<!DOCTYPE html>
<html lang="id" class="h-full transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory Control & Stock Retrieval')</title>
    
    <!-- Browser Favicon / Logo Peramban -->
    <link rel="icon" type="image/png" href="{{ asset('images/LogoMieGacoan.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LogoMieGacoan.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/LogoMieGacoan.png') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Application Custom External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body class="h-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col antialiased selection:bg-cyan-500 selection:text-white transition-colors duration-300 min-h-screen">

    <!-- 1. FIXED TOP NAVIGATION HEADER (Warna Terang Siang / Gelap Malam) -->
    @auth
    <nav class="fixed top-0 left-0 right-0 z-50 h-16 border-b border-slate-200/80 dark:border-slate-800/80 px-3 sm:px-6 py-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto h-full flex items-center justify-between gap-2">
            <!-- Brand Logo & Mobile Sidebar Toggle -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                @if(Auth::user()->isAdmin() && !request()->routeIs('stock.retrieval'))
                    <button onclick="toggleSidebar()" type="button" class="lg:hidden p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 active:scale-95 transition border border-slate-200 dark:border-slate-700" title="Buka Sidebar Navigasi">
                        <i class="fa-solid fa-bars-staggered text-base"></i>
                    </button>
                @endif

                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white dark:bg-slate-900 p-1 flex items-center justify-center shadow-md shadow-cyan-500/20 shrink-0 border border-slate-200 dark:border-slate-800">
                    <img src="{{ asset('images/LogoMieGacoan.png') }}" alt="Logo Mie Gacoan" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="font-heading font-black text-sm sm:text-lg tracking-wide text-slate-900 dark:text-white block leading-none">INVENTORY<span class="text-sky-600 dark:text-sky-400">CONTROL</span></span>
                    <span class="text-[9px] sm:text-[10px] tracking-wider text-pink-600 dark:text-pink-400 font-extrabold uppercase block mt-0.5">Stock Management System</span>
                </div>
            </div>

            <!-- Top Header Right Controls (Mobile-Optimized Dynamic Actions) -->
            <div class="flex items-center space-x-1.5 sm:space-x-3">
                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" title="Admin Panel Dashboard" class="px-2.5 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('admin.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:text-pink-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <i class="fa-solid fa-gauge-high text-xs sm:text-sm"></i>
                        <span class="hidden sm:inline">Admin Panel</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" title="Login ke Admin Panel" class="px-2.5 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 text-slate-700 dark:text-slate-300 hover:text-sky-600 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-lock text-xs sm:text-sm text-amber-500"></i>
                        <span class="hidden sm:inline">Admin Panel</span>
                    </a>
                @endif

                <a href="{{ route('stock.retrieval') }}" title="Modul Ambil Barang" class="px-2.5 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('stock.retrieval') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:text-pink-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                    <span class="hidden sm:inline">Ambil Barang</span>
                </a>

                <!-- Theme Switcher Button -->
                <button onclick="toggleTheme()" type="button" title="Ubah Mode Siang / Malam"
                        class="p-2 sm:px-2.5 sm:py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/90 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition flex items-center space-x-1.5 border border-slate-300/80 dark:border-slate-700/80">
                    <i id="theme-toggle-icon" class="fa-solid fa-moon text-pink-500 dark:text-pink-400 text-xs sm:text-sm"></i>
                    <span id="theme-toggle-label" class="text-xs font-semibold hidden md:inline">Mode: Malam</span>
                </button>

                <!-- User Profile Avatar & Header Logout -->
                <div class="flex items-center space-x-2 sm:space-x-3 pl-1.5 sm:pl-3 border-l border-slate-200 dark:border-slate-800">
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0284c7&color=fff' }}" 
                         alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full border border-pink-500/40 object-cover">
                    
                    <div class="hidden lg:block text-left">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[110px]">{{ Auth::user()->name }}</div>
                        <div class="text-[9px] text-sky-600 dark:text-sky-400 uppercase tracking-wider font-extrabold">{{ Auth::user()->role }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" title="Logout dari Sistem" class="w-8 h-8 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white transition flex items-center justify-center border border-rose-500/20 shadow-sm active:scale-95">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- 2. MAIN SYSTEM LAYOUT CONTAINER -->
    <div class="pt-20 pb-16 min-h-screen flex flex-col transition-colors duration-300">
        <!-- Sidebar Backdrop for Mobile -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>

        <!-- Layout Body -->
        <div class="flex-1 w-full max-w-7xl mx-auto px-4 py-6 relative">
            @auth
                @if(Auth::user()->isAdmin() && !request()->routeIs('stock.retrieval'))
                    <div class="relative w-full">
                        
                        <!-- 2. FIXED SIDEBAR NAVIGATION (Dengan Logout & Mobile Slide Support) -->
                        <aside id="admin-sidebar" class="fixed top-16 left-0 lg:left-auto lg:top-20 z-40 w-72 lg:w-64 h-[calc(100vh-4rem)] lg:h-[calc(100vh-8.5rem)] overflow-y-auto p-5 border-r lg:border border-slate-200 dark:border-slate-800 rounded-r-3xl lg:rounded-3xl space-y-6 hidden lg:block bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-2xl transition-all duration-300">
                            <!-- Sidebar Section Header -->
                            <div class="px-2 flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] font-extrabold uppercase tracking-wider text-pink-500 mb-0.5">Navigasi Utama</div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white flex items-center">
                                        <i class="fa-solid fa-gauge-high text-sky-600 dark:text-sky-400 mr-2"></i> Admin Panel
                                    </div>
                                </div>
                                <button onclick="toggleSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-rose-500 transition">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>

                            <!-- Main Navigation Links -->
                            <nav class="space-y-1.5">
                                <!-- 1. Dashboard -->
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-chart-pie text-sm"></i>
                                        <span>Dashboard Kontrol</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-70"></i>
                                </a>

                                <!-- 2. Master Data Inventaris -->
                                <a href="{{ route('admin.master-data') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.master-data') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                                        <span>Master Data</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-70"></i>
                                </a>

                                <!-- 2. Input / Restock Stok -->
                                <a href="{{ route('admin.stock.input') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.stock.input') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-boxes-packing text-sm"></i>
                                        <span>Input / Restock Stok</span>
                                    </div>
                                    <i class="fa-solid fa-plus text-[10px] opacity-70"></i>
                                </a>

                                <!-- 3. Pengajuan Barang -->
                                <a href="{{ route('admin.requisitions.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.requisitions.index') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-clipboard-list text-sm"></i>
                                        <span>Pengajuan Barang</span>
                                    </div>
                                    @if(($global_pending_req_count ?? 0) > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white animate-pulse">
                                            {{ $global_pending_req_count }}
                                        </span>
                                    @endif
                                </a>

                                <!-- 4. Deteksi Barang Menipis -->
                                <a href="{{ route('admin.low-stock') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.low-stock') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-triangle-exclamation text-sm text-pink-400"></i>
                                        <span>Deteksi Stok Menipis</span>
                                    </div>
                                    @if(($global_low_stock_count ?? 0) > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500 text-white animate-pulse">
                                            {{ $global_low_stock_count }}
                                        </span>
                                    @endif
                                </a>

                                <!-- 5. Manajemen User -->
                                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.users.index') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-users-gear text-sm"></i>
                                        <span>Manajemen User</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-70"></i>
                                </a>

                                <!-- 6. Pindai / Ambil Barang -->
                                <a href="{{ route('stock.retrieval') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition {{ request()->routeIs('stock.retrieval') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-qrcode text-sm"></i>
                                        <span>Pindai / Ambil Barang</span>
                                    </div>
                                    <i class="fa-solid fa-barcode text-[10px] opacity-70"></i>
                                </a>
                            </nav>

                            <!-- Low Stock Warning Box in Sidebar -->
                            @if(($global_low_stock_count ?? 0) > 0)
                                <div class="p-3.5 rounded-2xl bg-pink-500/10 border border-pink-500/20 space-y-2">
                                    <div class="flex items-center justify-between text-xs font-bold text-rose-600 dark:text-rose-400">
                                        <span><i class="fa-solid fa-bell mr-1"></i> Perhatian Stok!</span>
                                        <span class="px-1.5 py-0.5 rounded bg-rose-500 text-white text-[10px]">{{ $global_low_stock_count }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-snug">
                                        Terdapat <strong class="text-rose-600 dark:text-rose-400">{{ $global_low_stock_count }} item</strong> stok menipis.
                                    </p>
                                    <a href="{{ route('admin.low-stock') }}" class="inline-block w-full text-center px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-bold rounded-xl transition shadow-md shadow-rose-600/20">
                                        Buka Deteksi Stok
                                    </a>
                                </div>
                            @endif

                            <!-- Sidebar User Profile Card & Dedicated Logout Button -->
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                                <div class="flex items-center space-x-3 px-1">
                                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0284c7&color=fff' }}" 
                                         alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-xl border border-sky-500/40 object-cover shadow-sm">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                                        <div class="text-[10px] text-sky-600 dark:text-sky-400 font-extrabold uppercase tracking-wider">{{ Auth::user()->role }}</div>
                                    </div>
                                </div>

                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-3.5 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-500/20 font-bold text-xs transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm group">
                                        <i class="fa-solid fa-right-from-bracket text-xs transition-transform group-hover:-translate-x-0.5"></i>
                                        <span>Keluar / Logout System</span>
                                    </button>
                                </form>
                            </div>

                            <!-- System Info -->
                            <div class="pt-2 text-[10px] text-slate-400 text-center">
                                System Version 1.2 LTS &bull; MySQL Active
                            </div>
                        </aside>

                        <!-- Main Content Area (Offset by 17.5rem / 280px on desktop) -->
                        <main class="w-full lg:pl-[17.5rem] min-w-0">
                            @yield('content')
                        </main>
                    </div>
                @else
                    <main class="flex-1 w-full">
                        @yield('content')
                    </main>
                @endif
            @else
                <main class="flex-1 w-full">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>

    <!-- 3. FIXED FOOTER AT THE BOTTOM (Selalu Fixed di Bawah Layar) -->
    <footer class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 dark:border-slate-800/80 py-3 text-center text-xs text-slate-500 dark:text-slate-400 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md transition-colors duration-300 shadow-md">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between">
            <div>&copy; {{ date('Y') }} Inventory Control & Stock Retrieval System</div>
            <div class="mt-2 sm:mt-0 flex items-center space-x-3">
                <!-- <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 mr-1.5 animate-pulse"></span> MySQL Port 3307 Connected
                </span> -->
                <!-- <span>Laravel 11 LTS</span> -->
            </div>
        </div>
    </footer>
    <!-- Global Core Libraries: jQuery, Select2 & SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Suppress native DataTables browser alerts globally
        if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
            $.fn.dataTable.ext.errMode = 'none';
        }
        $(document).on('error.dt', function(e, settings, techNote, message) {
            console.warn('DataTables Handled Note:', message);
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) {
                sidebar.classList.toggle('hidden');
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        // Global Glassmorphism SweetAlert2 Dialog Helpers
        window.showAlert = function(title, message, icon = 'info') {
            Swal.fire({
                title: title,
                html: message,
                icon: icon,
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm'
                }
            });
        };

        window.showConfirm = function(title, message, callback, confirmText = 'Ya, Lanjutkan') {
            Swal.fire({
                title: title,
                html: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        };
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#0284c7',
                customClass: {
                    popup: 'swal2-popup font-sans'
                }
            });
        });
    </script>
    @endif

    @if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'Notifikasi Sistem',
                text: @json(session('status')),
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#0284c7',
                customClass: {
                    popup: 'swal2-popup font-sans'
                }
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: @json(session('error')),
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#e11d48',
                customClass: {
                    popup: 'swal2-popup font-sans'
                }
            });
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan Sistem',
                text: @json(session('warning')),
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b',
                customClass: {
                    popup: 'swal2-popup font-sans'
                }
            });
        });
    </script>
    @endif

    @if(isset($errors) && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorList = @json($errors->all());
            let htmlMsg = '<div style="text-align: left; font-size: 13px; line-height: 1.6;"><ul style="list-style-type: disc; padding-left: 20px;">';
            errorList.forEach(function(err) {
                htmlMsg += '<li>' + err + '</li>';
            });
            htmlMsg += '</ul></div>';

            Swal.fire({
                icon: 'error',
                title: 'Gagal Memproses Data',
                html: htmlMsg,
                confirmButtonText: 'Tutup & Perbaiki',
                confirmButtonColor: '#e11d48',
                customClass: {
                    popup: 'swal2-popup font-sans'
                }
            });
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>