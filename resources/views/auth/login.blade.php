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
@php
    $recaptchaSiteKey = config('services.recaptcha.site_key') ?: env('RECAPTCHA_SITE_KEY');
@endphp
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
            <!-- <div class="text-center lg:text-left">
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
            </div> -->

            <!-- Card Auth Form Container -->
            <div class="glass-panel rounded-3xl p-4 sm:p-8 shadow-2xl relative overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Auth Mode Switch Tabs -->
                <div class="flex bg-slate-200/80 dark:bg-slate-900/90 p-1 rounded-xl mb-6 border border-slate-300 dark:border-slate-800 text-center" role="tablist">
                    <button id="tab-classic-btn" onclick="switchAuthTab('classic')" 
                            class="flex-1 py-2.5 px-2 rounded-lg text-xs font-extrabold transition bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30 shadow flex items-center justify-center min-w-0">
                        <i class="fa-solid fa-key mr-1 shrink-0"></i> <span class="truncate">Admin / Classic</span>
                    </button>
                    <button id="tab-quick-btn" onclick="switchAuthTab('quick')" 
                            class="flex-1 py-2.5 px-2 rounded-lg text-xs font-extrabold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center min-w-0">
                        <i class="fa-solid fa-user-shield mr-1 shrink-0"></i> <span class="truncate">Pengambilan (SPV)</span>
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
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">Password</label>
                                <button type="button" onclick="openForgotModal()" class="text-[11px] font-extrabold text-cyan-600 dark:text-cyan-400 hover:underline">
                                    Lupa Username / Password?
                                </button>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password" id="password" required placeholder="••••••••"
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition shadow-sm">
                            </div>
                        </div>

                        <!-- Security Anti-Bot CAPTCHA Protection -->
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                                    <i class="fa-solid fa-shield-halved text-cyan-500 mr-1.5"></i> Verifikasi Anti-Bot (Security CAPTCHA)
                                </label>
                                @if(!$recaptchaSiteKey)
                                    <button type="button" onclick="refreshCaptcha('captcha-box-1', 'captcha-input-1')" class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 hover:underline flex items-center shrink-0">
                                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Acak Soal
                                    </button>
                                @endif
                            </div>

                            @if($recaptchaSiteKey)
                                <div class="recaptcha-responsive-container">
                                    <div class="recaptcha-responsive-wrapper">
                                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div id="captcha-box-1" class="px-3 sm:px-4 py-2.5 bg-slate-950 text-cyan-400 font-mono font-black text-xs rounded-xl border border-cyan-500/40 select-none tracking-wider shrink-0">
                                        Memuat CAPTCHA...
                                    </div>
                                    <input type="number" name="captcha_answer" id="captcha-input-1" required placeholder="Jawaban Angka"
                                           class="flex-1 min-w-0 px-3 py-2 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500">
                                </div>
                            @endif
                            @error('captcha_answer')
                                <p class="text-rose-600 dark:text-rose-400 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-500 hover:to-cyan-500 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                            <span>Masuk Sistem Admin</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Tab 2: Direct SPV Selection for Stock Retrieval (No Operator Account Needed) -->
                <div id="tab-quick" class="hidden space-y-5">
                    <form action="{{ route('user.quick-auth') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="p-3.5 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-xs text-cyan-700 dark:text-cyan-300 flex items-start space-x-2.5">
                            <i class="fa-solid fa-circle-info text-base shrink-0 mt-0.5"></i>
                            <div class="leading-relaxed">
                                <strong>Modul Pengambilan Barang:</strong> Tidak memerlukan akun operator terpisah. Cukup pilih <strong>Supervisor (SPV) Penanggung Jawab</strong> yang bertugas.
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Supervisor (SPV) Penanggung Jawab *</label>
                            <select name="supervisor_id" required class="w-full px-3.5 py-3 bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                                <option value="">-- Pilih SPV Penanggung Jawab --</option>
                                @foreach(\App\Models\User::where('role', 'spv')->orderBy('name', 'asc')->get() as $spv)
                                    <option value="{{ $spv->id }}">{{ $spv->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Security Anti-Bot CAPTCHA Protection -->
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-800 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                                    <i class="fa-solid fa-shield-halved text-cyan-500 mr-1.5"></i> Verifikasi Anti-Bot (Security CAPTCHA)
                                </label>
                                @if(!$recaptchaSiteKey)
                                    <button type="button" onclick="refreshCaptcha('captcha-box-2', 'captcha-input-2')" class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 hover:underline flex items-center shrink-0">
                                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Acak Soal
                                    </button>
                                @endif
                            </div>

                            @if($recaptchaSiteKey)
                                <div class="recaptcha-responsive-container">
                                    <div class="recaptcha-responsive-wrapper">
                                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div id="captcha-box-2" class="px-3 sm:px-4 py-2.5 bg-slate-950 text-cyan-400 font-mono font-black text-xs rounded-xl border border-cyan-500/40 select-none tracking-wider shrink-0">
                                        Memuat CAPTCHA...
                                    </div>
                                    <input type="number" name="captcha_answer" id="captcha-input-2" required placeholder="Jawaban Angka"
                                           class="flex-1 min-w-0 px-3 py-2 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500">
                                </div>
                            @endif
                            @error('captcha_answer')
                                <p class="text-rose-600 dark:text-rose-400 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-600 to-sky-600 hover:from-cyan-500 hover:to-sky-500 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-cyan-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>Masuk & Mulai Pengambilan Barang</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Modal Lupa Username / Password -->
<div id="forgot-account-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-md w-full p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-5 shadow-2xl my-auto text-left relative overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-user-lock text-cyan-500 mr-2"></i> Pemulihan Akun / Lupa Password
            </h3>
            <button onclick="closeForgotModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Masukkan alamat email terdaftar pada akun Anda untuk mencari <strong>Username</strong> dan petunjuk pemulihan kata sandi.
        </p>

        <form id="forgot-account-form" onsubmit="submitForgotAccount(event)" class="space-y-4">
            @csrf
            <div>
                <label for="forgot_email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email Terdaftar *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input type="email" id="forgot_email" required placeholder="contoh: admin@inventory.com"
                           class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition shadow-sm">
                </div>
            </div>

            <!-- Box Hasil Pencarian Akun -->
            <div id="forgot-result-box" class="hidden p-4 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-xs space-y-2.5">
                <div class="font-extrabold text-cyan-600 dark:text-cyan-400 flex items-center text-sm">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> <span id="forgot-result-title"></span>
                </div>
                <div class="space-y-1 text-slate-700 dark:text-slate-300 text-xs">
                    <div>Nama Lengkap: <strong id="forgot-result-name" class="text-slate-900 dark:text-white"></strong></div>
                    <div>Username Login: <span id="forgot-result-username" class="font-mono font-bold text-cyan-600 dark:text-cyan-400 px-2 py-0.5 bg-white dark:bg-slate-900 border border-cyan-500/30 rounded"></span></div>
                </div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-cyan-500/20 leading-relaxed">
                    <i class="fa-solid fa-envelope text-cyan-500 mr-1"></i> <span id="forgot-result-note"></span>
                </div>
                <div id="forgot-direct-link-container" class="pt-1">
                    <a id="forgot-direct-link" href="" class="inline-flex items-center justify-center w-full py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl font-extrabold text-xs shadow transition">
                        <i class="fa-solid fa-paper-plane mr-1.5"></i> Buka Link Reset Password
                    </a>
                </div>
            </div>

            <!-- Box Pesan Error Email -->
            <div id="forgot-error-box" class="hidden p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-xs font-semibold text-rose-600 dark:text-rose-400 flex items-center">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-base"></i> <span id="forgot-error-text"></span>
            </div>

            <div class="flex space-x-2 pt-2">
                <button type="button" onclick="closeForgotModal()" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">Batal</button>
                <button type="submit" id="btn-submit-forgot" class="flex-1 py-2.5 bg-gradient-to-r from-cyan-600 to-sky-600 hover:from-cyan-500 hover:to-sky-500 text-white rounded-xl text-xs font-bold transition shadow flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass mr-1.5"></i> Cek Akun Saya
                </button>
            </div>
        </form>

        <div class="p-3.5 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
            <div class="font-bold text-slate-700 dark:text-slate-300 flex items-center">
                <i class="fa-solid fa-headset text-cyan-500 mr-1.5"></i> Bantuan Tim IT / Superadmin:
            </div>
            <p class="leading-relaxed">Jika Anda perlu melakukan reset password instan, hubungi Administrator Utama Gudang atau Supervisor penanggung jawab Anda.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        function openForgotModal() {
            document.getElementById('forgot-result-box').classList.add('hidden');
            document.getElementById('forgot-error-box').classList.add('hidden');
            document.getElementById('forgot_email').value = '';
            document.getElementById('forgot-account-modal').classList.remove('hidden');
        }

        function closeForgotModal() {
            document.getElementById('forgot-account-modal').classList.add('hidden');
        }

        async function submitForgotAccount(e) {
            e.preventDefault();
            const email = document.getElementById('forgot_email').value;
            const btn = document.getElementById('btn-submit-forgot');
            const resultBox = document.getElementById('forgot-result-box');
            const errorBox = document.getElementById('forgot-error-box');

            resultBox.classList.add('hidden');
            errorBox.classList.add('hidden');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Memeriksa...';

            try {
                const response = await fetch("{{ route('forgot-password') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById('forgot-result-title').innerText = data.message;
                    document.getElementById('forgot-result-name').innerText = data.name;
                    document.getElementById('forgot-result-username').innerText = data.username;
                    document.getElementById('forgot-result-note').innerText = data.note;
                    if (data.reset_link) {
                        document.getElementById('forgot-direct-link').href = data.reset_link;
                    }
                    resultBox.classList.remove('hidden');
                } else {
                    document.getElementById('forgot-error-text').innerText = data.message || 'Alamat email tidak ditemukan dalam database.';
                    errorBox.classList.remove('hidden');
                }
            } catch(err) {
                document.getElementById('forgot-error-text').innerText = 'Terjadi kesalahan jaringan, silakan coba lagi.';
                errorBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-magnifying-glass mr-1.5"></i> Cek Akun Saya';
            }
        }

        async function refreshCaptcha(boxId, inputId) {
            const box = document.getElementById(boxId);
            const input = document.getElementById(inputId);
            if (!box) return;
            box.innerText = "Memuat...";
            try {
                const res = await fetch("{{ url('/captcha/refresh') }}");
                const data = await res.json();
                if (data.success) {
                    box.innerText = data.question;
                    if (input) input.value = '';
                }
            } catch(e) {
                box.innerText = "Captcha Error";
            }
        }
        @if(!$recaptchaSiteKey)
        document.addEventListener('DOMContentLoaded', function() {
            refreshCaptcha('captcha-box-1', 'captcha-input-1');
            refreshCaptcha('captcha-box-2', 'captcha-input-2');
        });
        @endif
    </script>
@endpush
