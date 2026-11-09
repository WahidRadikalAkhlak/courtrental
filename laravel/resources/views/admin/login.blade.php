<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: { lime: '#CCFF00', dark: '#080C14', card: '#0F1626' } } }
            }
        }
    </script>
</head>
<body class="bg-brand-dark text-slate-100 flex items-center justify-center min-h-screen px-4">

<div class="max-w-md w-full bg-brand-card border border-slate-800 rounded-3xl p-8 shadow-2xl">
    <div class="text-center mb-8">
        <div class="inline-flex w-12 h-12 rounded-2xl bg-brand-lime items-center justify-center font-black text-slate-950 text-2xl shadow-lg shadow-brand-lime/20 mb-3">
            P
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Admin Portal</h1>
        <p class="text-xs text-slate-400 mt-1">Masuk untuk mengelola jadwal dan data lapangan</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Email Administrator</label>
            <input type="email" name="email" value="{{ old('email', 'admin@padel.com') }}" required
                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-lime">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi</label>
            <input type="password" name="password" required placeholder="••••••••"
                   class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-lime">
        </div>
        <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-brand-lime hover:bg-lime-300 text-slate-950 font-bold text-sm tracking-wide transition shadow-lg shadow-brand-lime/20">
            Masuk ke Dashboard
        </button>
    </form>
</div>

</body>
</html>
