<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padel Arena - Official Venue Booking & Equipment</title>
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
<body class="bg-brand-dark text-slate-100 min-h-screen selection:bg-brand-lime selection:text-black">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        courts: {{ json_encode($fields) }},
        addons: {{ json_encode($rackets) }},
        selectedDate: '{{ now()->format('Y-m-d') }}',
        slotsMaster: ['07:00','08:00','09:00','10:00','11:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'],
        selectedByCourt: {},
        bookedByCourt: {},
        addonCart: {},
        ratingValue: 5,

        initApp() {
            this.courts.forEach(c => {
                this.selectedByCourt[c.id] = [];
                this.bookedByCourt[c.id] = [];
            });
            this.addons.forEach(item => {
                this.addonCart[item.id] = 0;
            });
            this.fetchAllBookedSlots();
        },

        get racketList() {
            return this.addons.filter(i => !i.brand.toLowerCase().includes('beli') && !i.name.toLowerCase().includes('tabung'));
        },

        get ballList() {
            return this.addons.filter(i => i.brand.toLowerCase().includes('beli') || i.name.toLowerCase().includes('tabung'));
        },

        async fetchAllBookedSlots() {
            for (let c of this.courts) {
                try {
                    let res = await fetch(`/api/booked-slots?field_id=${c.id}&booking_date=${this.selectedDate}`);
                    let data = await res.json();
                    this.bookedByCourt[c.id] = data;
                } catch(e) {
                    this.bookedByCourt[c.id] = [];
                }
            }
        },

        toggleSlot(courtId, slot) {
            if (this.bookedByCourt[courtId] && this.bookedByCourt[courtId].includes(slot)) return;
            if (!this.selectedByCourt[courtId]) this.selectedByCourt[courtId] = [];

            if (this.selectedByCourt[courtId].includes(slot)) {
                this.selectedByCourt[courtId] = this.selectedByCourt[courtId].filter(s => s !== slot);
            } else {
                this.selectedByCourt[courtId].push(slot);
                this.selectedByCourt[courtId].sort();
            }
        },

        changeQty(itemId, delta) {
            let maxStock = this.addons.find(i => i.id === itemId)?.stock || 20;
            let current = this.addonCart[itemId] || 0;
            let next = current + delta;
            if (next >= 0 && next <= maxStock) {
                this.addonCart[itemId] = next;
            }
        },

        getTotalHours() {
            let total = 0;
            Object.values(this.selectedByCourt).forEach(arr => total += arr.length);
            return total;
        },

        getCourtPriceTotal() {
            let total = 0;
            this.courts.forEach(c => {
                let count = (this.selectedByCourt[c.id] || []).length;
                total += (count * c.price_per_hour);
            });
            return total;
        },

        getRacketPriceTotal() {
            let total = 0;
            this.racketList.forEach(item => {
                let qty = this.addonCart[item.id] || 0;
                total += (qty * item.price_per_session);
            });
            return total;
        },

        getBallPriceTotal() {
            let total = 0;
            this.ballList.forEach(item => {
                let qty = this.addonCart[item.id] || 0;
                total += (qty * item.price_per_session);
            });
            return total;
        },

        getGrandTotal() {
            return this.getCourtPriceTotal() + this.getRacketPriceTotal() + this.getBallPriceTotal();
        },

        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num || 0);
        }
     }"
     x-init="initApp(); $watch('selectedDate', () => fetchAllBookedSlots());">

    <!-- Venue Header -->
    <header class="mb-10 pb-6 border-b border-slate-800/80">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-brand-lime text-slate-950 font-black text-[11px] uppercase tracking-wider">Official Venue</span>
                    <span class="text-xs text-slate-400">Palu, Sulawesi Tengah</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Just Padel Club & Arena</h1>
                <p class="text-xs text-slate-400 mt-1">Standar Lapangan WPT Panoramic · Sewa Raket Pro · Beli Bola Kaleng · Ruang Tunggu AC</p>
            </div>

            <div class="flex items-center gap-3 bg-brand-card p-3 rounded-2xl border border-slate-800">
                <div class="flex items-center gap-1.5 text-amber-400">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="text-lg font-black text-slate-100">
                        {{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '0.0' }}
                    </span>
                </div>
                <div class="text-[11px] text-slate-400 border-l border-slate-700/80 pl-3">
                    <div class="font-bold text-slate-200">Ulasan Pengguna</div>
                    <div class="text-slate-400">{{ $reviews->count() }} Review Terdaftar</div>
                </div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Column (8 Cols) -->
        <div class="lg:col-span-8 space-y-12">
            <!-- 1. Jadwal Lapangan -->
            <section class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-brand-lime">Langkah 1</span>
                        <h2 class="text-2xl font-bold mt-1">Pilih Lapangan & Slot Jam</h2>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Tanggal Main</label>
                        <input type="date" x-model="selectedDate" min="{{ now()->format('Y-m-d') }}"
                               class="bg-brand-card border border-slate-800 rounded-xl px-4 py-2 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-lime transition">
                    </div>
                </div>

                <template x-for="court in courts" :key="court.id">
                    <div class="bg-brand-card border border-slate-800/90 rounded-3xl p-6 transition hover:border-slate-700">
                        <div class="flex flex-col md:flex-row gap-5 items-start pb-5 border-b border-slate-800/80">
                            <img :src="court.image || 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=800&auto=format&fit=crop'" class="w-full md:w-44 h-32 object-cover rounded-2xl border border-slate-800">
                            <div class="flex-1">
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-black/60 text-brand-lime" x-text="court.type"></span>
                                <h3 class="text-xl font-bold mt-1 text-slate-100" x-text="court.name"></h3>
                                <p class="text-xs text-slate-400 mt-1">Kaca Panoramic 12mm · Lampu LED Anti-Silau · Shock Absorption Turf</p>
                                <div class="text-base font-extrabold text-brand-lime mt-2">
                                    <span x-text="formatRupiah(court.price_per_hour)"></span> <span class="text-xs text-slate-400 font-normal">/jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <span class="block text-xs font-semibold text-slate-400 mb-3">Pilih Slot Jam Lapangan Ini:</span>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                                <template x-for="slot in slotsMaster" :key="slot">
                                    <button type="button"
                                            @click="toggleSlot(court.id, slot)"
                                            :disabled="bookedByCourt[court.id] && bookedByCourt[court.id].includes(slot)"
                                            :class="{
                                                'bg-slate-900/40 text-slate-600 border-slate-800/40 cursor-not-allowed line-through': bookedByCourt[court.id] && bookedByCourt[court.id].includes(slot),
                                                'bg-brand-lime text-slate-950 font-bold border-brand-lime shadow-lg shadow-brand-lime/20 scale-105': selectedByCourt[court.id] && selectedByCourt[court.id].includes(slot),
                                                'bg-slate-900 text-slate-300 border-slate-800 hover:border-slate-600': !selectedByCourt[court.id]?.includes(slot) && !bookedByCourt[court.id]?.includes(slot)
                                            }"
                                            class="py-2.5 px-2 rounded-xl text-xs font-semibold border transition-all text-center">
                                        <span x-text="slot"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </section>

            <!-- 2. Bagian Sewa Raket Padel -->
            <section class="space-y-6 pt-4 border-t border-slate-800/80">
                <div>
                    <span class="text-xs uppercase tracking-wider font-bold text-brand-lime">Langkah 2 (Opsional)</span>
                    <h2 class="text-2xl font-bold mt-1">Sewa Raket Padel</h2>
                    <p class="text-xs text-slate-400 mt-1">Pilih jumlah raket siap pakai yang disediakan di arena permainan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="racket in racketList" :key="racket.id">
                        <div class="bg-brand-card border border-slate-800/80 rounded-2xl p-4 flex gap-4 items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img :src="racket.image || 'https://images.unsplash.com/photo-1617083934555-ac7d4fedc6b6?q=80&w=400&auto=format&fit=crop'" class="w-16 h-16 object-cover rounded-xl border border-slate-800 bg-slate-900">
                                <div>
                                    <span class="text-[10px] text-brand-lime font-bold uppercase" x-text="racket.brand"></span>
                                    <h4 class="text-sm font-bold text-slate-200" x-text="racket.name"></h4>
                                    <div class="text-xs font-semibold text-slate-300 mt-1" x-text="formatRupiah(racket.price_per_session) + ' /sesi'"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-xl p-1">
                                <button type="button" @click="changeQty(racket.id, -1)" class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold transition">-</button>
                                <span class="w-6 text-center text-xs font-mono font-bold" x-text="addonCart[racket.id] || 0"></span>
                                <button type="button" @click="changeQty(racket.id, 1)" class="w-7 h-7 rounded-lg bg-brand-lime hover:bg-lime-300 text-slate-950 text-xs font-bold transition">+</button>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- 3. Bagian Pembelian Bola Padel -->
            <section class="space-y-6 pt-4 border-t border-slate-800/80">
                <div>
                    <span class="text-xs uppercase tracking-wider font-bold text-amber-400">Langkah 3 (Opsional)</span>
                    <h2 class="text-2xl font-bold mt-1">Pembelian Bola Padel</h2>
                    <p class="text-xs text-slate-400 mt-1">Kaleng bola baru tersegel bertekanan standar turnamen resmi.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="ball in ballList" :key="ball.id">
                        <div class="bg-brand-card border border-slate-800/80 rounded-2xl p-4 flex gap-4 items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img :src="ball.image || 'https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?q=80&w=400&auto=format&fit=crop'" class="w-16 h-16 object-cover rounded-xl border border-slate-800 bg-slate-900">
                                <div>
                                    <span class="text-[10px] text-amber-400 font-bold uppercase" x-text="ball.brand"></span>
                                    <h4 class="text-sm font-bold text-slate-200" x-text="ball.name"></h4>
                                    <div class="text-xs font-semibold text-slate-300 mt-1" x-text="formatRupiah(ball.price_per_session) + ' /tabung'"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-xl p-1">
                                <button type="button" @click="changeQty(ball.id, -1)" class="w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold transition">-</button>
                                <span class="w-6 text-center text-xs font-mono font-bold" x-text="addonCart[ball.id] || 0"></span>
                                <button type="button" @click="changeQty(ball.id, 1)" class="w-7 h-7 rounded-lg bg-brand-lime hover:bg-lime-300 text-slate-950 text-xs font-bold transition">+</button>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- 4. Bagian Ulasan & Form Tulis Ulasan Langsung -->
            <section class="space-y-6 pt-4 border-t border-slate-800/80">
                <div>
                    <span class="text-xs uppercase tracking-wider font-bold text-brand-lime">Review & Pengalaman</span>
                    <h2 class="text-2xl font-bold mt-1">Ulasan Pengguna Lapangan</h2>
                    <p class="text-xs text-slate-400 mt-1">Lihat pengalaman komunitas atau berikan penilaian Anda di bawah ini.</p>
                </div>

                <!-- Form Berikan Ulasan Langsung di Halaman -->
                <div class="bg-brand-card border border-slate-800/90 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-base font-bold text-slate-200 mb-1">Tulis Ulasan & Beri Bintang</h3>
                    <p class="text-xs text-slate-400 mb-4">Bagikan kesan bermain, kualitas rumput turf, AC, atau pelayanan.</p>

                    <form action="{{ route('review.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="customer_name" required placeholder="Contoh: Andi Wijaya"
                                       class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">Pilih Lapangan</label>
                                <select name="field_id" required
                                        class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                                    @foreach($fields as $court)
                                        <option value="{{ $court->id }}">{{ $court->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1.5">Rating Bintang</label>
                            <input type="hidden" name="rating" :value="ratingValue">
                            <div class="flex items-center gap-2">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="ratingValue = star" class="text-2xl transition focus:outline-none"
                                            :class="star <= ratingValue ? 'text-amber-400 hover:scale-110' : 'text-slate-700 hover:text-slate-500'">
                                        ★
                                    </button>
                                </template>
                                <span class="text-xs text-slate-400 ml-2 font-mono" x-text="ratingValue + ' dari 5 Bintang'"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Ulasan / Komentar</label>
                            <textarea name="comment" rows="2" required placeholder="Ceritakan bagaimana kondisi lapangan dan fasilitasnya..."
                                      class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-brand-lime text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-brand-lime/20 hover:bg-lime-300 transition">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Ulasan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($reviews as $rev)
                        <div class="bg-brand-card border border-slate-800/80 rounded-2xl p-5 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-sm text-slate-200">{{ $rev->customer_name }}</span>
                                <div class="flex text-amber-400 text-xs">
                                    @for($i=1; $i<=$rev->rating; $i++) ★ @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 italic">"{{ $rev->comment }}"</p>
                            <div class="text-[10px] text-slate-500 pt-1">Lapangan: {{ $rev->field->name }}</div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-10 bg-brand-card/40 border border-slate-800/60 rounded-3xl">
                            <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500 mx-auto mb-2 text-sm">
                                ★
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Belum ada ulasan dari pengguna.</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Jadilah yang pertama memberikan penilaian pengalaman bermain di atas!</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Sidebar Rincian Reservasi (4 Cols) -->
        <div class="lg:col-span-4">
            <div class="bg-brand-card border border-slate-800/90 rounded-3xl p-6 sm:p-7 sticky top-6 shadow-2xl">
                <h3 class="text-lg font-bold tracking-tight pb-4 border-b border-slate-800">Ringkasan Reservasi</h3>

                <form action="{{ route('booking.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="booking_date" :value="selectedDate">
                    <input type="hidden" name="selections" :value="JSON.stringify(selectedByCourt)">
                    <input type="hidden" name="rackets" :value="JSON.stringify(addonCart)">

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Pemesan</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Alex Pratama"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">Email Aktif</label>
                        <input type="email" name="customer_email" required placeholder="alex@gmail.com"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" required placeholder="08xxxxxxxx"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-lime">
                    </div>

                    <!-- Breakdown Biaya Terpisah -->
                    <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-800 space-y-3 text-xs mt-6">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tanggal Sewa</span>
                            <span class="font-semibold text-slate-200" x-text="selectedDate"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Total Durasi Lapangan</span>
                            <span class="font-semibold text-brand-lime" x-text="getTotalHours() + ' Jam'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Biaya Lapangan</span>
                            <span class="font-semibold text-slate-200" x-text="formatRupiah(getCourtPriceTotal())"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Sewa Raket</span>
                            <span class="font-semibold text-slate-200" x-text="formatRupiah(getRacketPriceTotal())"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Beli Bola Padel</span>
                            <span class="font-semibold text-slate-200" x-text="formatRupiah(getBallPriceTotal())"></span>
                        </div>

                        <div class="pt-3 border-t border-slate-800 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-300">Total Pembayaran</span>
                            <span class="text-xl font-black text-brand-lime" x-text="formatRupiah(getGrandTotal())"></span>
                        </div>
                    </div>

                    <button type="submit"
                            :disabled="getTotalHours() === 0"
                            :class="getTotalHours() > 0 ? 'bg-brand-lime hover:bg-lime-300 text-slate-950 font-black cursor-pointer shadow-lg shadow-brand-lime/20' : 'bg-slate-800 text-slate-500 cursor-not-allowed'"
                            class="w-full py-4 rounded-xl text-sm uppercase tracking-wider transition-all mt-4">
                        Kirim Reservasi (<span x-text="getTotalHours()"></span> Jam)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
