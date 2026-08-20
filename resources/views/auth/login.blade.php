@extends('layouts.app')

@section('title', 'Login System - Inventory Control')

@push('styles')
<style>
    /* Custom Keyframe Animations for Left Visual Column */
    @keyframes laserScan {
        0%, 100% {
            top: 10%;
            opacity: 0.4;
        }
        50% {
            top: 85%;
            opacity: 1;
            box-shadow: 0 0 15px #06b6d4, 0 0 30px #0284c7;
        }
    }

    @keyframes floatSlow {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-12px) rotate(1.5deg);
        }
    }

    @keyframes floatReverse {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(12px) rotate(-1.5deg);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            opacity: 0.3;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.08);
        }
    }

    .animate-laser {
        animation: laserScan 3s ease-in-out infinite;
    }

    .animate-float-slow {
        animation: floatSlow 5s ease-in-out infinite;
    }

    .animate-float-reverse {
        animation: floatReverse 6s ease-in-out infinite;
    }

    .animate-pulse-glow {
        animation: pulseGlow 4s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-6 px-2 sm:px-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        
        <!-- LEFT COLUMN: Interactive Animation & Visual Artwork -->
        <div class="lg:col-span-6 space-y-6 flex flex-col justify-center order-2 lg:order-1">
            <div class="relative glass-panel p-8 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 overflow-hidden shadow-2xl bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-cyan-950/40">
                
                <!-- Glowing Ambient Lights -->
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-cyan-500/20 rounded-full blur-3xl animate-pulse-glow pointer-events-none"></div>
                <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-sky-600/20 rounded-full blur-3xl animate-pulse-glow pointer-events-none" style="animation-delay: 2s;"></div>

                <!-- Animated Container Content -->
                <div class="relative z-10 space-y-8 text-center sm:text-left">
                    
                    <!-- Badge Header -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-extrabold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping mr-1"></span>
                        <span>Smart Warehouse System 2.0</span>
                    </div>

                    <!-- Main Hero Title -->
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                            Manajemen Inventaris <br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-400">
                                Berbasis Live Camera QR Code
                            </span>
                        </h2>
                        <p class="text-slate-400 text-xs sm:text-sm mt-3 leading-relaxed">
                            Proses penerbitan label QR oleh Admin, pemindaian real-time oleh Operator, dan pengurangan stok database secara otomatis dengan sistem perlindungan transaksi real-time.
                        </p>
                    </div>

                    <!-- Interactive Animated QR Scanner Graphic Box -->
                    <div class="relative max-w-sm mx-auto sm:mx-0 p-6 glass-card rounded-2xl border border-cyan-500/30 bg-slate-950/70 shadow-2xl flex flex-col items-center justify-center space-y-4 animate-float-slow">
                        
                        <!-- Glowing Floating Badge top right -->
                        <div class="absolute -top-3 -right-3 px-3 py-1 bg-emerald-500 text-white font-extrabold text-[10px] rounded-full shadow-lg border border-emerald-300 flex items-center space-x-1 animate-float-reverse">
                            <i class="fa-solid fa-shield-check text-xs"></i>
                            <span>DB Lock Protection</span>
                        </div>

                        <!-- Animated Scanning Frame -->
                        <div class="relative w-40 h-40 rounded-xl bg-slate-900 border-2 border-cyan-500/40 p-3 flex items-center justify-center shadow-inner overflow-hidden">
                            <!-- Scanning Laser Line -->
                            <div class="absolute left-0 right-0 h-1 bg-gradient-to-r from-transparent via-cyan-400 to-transparent border-t border-cyan-300 shadow-md animate-laser z-20"></div>

                            <!-- Simulated QR Code SVG Graphic -->
                            <div class="relative z-10 w-full h-full flex items-center justify-center bg-white p-2 rounded-lg shadow">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SKU-SAMPLE-INVENTORY-SYSTEM" alt="Sample QR Graphic" class="w-full h-full object-contain">
                            </div>
                        </div>

                        <!-- Scanning Graphic Footer Status -->
                        <div class="flex items-center justify-between w-full pt-1 border-t border-slate-800 text-[11px]">
                            <span class="text-cyan-400 font-mono font-bold flex items-center">
                                <i class="fa-solid fa-barcode mr-1.5"></i> Live Camera Ready
                            </span>
                            <span class="text-slate-400 font-medium">Auto-Sync 100%</span>
                        </div>
                    </div>

                    <!-- Feature List Indicators -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-white leading-snug">Instant Scan</div>
                                <div class="text-[9px] text-slate-400">Kamera & Payload</div>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-white leading-snug">Qty Stepper</div>
                                <div class="text-[9px] text-slate-400">Kurangi Real-time</div>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-white leading-snug">Low Stock Alert</div>
                                <div class="text-[9px] text-slate-400">Warning Otomatis</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Elegant Login Form Panel -->
        <div class="lg:col-span-6 space-y-6 order-1 lg:order-2">
            
            <!-- Brand Logo Header -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-sky-600 shadow-xl shadow-sky-600/30 flex items-center justify-center border border-sky-400/40">
                        <i class="fa-solid fa-boxes-stacked text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            Inventory <span class="text-cyan-600 dark:text-cyan-400">Control</span>
                        </h1>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">Gudang Monitoring & Retrieval System</span>
                    </div>
                </div>
            </div>

            <!-- Card Auth Form Container -->
            <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Auth Mode Switch Tabs -->
                <div class="flex bg-slate-200/80 dark:bg-slate-900/90 p-1 rounded-xl mb-6 border border-slate-300 dark:border-slate-800" role="tablist">
                    <button id="tab-classic-btn" onclick="switchAuthTab('classic')" 
                            class="flex-1 py-2.5 rounded-lg text-xs sm:text-sm font-extrabold transition bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30 shadow">
                        <i class="fa-solid fa-key mr-1.5"></i> Admin / Classic
                    </button>
                    <button id="tab-quick-btn" onclick="switchAuthTab('quick')" 
                            class="flex-1 py-2.5 rounded-lg text-xs sm:text-sm font-extrabold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                        <i class="fa-solid fa-user-gear mr-1.5"></i> Quick Operator (SPV)
                    </button>
                </div>

                <!-- Tab 1: Classic Login Form -->
                <div id="tab-classic" class="block space-y-5">
                    <form action="{{ route('login.perform') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="login" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Username / Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </span>
                                <input type="text" name="login" id="login" required placeholder="admin atau email@inventory.com"
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition shadow-sm">
                            </div>
                            @error('login')
                                <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password" id="password" required placeholder="••••••••"
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition shadow-sm">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-500 hover:to-cyan-500 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                            <span>Masuk Sistem Admin</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Tab 2: Quick Operator Auth + Dynamic SPV Selection -->
                <div id="tab-quick" class="hidden space-y-5">
                    <form action="{{ route('user.quick-auth') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Akun Operator Gudang</label>
                            <select name="user_id" required class="w-full px-3.5 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                                <option value="">-- Pilih Operator Gudang --</option>
                                @foreach(\App\Models\User::where('role', 'user')->get() as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->name }} ({{ $operator->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Hubungkan ke Supervisor (SPV)</label>
                            <select name="supervisor_id" required class="w-full px-3.5 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                                <option value="">-- Pilih SPV Penanggung Jawab --</option>
                                @foreach(\App\Models\User::where('role', 'admin')->get() as $spv)
                                    <option value="{{ $spv->id }}">{{ $spv->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-600 to-sky-600 hover:from-cyan-500 hover:to-sky-500 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-cyan-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Autentikasi Cepat & Mulai Ambil Barang</span>
                        </button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-300 dark:border-slate-800"></div></div>
                    <div class="relative flex justify-center text-xs uppercase"><span class="bg-slate-100 dark:bg-slate-900 px-3 text-slate-500 font-bold">atau masuk dengan</span></div>
                </div>

                <!-- Google OAuth SSO Button -->
                <a href="{{ route('auth.google') }}" class="w-full py-3 px-4 bg-white dark:bg-slate-900/90 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700/70 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-sm flex items-center justify-center space-x-2 transition shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Single Sign-On (Google OAuth 2.0)</span>
                </a>

                <!-- Quick Demo Credentials Box -->
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800/80 text-xs text-slate-600 dark:text-slate-400 bg-slate-100/80 dark:bg-slate-950/40 p-3.5 rounded-2xl">
                    <div class="font-bold text-slate-800 dark:text-slate-300 mb-1 flex items-center">
                        <i class="fa-solid fa-circle-info text-cyan-600 dark:text-cyan-400 mr-1.5"></i> Demo Quick Login Credentials:
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[11px] font-mono mt-2">
                        <div class="p-2 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800">
                            <span class="text-cyan-600 dark:text-cyan-400 font-bold">Admin:</span><br>
                            User: <code class="text-slate-800 dark:text-slate-200 font-bold">admin</code><br>
                            Pass: <code class="text-slate-800 dark:text-slate-200">password123</code>
                        </div>
                        <div class="p-2 bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-800">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">Operator:</span><br>
                            User: <code class="text-slate-800 dark:text-slate-200 font-bold">op_mikaela</code><br>
                            Pass: <code class="text-slate-800 dark:text-slate-200">password123</code>
                        </div>
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
