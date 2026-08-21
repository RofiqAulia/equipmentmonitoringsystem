<!DOCTYPE html>
<html lang="id" class="h-full transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory Control & Stock Retrieval')</title>
    
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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="h-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col antialiased selection:bg-cyan-500 selection:text-white transition-colors duration-300 min-h-screen">

    <!-- 1. FIXED TOP NAVIGATION HEADER (Warna Terang Siang / Gelap Malam) -->
    @auth
    <nav class="fixed top-0 left-0 right-0 z-50 h-16 border-b border-slate-200 dark:border-slate-800/80 px-4 py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md transition-colors duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto h-full flex items-center justify-between">
            <!-- Brand Logo & Mobile Sidebar Toggle -->
            <div class="flex items-center space-x-3">
                @if(Auth::user()->isAdmin())
                    <button onclick="toggleSidebar()" type="button" class="lg:hidden p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 transition border border-slate-200 dark:border-slate-700">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                @endif

                <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center shadow-lg shadow-sky-600/20 shrink-0">
                    <i class="fa-solid fa-boxes-stacked text-white text-lg"></i>
                </div>
                <div>
                    <span class="font-heading font-bold text-base sm:text-lg tracking-wide text-slate-900 dark:text-white block leading-none">INVENTORY<span class="text-sky-600 dark:text-sky-400">CONTROL</span></span>
                    <span class="text-[10px] tracking-wider text-pink-600 dark:text-pink-400 font-bold uppercase">Stock Management System</span>
                </div>
            </div>

            <!-- Top Header Right Controls -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ route('stock.retrieval') }}" class="px-3 py-1.5 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('stock.retrieval') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:text-pink-600 dark:hover:text-pink-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-qrcode mr-1.5"></i> Ambil Barang
                </a>

                <!-- Theme Switcher Button -->
                <button onclick="toggleTheme()" type="button" title="Ubah Tampilan Siang / Malam"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/90 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition flex items-center space-x-1.5 border border-slate-300 dark:border-slate-700">
                    <i id="theme-toggle-icon" class="fa-solid fa-moon text-pink-500 dark:text-pink-400 text-sm"></i>
                    <span id="theme-toggle-label" class="text-xs font-semibold hidden sm:inline">Mode: Malam</span>
                </button>

                <!-- SPV Status Badge -->
                <div id="active-spv-badge" class="hidden md:flex items-center bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700/60 rounded-full px-3 py-1 text-xs">
                    <span class="text-slate-500 dark:text-slate-400 mr-1.5"><i class="fa-solid fa-user-shield text-indigo-500 mr-1"></i>SPV Aktif:</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ Auth::user()->supervisor ? Auth::user()->supervisor->name : Auth::user()->name }}
                    </span>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-2 sm:space-x-3 pl-2 sm:pl-3 border-l border-slate-200 dark:border-slate-800">
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0284c7&color=fff' }}" 
                         alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full border border-pink-500/40">
                    <div class="hidden lg:block text-left">
                        <div class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-sky-600 dark:text-sky-400 uppercase tracking-wider font-bold">{{ Auth::user()->role }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Logout" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800/80 hover:bg-rose-500/20 hover:text-rose-500 dark:hover:text-rose-400 text-slate-600 dark:text-slate-400 transition flex items-center justify-center border border-slate-300 dark:border-slate-700/50">
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

        <!-- Flash Alert Messages -->
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center justify-between transition-opacity duration-200 shadow-sm">
                    <div class="flex items-center"><i class="fa-solid fa-circle-check mr-2 text-lg"></i> {{ session('success') }}</div>
                    <button onclick="dismissAlert(this)" class="text-emerald-700 dark:text-emerald-400 hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 text-sm rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 flex items-center justify-between transition-opacity duration-200 shadow-sm">
                    <div class="flex items-center"><i class="fa-solid fa-triangle-exclamation mr-2 text-lg"></i> {{ session('error') }}</div>
                    <button onclick="dismissAlert(this)" class="text-rose-700 dark:text-rose-400 hover:opacity-75"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        <!-- Layout Body -->
        <div class="flex-1 w-full max-w-7xl mx-auto px-4 py-6 relative">
            @auth
                @if(Auth::user()->isAdmin())
                    <div class="relative w-full">
                        
                        <!-- 2. FIXED SIDEBAR NAVIGATION (Tidak Menutup Footer & Pas dengan Tema) -->
                        <aside id="admin-sidebar" class="fixed top-20 left-4 sm:left-6 lg:left-auto z-40 w-72 lg:w-64 h-[calc(100vh-8.5rem)] overflow-y-auto p-5 border border-slate-200 dark:border-slate-800 rounded-3xl space-y-6 hidden lg:block bg-white dark:bg-slate-900 backdrop-blur-md shadow-xl dark:shadow-2xl transition-all duration-300">
                            <!-- Sidebar Section Header -->
                            <div class="px-2 flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-pink-500 mb-0.5">Navigation Menu</div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white flex items-center">
                                        <i class="fa-solid fa-gauge-high text-sky-600 dark:text-sky-400 mr-2"></i> Admin Panel
                                    </div>
                                </div>
                                <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>

                            <!-- Main Navigation Links (Solid Color System: 1. Biru, 2. Pink, 3. Putih) -->
                            <nav class="space-y-1.5">
                                <!-- 1. Dashboard -->
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-chart-pie text-sm"></i>
                                        <span>Dashboard Kontrol</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-[10px] opacity-70"></i>
                                </a>

                                <!-- 2. Input / Restock Stok -->
                                <a href="{{ route('admin.stock.input') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition {{ request()->routeIs('admin.stock.input') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-boxes-packing text-sm"></i>
                                        <span>Input / Restock Stok</span>
                                    </div>
                                    <i class="fa-solid fa-plus text-[10px] opacity-70"></i>
                                </a>

                                <!-- 3. Pengajuan Barang -->
                                <a href="{{ route('admin.requisitions.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition {{ request()->routeIs('admin.requisitions.index') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-clipboard-list text-sm"></i>
                                        <span>Pengajuan Barang</span>
                                    </div>
                                    @if(($global_pending_req_count ?? 0) > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white animate-pulse">
                                            {{ $global_pending_req_count }}
                                        </span>
                                    @endif
                                </a>

                                <!-- 4. Deteksi Barang Menipis -->
                                <a href="{{ route('admin.low-stock') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition {{ request()->routeIs('admin.low-stock') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-triangle-exclamation text-sm text-pink-400"></i>
                                        <span>Deteksi Stok Menipis</span>
                                    </div>
                                    @if(($global_low_stock_count ?? 0) > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">
                                            {{ $global_low_stock_count }}
                                        </span>
                                    @endif
                                </a>

                                <!-- 5. Pindai / Ambil Barang -->
                                <a href="{{ route('stock.retrieval') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold transition {{ request()->routeIs('stock.retrieval') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-pink-500' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-qrcode text-sm"></i>
                                        <span>Pindai / Ambil Barang</span>
                                    </div>
                                    <i class="fa-solid fa-barcode text-[10px] opacity-70"></i>
                                </a>
                            </nav>

                            <!-- Low Stock Warning Box in Sidebar -->
                            @if(($global_low_stock_count ?? 0) > 0)
                                <div class="p-4 rounded-2xl bg-pink-500/10 border border-pink-500/20 space-y-2">
                                    <div class="flex items-center justify-between text-xs font-bold text-rose-600 dark:text-rose-400">
                                        <span><i class="fa-solid fa-bell mr-1"></i> Perhatian Stok!</span>
                                        <span class="px-1.5 py-0.5 rounded bg-rose-500 text-white text-[10px]">{{ $global_low_stock_count }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-snug">
                                        Terdapat <strong class="text-rose-600 dark:text-rose-400">{{ $global_low_stock_count }} item</strong> dengan stok mendekati/di bawah batas minimum.
                                    </p>
                                    <a href="{{ route('admin.low-stock') }}" class="inline-block w-full text-center px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-bold rounded-xl transition shadow-md shadow-rose-600/20">
                                        Buka Deteksi Stok
                                    </a>
                                </div>
                            @else
                                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center space-x-3">
                                    <i class="fa-solid fa-shield-check text-emerald-500 text-lg"></i>
                                    <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                                        Semua stok gudang dalam kondisi aman.
                                    </div>
                                </div>
                            @endif

                            <!-- System Info & Footer -->
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-[10px] text-slate-400 space-y-1">
                                <div>Port 3307 &bull; MySQL Active</div>
                                <div>System Version 1.2 LTS</div>
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
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 mr-1.5 animate-pulse"></span> MySQL Port 3307 Connected
                </span>
                <span>Laravel 11 LTS</span>
            </div>
        </div>
    </footer>
    <!-- SweetAlert2 CDN & External Application JS -->
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
                timer: 3500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
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
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm'
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
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm'
                }
            });
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>