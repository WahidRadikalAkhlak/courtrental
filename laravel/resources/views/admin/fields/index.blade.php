<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Admin Padel</title>
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ openCreateModal: false, editField: null }">
    <div class="flex justify-between items-center pb-8 border-b border-slate-800">
    <div class="flex items-center gap-6">
        <h1 class="text-2xl font-bold tracking-tight">Manajemen Lapangan</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-brand-lime">← Kembali ke Jadwal & Pesanan</a>
    </div>
    <div class="flex items-center gap-3">
        <!-- Tombol Tambahan untuk Kelola Raket & Bola -->
        <a href="{{ route('admin.rackets.index') }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:border-brand-lime text-slate-200 rounded-xl font-bold text-xs transition">
            🏓 Kelola Raket & Bola
        </a>
        <button @click="openCreateModal = true" class="px-4 py-2 bg-brand-lime text-slate-950 rounded-xl font-bold text-xs shadow-lg shadow-brand-lime/20">
            + Tambah Lapangan
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

    <!-- Cards List Lapangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @foreach($fields as $field)
            <div class="bg-brand-card border border-slate-800 rounded-2xl overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="h-44 bg-slate-900 relative">
                        <img src="{{ $field->image ?: 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=800&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded bg-black/60 text-brand-lime uppercase">
                            {{ $field->type }}
                        </span>
                        <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-1 rounded {{ $field->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                            {{ $field->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold">{{ $field->name }}</h3>
                        <p class="text-brand-lime font-black text-xl mt-2">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }} <span class="text-xs text-slate-500 font-normal">/jam</span></p>
                    </div>
                </div>
                <div class="p-5 pt-0 flex gap-2">
                    <button @click="editField = {{ json_encode($field) }}" class="flex-1 py-2 text-xs font-semibold bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl">
                        Edit
                    </button>
                    <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus lapangan ini?');">
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

    <!-- Modal Tambah Lapangan -->
    <div x-show="openCreateModal" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-brand-card border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto" x-data="{ sourceMode: 'upload' }">
            <h2 class="text-lg font-bold mb-4">Tambah Lapangan Padel</h2>
            <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nama Lapangan</label>
                    <input type="text" name="name" required placeholder="Contoh: Court C - Panoramic" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Tipe</label>
                    <select name="type" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Harga per Jam (Rp)</label>
                    <input type="number" name="price_per_hour" required placeholder="250000" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                </div>

                <!-- Pilihan Sumber Gambar -->
                <div class="pt-2 border-t border-slate-800">
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Sumber Foto Lapangan</label>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <button type="button" @click="sourceMode = 'upload'" :class="sourceMode === 'upload' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                            Unggah dari File
                        </button>
                        <button type="button" @click="sourceMode = 'url'" :class="sourceMode === 'url' ? 'bg-brand-lime text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 border border-slate-800'" class="py-1.5 text-xs rounded-lg transition">
                            Tautan URL Web
                        </button>
                    </div>

                    <div x-show="sourceMode === 'upload'">
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
                    </div>
                    <div x-show="sourceMode === 'url'" style="display: none;">
                        <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2 text-xs rounded-xl bg-slate-900">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-lime text-slate-950">Simpan Lapangan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Lapangan -->
    <template x-if="editField">
        <div class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
            <div class="bg-brand-card border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto" x-data="{ editSourceMode: 'upload' }">
                <h2 class="text-lg font-bold mb-4">Edit Lapangan</h2>
                <form :action="`/admin/fields/${editField.id}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Lapangan</label>
                        <input type="text" name="name" x-model="editField.name" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Tipe</label>
                        <select name="type" x-model="editField.type" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Harga per Jam (Rp)</label>
                        <input type="number" name="price_per_hour" x-model="editField.price_per_hour" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                    </div>

                    <!-- Gambar Saat Ini & Opsi Perubahan -->
                    <div class="pt-2 border-t border-slate-800">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Foto Lapangan</label>
                        <div class="mb-3 flex items-center gap-3 bg-slate-900 p-2 rounded-xl">
                            <img :src="editField.image" class="w-12 h-12 object-cover rounded-lg bg-slate-800">
                            <span class="text-[11px] text-slate-400 truncate" x-text="editField.image || 'Belum ada foto'"></span>
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
                            <p class="text-[10px] text-slate-500 mt-1">Kosongkan jika tidak ingin mengganti file gambar saat ini.</p>
                        </div>
                        <div x-show="editSourceMode === 'url'" style="display: none;">
                            <input type="url" name="image_url" x-model="editField.image" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Status Lapangan</label>
                        <select name="is_active" x-model="editField.is_active" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100">
                            <option :value="1">Aktif</option>
                            <option :value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
                        <button type="button" @click="editField = null" class="px-4 py-2 text-xs rounded-xl bg-slate-900">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-lime text-slate-950">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

</body>
</html>
