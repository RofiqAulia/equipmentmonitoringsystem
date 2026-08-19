@extends('layouts.app')

@section('title', 'Login - Inventory Control System')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center py-6">
    <div class="w-full max-w-md">
        
        <!-- Header Brand Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-sky-600 shadow-xl shadow-sky-600/20 mb-4 border border-sky-400/30">
                <i class="fa-solid fa-boxes-stacked text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Inventory <span class="text-cyan-600 dark:text-cyan-400">Control</span></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Sistem Autentikasi Pengeluaran Barang Gudang</p>
        </div>

        <!-- Card Auth Wrapper -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Auth Mode Switch Tabs -->
            <div class="flex bg-slate-200/80 dark:bg-slate-900/90 p-1 rounded-xl mb-6 border border-slate-300 dark:border-slate-800" role="tablist">
                <button id="tab-classic-btn" onclick="switchAuthTab('classic')" 
                        class="flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30 shadow">
                    <i class="fa-solid fa-key mr-1.5"></i> Admin / Classic
                </button>
                <button id="tab-quick-btn" onclick="switchAuthTab('quick')" 
                        class="flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <i class="fa-solid fa-user-gear mr-1.5"></i> Quick Operator (SPV)
                </button>
            </div>

            <!-- Tab 1: Classic Login Form -->
            <div id="tab-classic" class="block space-y-5">
                <form action="{{ route('login.perform') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Username / Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>
                            <input type="text" name="login" id="login" required placeholder="admin atau email@inventory.com"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                        </div>
                        @error('login')
                            <p class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98]">
                        Masuk Sistem <i class="fa-solid fa-arrow-right ml-1.5"></i>
                    </button>
                </form>
            </div>

            <!-- Tab 2: Quick Operator Auth + Dynamic SPV Selection -->
            <div id="tab-quick" class="hidden space-y-5">
                <form action="{{ route('user.quick-auth') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pilih Akun Operator Gudang</label>
                        <select name="user_id" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 transition">
                            <option value="">-- Pilih Operator Gudang --</option>
                            @foreach(\App\Models\User::where('role', 'user')->get() as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->name }} ({{ $operator->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Hubungkan ke Supervisor (SPV)</label>
                        <select name="supervisor_id" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-xl text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 transition">
                            <option value="">-- Pilih SPV Penanggung Jawab --</option>
                            @foreach(\App\Models\User::where('role', 'admin')->get() as $spv)
                                <option value="{{ $spv->id }}">{{ $spv->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98]">
                        Autentikasi Cepat & Mulai Ambil Barang <i class="fa-solid fa-circle-check ml-1.5"></i>
                    </button>
                </form>
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-300 dark:border-slate-800"></div></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-slate-100 dark:bg-slate-900 px-3 text-slate-500 font-medium">atau masuk dengan</span></div>
            </div>

            <!-- Google OAuth SSO Button -->
            <a href="{{ route('auth.google') }}" class="w-full py-2.5 px-4 bg-white dark:bg-slate-900/90 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700/70 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-sm flex items-center justify-center space-x-2 transition shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Single Sign-On (Google OAuth 2.0)</span>
            </a>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800/80 text-xs text-slate-600 dark:text-slate-400 bg-slate-100/80 dark:bg-slate-950/40 p-3 rounded-xl">
                <div class="font-semibold text-slate-800 dark:text-slate-300 mb-1"><i class="fa-solid fa-info-circle text-cyan-600 dark:text-cyan-400 mr-1"></i> Demo Quick Login Credentials:</div>
                <div class="grid grid-cols-2 gap-2 text-[11px] font-mono">
                    <div>
                        <span class="text-cyan-600 dark:text-cyan-400 font-bold">Admin:</span><br>
                        User: <code class="text-slate-800 dark:text-slate-200">admin</code><br>
                        Pass: <code class="text-slate-800 dark:text-slate-200">password123</code>
                    </div>
                    <div>
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">SPV / Operator:</span><br>
                        User: <code class="text-slate-800 dark:text-slate-200">spv_budi</code> / <code class="text-slate-800 dark:text-slate-200">op_mikaela</code><br>
                        Pass: <code class="text-slate-800 dark:text-slate-200">password123</code>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
