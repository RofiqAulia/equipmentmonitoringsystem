@extends('layouts.app')

@section('title', 'Reset Password - Inventory Control')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-6 px-4">
    <div class="w-full max-w-md space-y-6">
        
        <!-- Header Logo / Brand -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-3xl bg-gradient-to-br from-cyan-500 to-sky-600 shadow-xl shadow-cyan-500/20 border border-cyan-400/30 mb-3">
                <i class="fa-solid fa-key text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Reset <span class="text-cyan-600 dark:text-cyan-400">Password</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-semibold">
                Buat kata sandi baru yang aman untuk akun Anda
            </p>
        </div>

        <!-- Card Form Container -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden border border-slate-200 dark:border-slate-800">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" id="email" value="{{ request('email', old('email')) }}" required readonly
                               class="w-full pl-10 pr-4 py-3 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-mono font-bold text-slate-600 dark:text-slate-400 cursor-not-allowed">
                    </div>
                    @error('email')
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Password Baru *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                               class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                    </div>
                    @error('password')
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Konfirmasi Password Baru *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-shield-check text-sm"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password baru"
                               class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-600 to-sky-600 hover:from-cyan-500 hover:to-sky-500 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-cyan-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <span>Simpan Password Baru</span>
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-400 transition">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Halaman Login
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
