@extends('layouts.app')

@section('title', 'Master Data Inventaris Barang - Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Top Header & Action Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel p-6 rounded-3xl">
        <div class="space-y-1">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                    Database Master Gudang
                </span>
                <span class="text-xs text-slate-400">&bull; Live Management</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center">
                <i class="fa-solid fa-boxes-stacked text-sky-600 dark:text-sky-400 mr-3"></i> Master Data Inventaris
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Kelola seluruh data barang, pencarian SKU, filter status stok, dan terbitkan QR Code.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="refreshMasterTable()" type="button" class="px-3.5 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition flex items-center shadow-sm">
                <i class="fa-solid fa-rotate mr-1.5"></i> Refresh
            </button>

            <a href="{{ route('admin.stock.input') }}" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs transition flex items-center shadow-md shadow-sky-600/25">
                <i class="fa-solid fa-plus mr-1.5"></i> Restock Barang
            </a>

            <a href="{{ route('admin.stock.print') }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition flex items-center shadow-md shadow-emerald-600/25">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <!-- Inventory Master Table Component -->
    @include('admin.partials.inventory_table')
</div>

<!-- QR Code Modal -->
@include('admin.partials.qr_modal')
@endsection

@push('scripts')
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

            var inventoryExportOptions = {
                columns: [1, 2, 3, 4, 5, 6],
                format: {
                    body: function (data, row, column, node) {
                        if (column === 0 && node) {
                            var skuEl = $(node).find('.item-sku-text');
                            if (skuEl.length) {
                                return skuEl.text().trim();
                            }
                        }
                        if (node) {
                            return $(node).text().trim().replace(/\s+/g, ' ');
                        }
                        return typeof data === 'string' ? data.replace(/<[^>]*>/g, '').trim() : data;
                    },
                    header: function (data, column) {
                        if (column === 0) {
                            return 'Kode SKU';
                        }
                        return data;
                    }
                }
            };

            var inventoryTable = $('#inventoryTable').DataTable({
                pageLength: -1,
                lengthMenu: [[-1, 10, 25, 50, 100], ["Tampilkan Semua", 10, 25, 50, 100]],
                order: [[1, 'asc']], // Default Sort by SKU ASC
                columnDefs: [
                    { orderable: false, targets: [0, 7, 8] },
                    { orderable: true, targets: [1, 2, 3, 4, 5, 6] }
                ],
                dom: '<"flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 p-1"lBf>rt<"flex flex-col md:flex-row md:items-center justify-between gap-3 mt-4 p-1"ip>',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa-solid fa-file-csv mr-1.5"></i> Ekspor CSV',
                        title: 'Inventory_Control_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: inventoryExportOptions
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa-solid fa-file-excel mr-1.5"></i> Ekspor Excel',
                        title: 'Inventory_Control_Report_' + new Date().toISOString().slice(0,10),
                        exportOptions: inventoryExportOptions
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
                        exportOptions: inventoryExportOptions
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

        function refreshMasterTable() {
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
