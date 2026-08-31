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

    <!-- Inventory Summary Section -->
    <div class="space-y-3">
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center">
            <i class="fa-solid fa-boxes-stacked text-cyan-500 mr-2"></i> Inventory Summary
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.dashboard', ['status' => 'in_stock']) }}" class="glass-card p-5 rounded-2xl border-l-4 border-l-emerald-500 hover:border-emerald-500 transition group block">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">In Stock (Aman)</div>
                        <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 group-hover:text-emerald-500 transition">{{ $inStockCount ?? $inventory_summary['in_stock'] ?? 0 }} <span class="text-xs text-slate-400 font-normal">Item</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Klik untuk memfilter barang stok cukup</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.low-stock') }}" class="glass-card p-5 rounded-2xl border-l-4 border-l-amber-500 hover:border-amber-500 transition group block">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Low Stock (Peringatan)</div>
                        <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 group-hover:text-amber-500 transition">{{ $lowStockCount ?? $inventory_summary['low_stock'] ?? 0 }} <span class="text-xs text-slate-400 font-normal">Item</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Klik untuk memfilter barang mendekati batas minimum</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.low-stock') }}" class="glass-card p-5 rounded-2xl border-l-4 border-l-rose-500 hover:border-rose-500 transition group block">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Out of Stock (Kritis)</div>
                        <div class="text-3xl font-black text-slate-900 dark:text-white mt-1 group-hover:text-rose-500 transition">{{ $outOfStockCount ?? $inventory_summary['out_of_stock'] ?? 0 }} <span class="text-xs text-slate-400 font-normal">Item</span></div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Klik untuk memfilter barang stok habis</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- DataTables Table 1: Activity Log Table -->
    @include('admin.partials.activity_log')

    <!-- DataTables Table 2: Inventory Master Table -->
    @include('admin.partials.inventory_table')
</div>

<!-- Modal Terbitkan QR Code untuk Operator -->
@include('admin.partials.qr_modal')
@endsection

@push('scripts')
    <!-- jQuery & DataTables Core + Export Extensions + RowGroup -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

            // 1. Activity Log Table with Dynamic Row Grouping
            var activityTable = $('#activityLogTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']], // Default Sort ID Descending
                columnDefs: [
                    { orderable: true, targets: '_all' }
                ],
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

            // Activity Log Grouping Handler
            $('#group-activity-select').on('change', function() {
                var colIdx = parseInt($(this).val());
                if (colIdx >= 0) {
                    activityTable.rowGroup().dataSrc(colIdx).draw();
                } else {
                    activityTable.rowGroup().disable().draw();
                }
            });

            // 2. Inventory Master Table with Dynamic Row Grouping & Column ASC/DESC Filtering
            var inventoryTable = $('#inventoryTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                order: [[1, 'asc']], // Default Sort by SKU ASC
                columnDefs: [
                    { orderable: false, targets: [0, 7, 8] }, // Photo, Aksi QR & Aksi columns no sort
                    { orderable: true, targets: [1, 2, 3, 4, 5, 6] }
                ],
                dom: '<"flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 p-1"lBf>rt<"flex flex-col md:flex-row md:items-center justify-between gap-3 mt-4 p-1"ip>',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa-solid fa-file-csv mr-1.5"></i> Ekspor CSV',
                        title: 'Inventory_Control_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6] // Hanya kolom data terpilih (SKU, Nama, Rak, Stok, Min, Status)
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa-solid fa-file-excel mr-1.5"></i> Ekspor Excel',
                        title: 'Inventory_Control_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6] // Exclude Foto (col 0) & Aksi QR (col 7)
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print mr-1.5"></i> Cetak Tabel',
                        title: '',
                        messageTop: `
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
                                <div>
                                    <img src="{{ asset('images/LogoMieGacoan.png') }}" alt="Logo" style="max-height: 70px; width: auto; object-fit: contain;">
                                </div>
                                <div style="text-align: right; font-weight: 700; font-size: 12px; color: #0f172a;">
                                    Malang, {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        `,
                        messageBottom: `
                            <div style="margin-top: 35px; display: flex; justify-content: flex-end; font-size: 11px; color: #0f172a;">
                                <div style="text-align: center; width: 200px;">
                                    <div>Disetujui oleh,</div>
                                    <div style="height: 55px;"></div>
                                    <div style="font-weight: bold;">{{ auth()->user()->name ?? 'Supervisor' }}</div>
                                    <div style="font-size: 10px; color: #64748b;">Supervisor Gudang</div>
                                </div>
                            </div>
                        `,
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari SKU, Nama Barang, atau Rak...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data barang",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data barang)",
                    zeroRecords: "Tidak ada data barang yang ditemukan",
                    paginate: {
                        first: "Pertama",
                        previous: "« Prev",
                        next: "Next »",
                        last: "Terakhir"
                    }
                },
                responsive: true
            });

            // Inventory Grouping Handler
            $('#group-inventory-select').on('change', function() {
                var colIdx = parseInt($(this).val());
                if (colIdx >= 0) {
                    inventoryTable.rowGroup().dataSrc(colIdx).draw();
                } else {
                    inventoryTable.rowGroup().disable().draw();
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
    </script>
@endpush