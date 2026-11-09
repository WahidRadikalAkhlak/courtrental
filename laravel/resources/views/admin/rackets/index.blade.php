<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Perlengkapan - Admin Padel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: { lime: '#CCFF00', dark: '#080C14', card: '#0F1626' } } }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-brand-dark text-slate-100 min-h-screen">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ openCreateModal: false, editItem: null }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b border-slate-800 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Manajemen Perlengkapan Padel</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola stok sewa raket dan penjualan bola padel kaleng.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.fields.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:border-brand-lime">
                🎾 Lapangan
            </a>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:border-brand-lime">
                ← Dashboard
            </a>
            <button @click="openCreateModal = true" class="px-4 py-2 bg-brand-lime text-slate-950 rounded-xl font-bold text-xs shadow-lg shadow-brand-lime/20">
                + Tambah Item
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 p-4 bg-lime-400/10 border border-lime-400/30 text-lime-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Cards List Perlengkapan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @foreach($rackets as $item)
            <div class="bg-brand-card border border-slate-800 rounded-2xl overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="h-44 bg-slate-900 relative">
                        <img src="{{ $item->image ?: 'https://images.unsplash.com/photo-1617083934555-ac7d4fedc6b6?q=80&w=400&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded bg-black/70 text-brand-lime uppercase">
                            {{ $item->brand }}
                        </span>
                        <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-1 rounded {{ $item->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                            {{ $item->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-base font-bold text-slate-100">{{ $item->name }}</h3>
                        <div class="flex justify-between items-baseline mt-2">
                            <span class="text-brand-lime font-black text-lg">
                                Rp {{ number_format($item->price_per_session, 0, ',', '.') }}
                                <span class="text-xs text-slate-500 font-normal">/item</span>
                            </span>
                            <span class="text-xs font-semibold {{ $item->stock > 0 ? 'text-slate-400' : 'text-rose-400' }}">
                                Stok: {{ $item->stock }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0 flex gap-2 border-t border-slate-800/80 mt-2">
                    <button @click="editItem = {{ json_encode($item) }}" class="flex-1 py-2 text-xs font-semibold bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl">
                        Edit
                    </button>
                    <form action="{{ route('admin.rackets.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus perlengkapan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-2 px-3 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 rounded-xl">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Tambah Perlengkapan -->
    <div x-show="openCreateModal" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-brand-card border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto" x-data="{ sourceMode: 'upload', category: 'racket' }">
            <h2 class="text-lg font-bold mb-4">Tambah Perlengkapan Padel</h2>
            <form action="{{ route('admin.rackets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Kategori Produk</label>
                    <select name="category" x-model="category" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                        <option value="racket">Sewa Raket Padel</option>
                        <option value="ball">Penjualan Bola Padel (Per Tabung)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nama Item</label>
                    <input type="text" name="name" required placeholder="Contoh: Babolat Counter Vertuo Pro" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Label Merk / Tipe</label>
                    <input type="text" name="brand" placeholder="Contoh: Babolat (Sewa Raket)" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Harga (Rp)</label>
                        <input type="number" name="price_per_session" required placeholder="35000" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Stok Tersedia</label>
                        <input type="number" name="stock" required placeholder="10" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-800">
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Foto Produk</label>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <button type="button" @click="sourceMode = 'upload'" :class="sourceMode === 'upload' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                            Unggah File
                        </button>
                        <button type="button" @click="sourceMode = 'url'" :class="sourceMode === 'url' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                            Tautan URL
                        </button>
                    </div>

                    <div x-show="sourceMode === 'upload'">
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
                    </div>
                    <div x-show="sourceMode === 'url'" style="display: none;">
                        <input type="url" name="image_url" placeholder="https://..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2 text-xs rounded-xl bg-slate-900">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-lime text-slate-950">Simpan Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Perlengkapan -->
    <template x-if="editItem">
        <div class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
            <div class="bg-brand-card border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto" x-data="{ editSourceMode: 'upload' }">
                <h2 class="text-lg font-bold mb-4">Edit Perlengkapan</h2>
                <form :action="`/admin/rackets/${editItem.id}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Item</label>
                        <input type="text" name="name" x-model="editItem.name" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Label Brand / Kategori</label>
                        <input type="text" name="brand" x-model="editItem.brand" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Harga (Rp)</label>
                            <input type="number" name="price_per_session" x-model="editItem.price_per_session" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Stok</label>
                            <input type="number" name="stock" x-model="editItem.stock" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-800">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Foto Saat Ini</label>
                        <div class="mb-3 flex items-center gap-3 bg-slate-900 p-2 rounded-xl">
                            <img :src="editItem.image" class="w-12 h-12 object-cover rounded-lg bg-slate-800">
                            <span class="text-[11px] text-slate-400 truncate" x-text="editItem.image || 'Belum ada foto'"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <button type="button" @click="editSourceMode = 'upload'" :class="editSourceMode === 'upload' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                                Unggah File Baru
                            </button>
                            <button type="button" @click="editSourceMode = 'url'" :class="editSourceMode === 'url' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                                Ganti Tautan URL
                            </button>
                        </div>

                        <div x-show="editSourceMode === 'upload'">
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
                        </div>
                        <div x-show="editSourceMode === 'url'" style="display: none;">
                            <input type="url" name="image_url" x-model="editItem.image" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Status Ketersediaan</label>
                        <select name="is_active" x-model="editItem.is_active" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-100">
                            <option :value="1">Aktif (Tersedia untuk Dipesan)</option>
                            <option :value="0">Nonaktif (Habis/Tidak Disewakan)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
                        <button type="button" @click="editItem = null" class="px-4 py-2 text-xs rounded-xl bg-slate-900">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-lime text-slate-950">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

</body>
</html>
