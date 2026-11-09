<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Padel Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { lime: '#CCFF00', dark: '#080C14', card: '#0F1626' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-dark text-slate-100 min-h-screen">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b border-slate-800 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Admin Booking Console</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola reservasi, verifikasi pesanan, dan pantau pendapatan harian.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.fields.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold hover:border-brand-lime text-slate-300 transition">
                Kelola Lapangan
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs font-semibold hover:bg-rose-500/20 transition">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 p-4 bg-lime-400/10 border border-lime-400/30 text-lime-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cards Rekapitulasi Harga & Jadwal -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 my-8">
        <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Total Pendapatan Terkonfirmasi</span>
            <div class="text-3xl font-black text-brand-lime mt-2">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Menunggu Konfirmasi</span>
            <div class="text-3xl font-black text-amber-400 mt-2">
                {{ $pendingBookings }} <span class="text-sm font-normal text-slate-400">pesanan</span>
            </div>
        </div>

        <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Total Seluruh Reservasi</span>
            <div class="text-3xl font-black text-slate-200 mt-2">
                {{ $totalBookings }} <span class="text-sm font-normal text-slate-400">jadwal</span>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap Jadwal & Konfirmasi -->
    <div class="bg-brand-card border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center">
            <h2 class="text-base font-bold text-slate-200">Jadwal Pemesanan Lapangan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Kontak (WA/Email)</th>
                        <th class="px-6 py-4">Lapangan</th>
                        <th class="px-6 py-4">Waktu Main & Durasi</th>
                        <th class="px-6 py-4">Total Biaya</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($bookings as $booking)
                        @php
                            $startTs = strtotime($booking->start_time);
                            $endTs   = strtotime($booking->end_time);
                            $durationHours = max(1, round(($endTs - $startTs) / 3600));
                        @endphp
                        <tr class="hover:bg-slate-900/30 transition">
                            <td class="px-6 py-4 font-semibold text-slate-100">
                                {{ $booking->customer_name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono text-slate-200">{{ $booking->customer_phone }}</div>
                                <div class="text-[11px] text-slate-500">{{ $booking->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                {{ $booking->field->name }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</div>
                                <div class="text-brand-lime font-mono font-bold mt-0.5">
                                    {{ date('H:i', $startTs) }} - {{ date('H:i', $endTs) }} WIB
                                    <span class="text-slate-400 font-normal">({{ $durationHours }} Jam)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-200 text-xs">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($booking->status === 'confirmed')
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">DITERIMA</span>
                                @elseif ($booking->status === 'pending')
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">PENDING</span>
                                @else
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20">DITOLAK</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="inline-flex gap-1.5">
                                        @csrf
                                        @method('PATCH')
                                        @if ($booking->status !== 'confirmed')
                                            <button name="status" value="confirmed" type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-emerald-500/20 hover:bg-emerald-500 hover:text-black text-emerald-400 transition">
                                                Terima
                                            </button>
                                        @endif
                                        @if ($booking->status !== 'cancelled')
                                            <button name="status" value="cancelled" type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-rose-500/20 hover:bg-rose-500 hover:text-white text-rose-400 transition">
                                                Tolak
                                            </button>
                                        @endif
                                    </form>

                                    @php
                                        $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->customer_phone));
                                        $formattedDate = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y');
                                        $formattedPrice = number_format($booking->total_price, 0, ',', '.');
                                        $timeRange = date('H:i', $startTs) . ' - ' . date('H:i', $endTs);

                                        $pesanWA = "Halo *{$booking->customer_name}*,\n\n"
                                                 . "Pesanan lapangan padel Anda di *PADEL ARENA* telah kami *SETUJUI*!\n\n"
                                                 . "Detail Reservasi:\n"
                                                 . "• Lapangan: {$booking->field->name}\n"
                                                 . "• Tanggal: {$formattedDate}\n"
                                                 . "• Waktu Main: {$timeRange} WIB ({$durationHours} Jam)\n"
                                                 . "• Total Biaya: Rp {$formattedPrice}\n\n"
                                                 . "Harap datang tepat waktu minimal 10 menit sebelum jadwal. Terima kasih!";
                                    @endphp

                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ rawurlencode($pesanWA) }}"
                                       target="_blank"
                                       title="Kirim Konfirmasi ke WhatsApp"
                                       class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                        </svg>
                                        <span>WA</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 text-xs">
                                Belum ada pesanan yang masuk. Semua jadwal kosong.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
