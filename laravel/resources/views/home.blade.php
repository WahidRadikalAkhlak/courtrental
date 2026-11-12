<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Padel - Official Court & Club Palu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { brand: { lime: '#CCFF00', dark: '#080C14', card: '#0F1626' } }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-brand-dark text-slate-100 selection:bg-brand-lime selection:text-black">

    <!-- Navbar Transparan Melayang (Tanpa Tombol Admin) -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-brand-dark/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-lime flex items-center justify-center font-black text-slate-950 text-xl shadow-lg shadow-brand-lime/20">
                    P
                </div>
                <div>
                    <span class="font-extrabold text-lg tracking-tight block leading-none">PREMIUM PADEL</span>
                    <span class="text-[10px] text-brand-lime uppercase tracking-widest font-semibold">Palu · Sulawesi Tengah</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                <a href="#beranda" class="hover:text-brand-lime transition">Home</a>
                <a href="#lapangan" class="hover:text-brand-lime transition">Lapangan & Tarif</a>
                <a href="#fasilitas" class="hover:text-brand-lime transition">Fasilitas</a>
                <a href="#ulasan" class="hover:text-brand-lime transition">Ulasan</a>
                <a href="#lokasi" class="hover:text-brand-lime transition">Lokasi & Kontak</a>
            </div>

            <div>
                <a href="{{ route('booking.index') }}" class="px-5 py-2.5 rounded-xl bg-brand-lime hover:bg-lime-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-brand-lime/20 transition">
                    Booking Jadwal
                </a>
            </div>
        </div>
    </nav>

    <!-- Notifikasi Sukses Setelah Mengirim Ulasan -->
    @if(session('success'))
        <div class="fixed bottom-6 right-6 z-50 max-w-md p-4 bg-emerald-950/95 backdrop-blur border border-emerald-500/40 text-emerald-300 rounded-2xl shadow-2xl flex items-center gap-3 text-xs">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Full-Screen Hero Banner -->
    <section id="beranda" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=1920&auto=format&fit=crop" alt="Premium Padel Court Palu" class="w-full h-full object-cover object-center scale-105">
            <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/80 via-brand-dark/70 to-brand-dark"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-brand-lime mb-6">
                <span>📍 Jl. I Gusti Ngurah Rai, Palu, Sulawesi Tengah</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-tight">
                Focus on the game,<br>
                <span class="text-brand-lime">we'll take care of the court.</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Nikmati pengalaman bermain padel berstandar internasional di <strong>Premium Padel Palu</strong>. Dilengkapi lapangan panoramik, sewa raket pro, dan reservasi jadwal secara instan.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('booking.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-brand-lime hover:bg-lime-300 text-slate-950 font-black text-sm uppercase tracking-wider shadow-2xl shadow-brand-lime/30 transition transform hover:-translate-y-0.5">
                    Pesan Lapangan Sekarang →
                </a>
                <a href="#lapangan" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 text-white font-bold text-sm transition">
                    Lihat Daftar Lapangan
                </a>
            </div>

            <!-- Sorotan Cepat -->
            <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
                <div class="bg-brand-card/80 backdrop-blur-md border border-slate-800/80 p-4 rounded-2xl text-center">
                    <div class="w-10 h-10 rounded-full bg-brand-lime/15 text-brand-lime flex items-center justify-center mx-auto mb-2 font-bold">🏟️</div>
                    <div class="text-xs font-bold text-slate-100">{{ $fields->count() }} Lapangan Pro</div>
                    <div class="text-[10px] text-slate-400">Indoor & Outdoor</div>
                </div>
                <div class="bg-brand-card/80 backdrop-blur-md border border-slate-800/80 p-4 rounded-2xl text-center">
                    <div class="w-10 h-10 rounded-full bg-brand-lime/15 text-brand-lime flex items-center justify-center mx-auto mb-2 font-bold">🏓</div>
                    <div class="text-xs font-bold text-slate-100">Sewa Raket</div>
                    <div class="text-[10px] text-slate-400">Babolat & Head Original</div>
                </div>
                <div class="bg-brand-card/80 backdrop-blur-md border border-slate-800/80 p-4 rounded-2xl text-center">
                    <div class="w-10 h-10 rounded-full bg-brand-lime/15 text-brand-lime flex items-center justify-center mx-auto mb-2 font-bold">🎾</div>
                    <div class="text-xs font-bold text-slate-100">Bola Turnamen</div>
                    <div class="text-[10px] text-slate-400">Tersedia Per Tabung</div>
                </div>
                <a href="#ulasan" class="bg-brand-card/80 backdrop-blur-md border border-slate-800/80 hover:border-amber-400/50 p-4 rounded-2xl text-center transition">
                    <div class="w-10 h-10 rounded-full bg-amber-400/15 text-amber-400 flex items-center justify-center mx-auto mb-2 font-bold">★</div>
                    <div class="text-xs font-bold text-slate-100">
                        Rating {{ $totalReviews > 0 ? number_format($averageRating, 1) : '0.0' }}/5.0
                    </div>
                    <div class="text-[10px] text-slate-400">{{ $totalReviews }} Ulasan Pemain</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Section: Daftar Lapangan Kami -->
    <section id="lapangan" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-800/60">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest font-bold text-brand-lime">Arena Kami</span>
                <h2 class="text-3xl font-extrabold mt-2 tracking-tight">Pilihan Lapangan Premium Padel</h2>
                <p class="text-sm text-slate-400 mt-1">Pilih lapangan sesuai preferensi permainan Anda dan cek jadwal kosong secara langsung.</p>
            </div>
            <a href="{{ route('booking.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-brand-lime text-xs font-bold text-slate-200 transition">
                Cek Ketersediaan Jadwal →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($fields as $field)
                <div class="group bg-brand-card border border-slate-800 rounded-3xl overflow-hidden hover:border-brand-lime/60 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-64 overflow-hidden bg-slate-900">
                            <img src="{{ $field->image ?: 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $field->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-card via-transparent to-transparent"></div>
                            <span class="absolute top-4 left-4 text-xs uppercase font-extrabold tracking-widest px-3 py-1 rounded-lg bg-black/70 backdrop-blur text-brand-lime border border-white/10">
                                {{ $field->type }}
                            </span>
                        </div>

                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-slate-100">{{ $field->name }}</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Menggunakan kaca tempered panoramic 12mm berstandar World Padel Tour (WPT), karpet monofilament turf berdensitas tinggi, serta pencahayaan LED anti-silau untuk permainan malam hari.
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block">Tarif Sewa</span>
                            <span class="text-xl font-black text-brand-lime">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-500">/jam</span>
                        </div>
                        <a href="{{ route('booking.index') }}" class="px-5 py-3 rounded-xl bg-brand-lime hover:bg-lime-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider transition">
                            Pilih Jadwal
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section: Fasilitas Venue -->
    <section id="fasilitas" class="py-20 bg-brand-card/40 border-y border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs uppercase tracking-widest font-bold text-brand-lime">Kenyamanan Pemain</span>
                <h2 class="text-3xl font-extrabold mt-2 tracking-tight">Fasilitas Lengkap di Lokasi</h2>
                <p class="text-sm text-slate-400 mt-2">Semua kebutuhan pertandingan dan kenyamanan istirahat tersedia di satu tempat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
                    <div class="text-2xl mb-3">🚿</div>
                    <h3 class="font-bold text-base text-slate-100">Shower & Locker Room</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Ruang ganti bersih ber-AC lengkap dengan shower air hangat dan loker penyimpanan barang aman.</p>
                </div>
                <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
                    <div class="text-2xl mb-3">☕</div>
                    <h3 class="font-bold text-base text-slate-100">Lounge & Refreshment</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Area santai dengan minuman isotonik, kopi segar, dan makanan ringan setelah bertanding.</p>
                </div>
                <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
                    <div class="text-2xl mb-3">🏓</div>
                    <h3 class="font-bold text-base text-slate-100">Pro Shop & Rental</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Penyewaan raket seri terbaru serta penjualan bola padel kaleng tersegel dan overgrip.</p>
                </div>
                <div class="bg-brand-card border border-slate-800 p-6 rounded-2xl">
                    <div class="text-2xl mb-3">🅿️</div>
                    <h3 class="font-bold text-base text-slate-100">Area Parkir Luas</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Parkir mobil dan motor luas dengan penjagaan keamanan 24 jam di Jl. I Gusti Ngurah Rai.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Ulasan Pemain & Form Rating Langsung di Halaman Utama -->
    <section id="ulasan" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ ratingValue: 5 }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest font-bold text-brand-lime">Kata Mereka</span>
                <h2 class="text-3xl font-extrabold mt-2 tracking-tight">Ulasan & Rating Pemain</h2>
                <p class="text-sm text-slate-400 mt-1">Lihat pengalaman komunitas atau bagikan penilaian Anda setelah bermain di arena kami.</p>
            </div>

            <!-- Badge Rata-Rata Rating -->
            <div class="flex items-center gap-3 bg-brand-card px-4 py-3 rounded-2xl border border-slate-800">
                <div class="flex items-center gap-1.5 text-amber-400">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="text-xl font-black text-slate-100">
                        {{ $totalReviews > 0 ? number_format($averageRating, 1) : '0.0' }}
                    </span>
                </div>
                <div class="text-xs text-slate-400 border-l border-slate-700/80 pl-3">
                    <div class="font-bold text-slate-200">Skor Kepuasan</div>
                    <div>Dari {{ $totalReviews }} ulasan pemain</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Form Beri Ulasan (5 Cols) -->
            <div class="lg:col-span-5 bg-brand-card border border-slate-800 rounded-3xl p-6 sm:p-7 shadow-xl">
                <h3 class="text-lg font-bold text-slate-100 mb-1">Tulis Ulasan Anda</h3>
                <p class="text-xs text-slate-400 mb-5">Bagaimana pengalaman bermain Anda di Premium Padel Palu?</p>

                <form action="{{ route('review.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Andi Wijaya"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Lapangan yang Digunakan</label>
                        <select name="field_id" required
                                class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                            @foreach($fields as $court)
                                <option value="{{ $court->id }}">{{ $court->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Rating Bintang</label>
                        <input type="hidden" name="rating" :value="ratingValue">
                        <div class="flex items-center gap-2 bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-2">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <button type="button" @click="ratingValue = star" class="text-2xl transition focus:outline-none"
                                        :class="star <= ratingValue ? 'text-amber-400 hover:scale-110' : 'text-slate-700 hover:text-slate-500'">
                                    ★
                                </button>
                            </template>
                            <span class="text-xs text-slate-400 ml-auto font-mono" x-text="ratingValue + ' / 5'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Ulasan / Komentar</label>
                        <textarea name="comment" rows="3" required placeholder="Ceritakan kondisi karpet, pencahayaan, atau fasilitas lainnya..."
                                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-brand-lime hover:bg-lime-300 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-brand-lime/20 transition">
                        Kirim Ulasan & Bintang
                    </button>
                </form>
            </div>

            <!-- Daftar Kartu Ulasan Pemain (7 Cols) -->
            <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($reviews as $rev)
                    <div class="bg-brand-card border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex text-amber-400 text-sm">
                                    @for($i=1; $i<=$rev->rating; $i++) ★ @endfor
                                </div>
                                <span class="text-[10px] text-slate-500">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-300 italic leading-relaxed">"{{ $rev->comment }}"</p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-100">{{ $rev->customer_name }}</span>
                            <span class="text-[10px] text-brand-lime font-medium">{{ $rev->field->name }}</span>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 text-center py-16 px-6 bg-brand-card/50 border border-slate-800 rounded-3xl">
                        <div class="w-12 h-12 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-amber-400 mx-auto mb-3 text-lg">
                            ★
                        </div>
                        <p class="text-sm font-bold text-slate-200">Belum ada ulasan pemain</p>
                        <p class="text-xs text-slate-400 mt-1">Jadilah yang pertama membagikan pengalaman bermain Anda melalui formulir di samping!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Section: Lokasi & Alamat Resmi Perusahaan -->
    <section id="lokasi" class="py-20 bg-brand-card border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <span class="text-xs uppercase tracking-widest font-bold text-brand-lime">Kunjungi Arena Kami</span>
                        <h2 class="text-3xl font-extrabold mt-2 tracking-tight">Premium Padel Palu</h2>
                    </div>

                    <div class="space-y-4 text-sm text-slate-300">
                        <div class="flex items-start gap-3">
                            <span class="text-brand-lime text-lg">📍</span>
                            <div>
                                <div class="font-bold text-slate-100">Alamat Lengkap</div>
                                <p class="text-slate-400 mt-0.5">Jl. I Gusti Ngurah Rai, Kota Palu, Sulawesi Tengah</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-brand-lime text-lg">⏰</span>
                            <div>
                                <div class="font-bold text-slate-100">Jam Operasional</div>
                                <p class="text-slate-400 mt-0.5">Setiap Hari: 07:00 – 22:00 WITA</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-brand-lime text-lg">📞</span>
                            <div>
                                <div class="font-bold text-slate-100">Reservasi & Informasi</div>
                                <p class="text-slate-400 mt-0.5">Pemesanan online 24 jam melalui sistem reservasi website.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="{{ route('booking.index') }}" class="px-6 py-3.5 rounded-xl bg-brand-lime hover:bg-lime-300 text-slate-950 font-extrabold text-xs uppercase tracking-wider transition">
                            Booking Lapangan
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Jalan+I+Gusti+Ngurah+Rai+Palu+Sulawesi+Tengah" target="_blank" class="px-6 py-3.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-brand-lime text-slate-200 font-bold text-xs uppercase tracking-wider transition">
                            Buka di Google Maps ↗
                        </a>
                    </div>
                </div>

                <!-- Embed Peta Lokasi Jl. I Gusti Ngurah Rai, Palu -->
                <div class="lg:col-span-7">
                    <div class="rounded-3xl overflow-hidden border border-slate-800 h-80 bg-slate-900 shadow-2xl">
                        <iframe
                            src="https://maps.google.com/maps?q=Jalan%20I%20Gusti%20Ngurah%20Rai,%20Palu,%20Sulawesi%20Tengah&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            class="w-full h-full border-0 filter invert-[90%] hue-rotate-180 contrast-90"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Bersih -->
    <footer class="py-8 border-t border-slate-800/80 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="font-bold text-slate-300">PREMIUM PADEL PALU</div>
            <div>Jl. I Gusti Ngurah Rai, Palu, Sulawesi Tengah</div>
            <div>&copy; {{ date('Y') }} Premium Padel. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>
