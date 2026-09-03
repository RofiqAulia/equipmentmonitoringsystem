@extends('layouts.app')

@section('title', 'Admin Dashboard - DataTables Inventory Control System')

@push('styles')
    <!-- DataTables Core, Buttons, & RowGroup CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.dataTables.min.css">
    <style>
        /* Custom Dynamic & Clean Glassmorphism DataTables Theme */
        .dataTables_wrapper {
            font-family: inherit;
            color: inherit;
            width: 100%;
        }
        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
        }
        .dataTables_wrapper .dataTables_length select {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.35rem 2rem 0.35rem 0.75rem;
            color: var(--text-main);
            font-size: 0.75rem;
            outline: none;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
        }
        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.45rem 0.85rem;
            color: var(--text-main);
            font-size: 0.75rem;
            outline: none;
            min-width: 220px;
            transition: all 0.2s ease;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.2);
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 0.75rem;
            color: var(--text-muted);
            padding-top: 0.75rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.75rem;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.6rem !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.35rem 0.75rem !important;
            margin: 0 0.1rem !important;
            color: var(--text-muted) !important;
            background: var(--bg-card) !important;
            cursor: pointer;
            transition: all 0.2s ease !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0284c7 !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            font-weight: 700 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgba(6, 182, 212, 0.15) !important;
            color: #06b6d4 !important;
            border-color: rgba(6, 182, 212, 0.4) !important;
        }
        
        /* Enforce Table Layout & Clean Wrap Text Formatting */
        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        table.dataTable td, table.dataTable th {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            vertical-align: middle;
        }
        table.dataTable tbody tr {
            background-color: transparent !important;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid var(--border-color) !important;
        }

        /* Sorting Indicators (ASC & DESC) */
        table.dataTable thead th.sorting,
        table.dataTable thead th.sorting_asc,
        table.dataTable thead th.sorting_desc {
            cursor: pointer;
            position: relative;
            padding-right: 1.5rem !important;
            user-select: none;
        }
        table.dataTable thead th.sorting:after {
            content: " ↕";
            opacity: 0.35;
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }
        table.dataTable thead th.sorting_asc:after {
            content: " ▲ ASC";
            color: #06b6d4;
            font-weight: 800;
            font-size: 0.65rem;
            margin-left: 0.25rem;
        }
        table.dataTable thead th.sorting_desc:after {
            content: " ▼ DESC";
            color: #06b6d4;
            font-weight: 800;
            font-size: 0.65rem;
            margin-left: 0.25rem;
        }

        /* DataTables RowGroup Header Styling */
        tr.dtrg-group th {
            background: var(--bg-card) !important;
            color: #06b6d4 !important;
            font-weight: 800 !important;
            font-size: 0.75rem !important;
            padding: 0.6rem 1rem !important;
            border-top: 2px solid rgba(6, 182, 212, 0.4) !important;
            border-bottom: 1px solid var(--border-color) !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .dt-buttons .dt-button {
            background: rgba(6, 182, 212, 0.1) !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            color: #06b6d4 !important;
            border-radius: 0.75rem !important;
            padding: 0.45rem 0.85rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            transition: all 0.2s ease !important;
        }
        .dt-buttons .dt-button:hover {
            background: #06b6d4 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3) !important;
        }
    </style>
@endpush

@section('content')
<div class="space-y-8">
    
    <!-- Dashboard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel p-6 rounded-3xl">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 text-xs font-bold rounded-full border border-cyan-500/30 uppercase tracking-wider">
                    <i class="fa-solid fa-table-cells mr-1"></i> DataTables Admin Panel
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">System Monitoring & DataTables Controls</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Dashboard Kontrol Gudang</h1>
        </div>

        <div class="flex items-center space-x-3">
            <button onclick="refreshTables()" class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition flex items-center border border-slate-300 dark:border-slate-700">
                <i class="fa-solid fa-rotate mr-2"></i> Reload Data
            </button>
            <a href="{{ route('stock.retrieval') }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/20 transition flex items-center">
                <i class="fa-solid fa-qrcode mr-2"></i> Pindai / Ambil Barang
            </a>
        </div>
    </div>

    <!-- Metric Cards Grid -->
    <div class="space-y-3">
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center">
            <i class="fa-solid fa-chart-line text-cyan-500 mr-2"></i> System Health Metrics
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Pengguna</div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $totalUsers ?? $system_health['total_users'] ?? 0 }}</div>
                    <div class="text-[10px] text-cyan-600 dark:text-cyan-400 font-bold mt-0.5">{{ $adminsCount ?? $system_health['total_admins'] ?? 0 }} Admin / {{ $spvsCount ?? $supervisorsCount ?? $system_health['total_spvs'] ?? 0 }} SPV</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Total Items (SKU) -->
            <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Barang (SKU)</div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $totalItems ?? $system_health['total_items'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">Item terdaftar di rak</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>

            <!-- Total Retrievals Today -->
            <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pengambilan Hari Ini</div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $todayRetrievalsCount ?? $system_health['retrievals_today'] ?? 0 }}</div>
                    <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">Total Unit: {{ $todayRetrievalsUnits ?? $system_health['total_qty_picked_today'] ?? 0 }} barang</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
            </div>

            <!-- Port Connection Indicator -->
            <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Status Koneksi Port</div>
                    <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">MySQL 3307</div>
                    <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">Active & Synced</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Analytics & Charts Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center">
                <i class="fa-solid fa-chart-pie text-cyan-500 mr-2"></i> Visualisasi Analytics & Grafik Gudang
            </h2>
            <span class="text-[11px] font-semibold text-slate-400">Real-time Data Chart</span>
        </div>

        <!-- Dynamic Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Stock Status Distribution (Donut / Pie Chart) -->
            <div class="glass-panel p-6 rounded-3xl space-y-4 flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center">
                            <i class="fa-solid fa-chart-pie text-emerald-500 mr-2"></i> Diagram Ratio Status Stok Barang
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Proporsi item Aman, Tipis, dan Habis dalam gudang</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button type="button" onclick="toggleStockChartType()" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[11px] rounded-lg border border-slate-300 dark:border-slate-700 transition flex items-center">
                            <i class="fa-solid fa-rotate mr-1 text-xs"></i> Switch Pie/Doughnut
                        </button>
                    </div>
                </div>

                <div class="relative flex items-center justify-center min-h-[240px] max-h-[280px]">
                    <canvas id="stockStatusChart"></canvas>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200 dark:border-slate-800 text-center">
                    <div class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                        <div class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">Stok Aman</div>
                        <div class="text-sm font-black text-slate-900 dark:text-white">{{ $inStockCount }} Item</div>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <div class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase">Stok Tipis</div>
                        <div class="text-sm font-black text-slate-900 dark:text-white">{{ $lowStockCount }} Item</div>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-500/10 border border-rose-500/20">
                        <div class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase">Stok Habis</div>
                        <div class="text-sm font-black text-slate-900 dark:text-white">{{ $outOfStockCount }} Item</div>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Retrieval Types Categorization (Bar Chart / Pie Chart) -->
            <div class="glass-panel p-6 rounded-3xl space-y-4 flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center">
                            <i class="fa-solid fa-chart-column text-cyan-500 mr-2"></i> Diagram Jenis Pengambilan Barang
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">4 Kategori utama + 1 Custom (Lainnya)</p>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button type="button" onclick="toggleRetrievalChartType()" class="px-2.5 py-1 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 font-bold text-[11px] rounded-lg border border-cyan-500/30 transition flex items-center">
                            <i class="fa-solid fa-chart-simple mr-1 text-xs"></i> <span id="retrievalChartToggleLabel">Switch to Pie</span>
                        </button>
                    </div>
                </div>

                <div class="relative flex items-center justify-center min-h-[240px] max-h-[280px]">
                    <canvas id="retrievalCategoryChart"></canvas>
                </div>

                <!-- Category Breakdown Stats -->
                <div class="grid grid-cols-5 gap-1.5 pt-2 border-t border-slate-200 dark:border-slate-800 text-center">
                    <div class="p-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/20">
                        <div class="text-[9px] font-bold text-cyan-600 dark:text-cyan-400 truncate">Rutin</div>
                        <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">{{ $retrievalCategoryQty['rutin'] ?? 0 }} <span class="text-[9px] font-normal text-slate-400">Unit</span></div>
                    </div>
                    <div class="p-1.5 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                        <div class="text-[9px] font-bold text-yellow-600 dark:text-yellow-400 truncate">Repair</div>
                        <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">{{ $retrievalCategoryQty['maintenance'] ?? 0 }} <span class="text-[9px] font-normal text-slate-400">Unit</span></div>
                    </div>
                    <div class="p-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20">
                        <div class="text-[9px] font-bold text-blue-600 dark:text-blue-400 truncate">Refill</div>
                        <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">{{ $retrievalCategoryQty['refill'] ?? 0 }} <span class="text-[9px] font-normal text-slate-400">Unit</span></div>
                    </div>
                    <div class="p-1.5 rounded-lg bg-red-500/10 border border-red-500/20">
                        <div class="text-[9px] font-bold text-red-600 dark:text-red-400 truncate">Rusak</div>
                        <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">{{ $retrievalCategoryQty['rusak'] ?? 0 }} <span class="text-[9px] font-normal text-slate-400">Unit</span></div>
                    </div>
                    <div class="p-1.5 rounded-lg bg-purple-500/10 border border-purple-500/20">
                        <div class="text-[9px] font-bold text-purple-600 dark:text-purple-400 truncate">Lainnya</div>
                        <div class="text-xs font-black text-slate-900 dark:text-white mt-0.5">{{ $retrievalCategoryQty['lainnya'] ?? 0 }} <span class="text-[9px] font-normal text-slate-400">Unit</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Table 1: Activity Log Table -->
    @include('admin.partials.activity_log')
</div>

<!-- Modal Terbitkan QR Code untuk Operator -->
@include('admin.partials.qr_modal')
@endsection

@push('scripts')
    <!-- Chart.js CDN for Interactive Dashboard Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- DataTables Core + Export Extensions + RowGroup -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js"></script>

    <script>
        $(document).ready(function() {
            if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
                $.fn.dataTable.ext.errMode = 'none';
            }

            var activityExportOptions = {
                columns: [0, 1, 2, 3, 4, 5],
                format: {
                    body: function(data, row, column, node) {
                        if (column === 0) {
                            return (row + 1).toString();
                        }
                        if (data === null || data === undefined) return '';
                        var str = String(data);
                        return str.replace(/\[DATE:[^\]]*\]/g, '')
                                  .replace(/\[ITEM:[^\]]*\]/g, '')
                                  .replace(/<[^>]*>/g, '')
                                  .replace(/\s+/g, ' ')
                                  .trim();
                    },
                    footer: function(data, row, column, node) {
                        if (column === 0) {
                            return '';
                        }
                        if (column === 1) {
                            return 'Total Barang:';
                        }
                        if (column === 2) {
                            if (data === null || data === undefined) return '';
                            var str = String(data);
                            return str.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
                        }
                        return '';
                    }
                }
            };

            // 1. Activity Log Table with Dynamic Row Grouping and Export Buttons
            var activityTable = $('#activityLogTable').DataTable({
                dom: '<"flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-4 p-3 bg-slate-50/70 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-800"<"flex flex-wrap items-center gap-3"lB><"w-full xl:w-auto"f>>rt<"flex flex-col sm:flex-row items-center justify-between gap-4 mt-4 p-2"ip>',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa-solid fa-file-csv mr-1.5"></i> Ekspor CSV',
                        title: 'Activity_Log_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: activityExportOptions,
                        footer: true
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa-solid fa-file-excel mr-1.5"></i> Ekspor Excel',
                        title: 'Activity_Log_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: activityExportOptions,
                        footer: true
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print mr-1.5"></i> Cetak Tabel',
                        title: '',
                        footer: true,
                        messageTop: function() {
                            var start = $('#start_date').val();
                            var end = $('#end_date').val();
                            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            
                            function fmtDate(dStr) {
                                if (!dStr) return '';
                                var p = dStr.split('-');
                                if (p.length !== 3) return dStr;
                                var idx = parseInt(p[1], 10) - 1;
                                return p[2] + ' ' + (months[idx] || p[1]) + ' ' + p[0];
                            }

                            var dateText = '';
                            if (start && end) {
                                dateText = (start === end) ? fmtDate(start) : (fmtDate(start) + ' - ' + fmtDate(end));
                            } else if (start) {
                                dateText = 'Dari ' + fmtDate(start);
                            } else if (end) {
                                dateText = 'Sampai ' + fmtDate(end);
                            } else {
                                dateText = 'Semua Tanggal';
                            }

                            var logoUrl = "{{ asset('images/LogoMieGacoan.png') }}";
                            return '<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">' +
                                   '<div><img src="' + logoUrl + '" alt="Logo" style="max-height: 70px; width: auto; object-fit: contain;"></div>' +
                                   '<div style="text-align: right; font-weight: 700; font-size: 12px; color: #0f172a;">' +
                                   'Laporan Activity Log Transaksi Pengambilan<br>' +
                                   'Malang, ' + dateText +
                                   '</div></div>';
                        },
                        exportOptions: activityExportOptions
                    }
                ],
                pageLength: -1,
                lengthMenu: [[-1, 10, 25, 50, 100], ["Tampilkan Semua", 10, 25, 50, 100]],
                order: [[3, 'desc']], // Default Sort by Waktu Descending
                columnDefs: [
                    { targets: 0, orderable: false },
                    { orderable: true, targets: '_all' }
                ],
                rowGroup: {
                    enable: false,
                    className: 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 font-bold text-xs px-4 py-2 border-y border-cyan-500/20'
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    
                    var intVal = function(i) {
                        if (typeof i === 'number') return i;
                        if (typeof i === 'string') {
                            var clean = i.replace(/<[^>]*>/g, '').replace(/[^0-9-]/g, '');
                            return clean ? parseInt(clean, 10) : 0;
                        }
                        return 0;
                    };

                    var total = api.column(2, { search: 'applied' }).data().reduce(function(a, b) {
                        return Math.abs(intVal(a)) + Math.abs(intVal(b));
                    }, 0);

                    $('#activity-total-qty').html('-' + total + ' unit');
                },
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari riwayat transaksi...",
                    lengthMenu: "_MENU_ per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ riwayat",
                    infoEmpty: "Belum ada riwayat",
                    zeroRecords: "Tidak ada transaksi yang cocok",
                    paginate: {
                        first: "Pertama",
                        previous: "« Prev",
                        next: "Next »",
                        last: "Terakhir"
                    }
                },
                responsive: true
            });

            activityTable.on('order.dt search.dt draw.dt', function () {
                var i = 1;
                activityTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell) {
                    cell.innerHTML = i++;
                });
            });

            // Activity Log Grouping Handler
            $('#group-activity-select').on('change', function() {
                var colIdx = parseInt($(this).val());
                if (activityTable && activityTable.rowGroup) {
                    if (colIdx >= 0) {
                        activityTable.rowGroup().dataSrc(colIdx).enable().draw();
                    } else {
                        activityTable.rowGroup().disable().draw();
                    }
                }
            });
        });

        function refreshTables() {
            window.location.reload();
        }

        function openQrModal(name, sku, payload, bin) {
            document.getElementById('qr-modal-title').innerText = name;
            document.getElementById('qr-modal-sku').innerText = 'SKU: ' + sku;
            document.getElementById('qr-modal-bin').innerText = 'Lokasi Rak: ' + bin;
            
            var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(payload);
            document.getElementById('qr-modal-image').src = qrUrl;

            document.getElementById('admin-qr-modal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('admin-qr-modal').classList.add('hidden');
        }

        function printQrCode() {
            var printWin = window.open('', '_blank');
            var imgUrl = document.getElementById('qr-modal-image').src;
            var title = document.getElementById('qr-modal-title').innerText;
            var sku = document.getElementById('qr-modal-sku').innerText;
            var bin = document.getElementById('qr-modal-bin').innerText;

            printWin.document.write(`
                <html>
                <head>
                    <title>Cetak QR Code - ${sku}</title>
                    <style>
                        body { font-family: sans-serif; text-align: center; padding: 40px; }
                        .qr-card { border: 2px dashed #333; padding: 25px; display: inline-block; border-radius: 16px; }
                        img { width: 220px; height: 220px; }
                        h2 { margin: 10px 0 5px; font-size: 20px; }
                        p { margin: 3px 0; font-size: 14px; color: #555; }
                        .sku { font-weight: bold; font-family: monospace; color: #0284c7; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    <div class="qr-card">
                        <img src="${imgUrl}">
                        <h2>${title}</h2>
                        <p class="sku">${sku}</p>
                        <p>${bin}</p>
                    </div>
                </body>
                </html>
            `);
            printWin.document.close();
        }

        // ==========================================
        // Interactive Dashboard Visualizations (Chart.js)
        // ==========================================
        var stockChartInstance = null;
        var stockChartType = 'doughnut';

        var retrievalChartInstance = null;
        var retrievalChartType = 'bar';

        $(document).ready(function() {
            initStockStatusChart();
            initRetrievalCategoryChart();
        });

        // 1. Chart Ratio Status Stok Barang (Pie / Doughnut Chart)
        function initStockStatusChart() {
            var ctx = document.getElementById('stockStatusChart');
            if (!ctx) return;

            var inStock = {{ $inStockCount ?? 0 }};
            var lowStock = {{ $lowStockCount ?? 0 }};
            var outOfStock = {{ $outOfStockCount ?? 0 }};

            if (stockChartInstance) {
                stockChartInstance.destroy();
            }

            stockChartInstance = new Chart(ctx, {
                type: stockChartType,
                data: {
                    labels: ['Stok Aman (In Stock)', 'Stok Tipis (Low Stock)', 'Stok Habis (Out of Stock)'],
                    datasets: [{
                        data: [inStock, lowStock, outOfStock],
                        backgroundColor: [
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#f43f5e'  // Rose
                        ],
                        borderWidth: 2,
                        borderColor: '#0f172a',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#94a3b8',
                                font: { size: 11, weight: '600' },
                                padding: 14,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var val = context.raw || 0;
                                    var total = inStock + lowStock + outOfStock;
                                    var pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ' ' + label + ': ' + val + ' item (' + pct + '%)';
                                }
                            }
                        }
                    },
                    cutout: stockChartType === 'doughnut' ? '68%' : '0%'
                }
            });
        }

        function toggleStockChartType() {
            stockChartType = stockChartType === 'doughnut' ? 'pie' : 'doughnut';
            initStockStatusChart();
        }

        // 2. Chart Jenis Pengambilan Barang (4 Preset + 1 Custom / Lainnya)
        function initRetrievalCategoryChart() {
            var ctx = document.getElementById('retrievalCategoryChart');
            if (!ctx) return;

            var rutinQty = {{ $retrievalCategoryQty['rutin'] ?? 0 }};
            var maintQty = {{ $retrievalCategoryQty['maintenance'] ?? 0 }};
            var refillQty = {{ $retrievalCategoryQty['refill'] ?? 0 }};
            var rusakQty = {{ $retrievalCategoryQty['rusak'] ?? 0 }};
            var lainnyaQty = {{ $retrievalCategoryQty['lainnya'] ?? 0 }};

            var rutinCount = {{ $retrievalCategoryCounts['rutin'] ?? 0 }};
            var maintCount = {{ $retrievalCategoryCounts['maintenance'] ?? 0 }};
            var refillCount = {{ $retrievalCategoryCounts['refill'] ?? 0 }};
            var rusakCount = {{ $retrievalCategoryCounts['rusak'] ?? 0 }};
            var lainnyaCount = {{ $retrievalCategoryCounts['lainnya'] ?? 0 }};

            var categoryLabels = [
                '🛠️ Rutin Operasional',
                '⚡ Repair / Maintenance',
                '📦 Refill Area Kerja',
                '⚠️ Barang Rusak',
                '🧩 Lainnya (Custom)'
            ];

            var bgColors = [
                '#06b6d4', // Cyan
                '#eab308', // Yellow
                '#3b82f6', // Blue
                '#ef4444', // Red
                '#a855f7'  // Purple
            ];

            if (retrievalChartInstance) {
                retrievalChartInstance.destroy();
            }

            var isBar = retrievalChartType === 'bar';

            retrievalChartInstance = new Chart(ctx, {
                type: retrievalChartType,
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Total Unit Barang Diambil',
                        data: [rutinQty, maintQty, refillQty, rusakQty, lainnyaQty],
                        backgroundColor: bgColors,
                        borderRadius: isBar ? 8 : 0,
                        borderWidth: isBar ? 0 : 2,
                        borderColor: '#0f172a',
                        hoverOffset: isBar ? 0 : 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: isBar ? {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8', font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } }
                        }
                    } : {},
                    plugins: {
                        legend: {
                            display: !isBar,
                            position: 'bottom',
                            labels: {
                                color: '#94a3b8',
                                font: { size: 10, weight: '600' },
                                padding: 10,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var idx = context.dataIndex;
                                    var qty = context.raw || 0;
                                    var countsMap = [rutinCount, maintCount, refillCount, rusakCount, lainnyaCount];
                                    var count = countsMap[idx] || 0;
                                    return ' ' + qty + ' Unit diambil (' + count + ' x Transaksi)';
                                }
                            }
                        }
                    }
                }
            });
        }

        function toggleRetrievalChartType() {
            retrievalChartType = retrievalChartType === 'bar' ? 'pie' : 'bar';
            var btnLabel = document.getElementById('retrievalChartToggleLabel');
            if (btnLabel) {
                btnLabel.innerText = retrievalChartType === 'bar' ? 'Switch to Pie' : 'Switch to Bar';
            }
            initRetrievalCategoryChart();
        }
    </script>
@endpush