<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminBookingController extends Controller
{
    public function index()
    {
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        $bookings = Booking::with('field')
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.dashboard', compact('bookings', 'totalRevenue', 'totalBookings', 'pendingBookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,pending',
        ]);

        $booking->update(['status' => $validated['status']]);

        // Kirim otomatis jika token Fonnte ada di file .env
        if ($validated['status'] === 'confirmed' && env('FONNTE_TOKEN')) {
            $this->sendAutomatedWhatsApp($booking);
        }

        return back()->with('success', 'Status pesanan #' . $booking->id . ' berhasil diubah menjadi ' . strtoupper($validated['status']));
    }

    private function sendAutomatedWhatsApp(Booking $booking)
    {
        $phone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->customer_phone));
        $startTs = strtotime($booking->start_time);
        $endTs   = strtotime($booking->end_time);
        $durationHours = max(1, round(($endTs - $startTs) / 3600));
        $formattedDate = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y');

        $message = "Halo *{$booking->customer_name}*,\n\n"
                 . "Pesanan lapangan padel Anda di *PADEL ARENA* telah kami *SETUJUI*!\n\n"
                 . "📋 *Detail Reservasi:*\n"
                 . "• Lapangan: {$booking->field->name}\n"
                 . "• Tanggal: {$formattedDate}\n"
                 . "• Waktu Main: " . date('H:i', $startTs) . " - " . date('H:i', $endTs) . " WIB ({$durationHours} Jam)\n"
                 . "• Total Biaya: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n"
                 . "Harap datang tepat waktu minimal 10 menit sebelum jadwal bermain. Terima kasih!";

        try {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Lanjutkan eksekusi jika API eksternal gagal
        }
    }
}
