<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Racket;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $fields = Field::with('reviews')->where('is_active', true)->get();
        $rackets = Racket::where('is_active', true)->where('stock', '>', 0)->get();
        $reviews = Review::with('field')->latest()->take(6)->get();

        return view('booking', compact('fields', 'rackets', 'reviews'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'field_id'      => 'required|exists:fields,id',
            'customer_name' => 'required|string|max:80',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:500',
        ]);

        Review::create($validated);

        return back()->with('success', 'Ulasan dan penilaian bintang berhasil dikirim!');
    }

    public function getBookedSlots(Request $request)
    {
        $request->validate([
            'field_id'     => 'required|exists:fields,id',
            'booking_date' => 'required|date',
        ]);

        $bookings = Booking::where('field_id', $request->field_id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time', 'end_time']);

        $bookedSlots = [];
        foreach ($bookings as $b) {
            $cur = strtotime($b->start_time);
            $end = strtotime($b->end_time);
            while ($cur < $end) {
                $bookedSlots[] = date('H:i', $cur);
                $cur = strtotime('+1 hour', $cur);
            }
        }

        return response()->json(array_values(array_unique($bookedSlots)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => 'required|string|max:20',
            'booking_date'   => 'required|date|after_or_equal:today',
            'selections'     => 'required|json',
            'rackets'        => 'nullable|json',
        ]);

        $selections = json_decode($validated['selections'], true);
        $racketInput = json_decode($validated['rackets'] ?? '{}', true) ?: [];
        $totalHoursBooked = 0;

        // Hitung total biaya sewa raket dan pembelian bola
        $racketItems = [];
        $totalAddonFee = 0;
        foreach ($racketInput as $racketId => $qty) {
            if ($qty > 0) {
                $item = Racket::find($racketId);
                if ($item) {
                    $subtotal = $item->price_per_session * $qty;
                    $totalAddonFee += $subtotal;
                    $racketItems[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'qty' => $qty,
                        'price_each' => $item->price_per_session,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        return DB::transaction(function () use ($validated, $selections, $racketItems, $totalAddonFee, &$totalHoursBooked) {
            $firstBooking = null;

            foreach ($selections as $fieldId => $slots) {
                if (empty($slots) || !is_array($slots)) continue;

                $field = Field::findOrFail($fieldId);
                sort($slots);

                // Cek ketersediaan slot
                foreach ($slots as $slot) {
                    $startCheck = date('H:i:00', strtotime($slot));
                    $endCheck   = date('H:i:00', strtotime($slot . ' +1 hour'));

                    $conflict = Booking::where('field_id', $fieldId)
                        ->where('booking_date', $validated['booking_date'])
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->where(function ($query) use ($startCheck, $endCheck) {
                            $query->where('start_time', '<', $endCheck)
                                  ->where('end_time', '>', $startCheck);
                        })
                        ->lockForUpdate()
                        ->exists();

                    if ($conflict) {
                        return back()->withErrors(['error' => "Slot jam {$slot} di lapangan {$field->name} sudah diambil orang lain."]);
                    }
                }

                // Gabungkan jam berurutan
                $mergedIntervals = [];
                $tempGroup = [];
                foreach ($slots as $s) {
                    if (empty($tempGroup)) {
                        $tempGroup[] = $s;
                    } else {
                        $lastSlot = end($tempGroup);
                        if (strtotime($s) == strtotime($lastSlot . ' +1 hour')) {
                            $tempGroup[] = $s;
                        } else {
                            $mergedIntervals[] = $tempGroup;
                            $tempGroup = [$s];
                        }
                    }
                }
                if (!empty($tempGroup)) $mergedIntervals[] = $tempGroup;

                foreach ($mergedIntervals as $group) {
                    $firstSlot = $group[0];
                    $lastSlot  = end($group);
                    $startTime = date('H:i:00', strtotime($firstSlot));
                    $endTime   = date('H:i:00', strtotime($lastSlot . ' +1 hour'));
                    $hourCount = count($group);
                    $totalHoursBooked += $hourCount;

                    $courtPrice = $field->price_per_hour * $hourCount;
                    $currentAddonFee = ($firstBooking === null) ? $totalAddonFee : 0;
                    $currentAddonItems = ($firstBooking === null) ? $racketItems : null;

                    $created = Booking::create([
                        'field_id'       => $field->id,
                        'customer_name'  => $validated['customer_name'],
                        'customer_email' => $validated['customer_email'],
                        'customer_phone' => $validated['customer_phone'],
                        'booking_date'   => $validated['booking_date'],
                        'start_time'     => $startTime,
                        'end_time'       => $endTime,
                        'racket_items'   => $currentAddonItems,
                        'racket_price'   => $currentAddonFee,
                        'total_price'    => $courtPrice + $currentAddonFee,
                        'status'         => 'pending',
                    ]);

                    if ($firstBooking === null) {
                        $firstBooking = $created;
                    }
                }
            }

            if ($totalHoursBooked === 0) {
                return back()->withErrors(['error' => 'Silakan tentukan minimal 1 slot jam lapangan.']);
            }

            return redirect()->route('booking.index')->with('success', "Pesanan lapangan beserta perlengkapan berhasil dikirim! Menunggu konfirmasi admin.");
        });
    }

    public function checkout(Booking $booking)
    {
        $booking->load('field');
        return view('checkout', compact('booking'));
    }

    public function success()
    {
        return view('status.success');
    }

    public function pending()
    {
        return view('status.pending');
    }
}
