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
    <!-- DataTables Core + Export Extensions + RowGroup (Local Vendor Assets for Maximum Speed & Offline Support) -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.rowGroup.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
                $.fn.dataTable.ext.errMode = 'none';
            }

            var inventoryExportOptions = {
                columns: [0, 2, 3, 4, 5, 6, 7, 8],
                format: {
                    body: function (data, row, column, node) {
                        if (column === 0) {
                            return (row + 1).toString();
                        }
                        if (column === 1 && node) {
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
                            return 'No';
                        }
                        if (column === 1) {
                            return 'Kode SKU';
                        }
                        return data;
                    }
                }
            };

            var inventoryTable = $('#inventoryTable').DataTable({
                pageLength: 25,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Tampilkan Semua"]],
                deferRender: true,
                order: [[0, 'asc']], // Default Sort by No ASC
                columnDefs: [
                    { orderable: false, targets: [1, 9] },
                    { orderable: true, targets: [0, 2, 3, 4, 5, 6, 7, 8] }
                ],
                dom: '<"flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-4 p-3 bg-slate-50/70 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-800"<"flex flex-wrap items-center gap-3"lB><"w-full xl:w-auto"f>>rt<"flex flex-col sm:flex-row items-center justify-between gap-4 mt-4 p-2"ip>',
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
                                    <div style="font-size: 10px; color: #64748b;">{{ auth()->user()->role ?? 'Supervisor' }}</div>
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

            inventoryTable.on('order.dt search.dt draw.dt', function () {
                inventoryTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
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

        function openQrModal(name, sku, payload, bin, category) {
            document.getElementById('qr-modal-title').innerText = name;
            document.getElementById('qr-modal-sku').innerText = 'KODE : ' + sku;
            document.getElementById('qr-modal-category').innerText = 'ITEM GIS : ' + (category || 'SPAREPART INVENTARIS');
            document.getElementById('qr-modal-bin').innerText = 'LOKASI: ' + bin;
            
            var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(payload);
            document.getElementById('qr-modal-image').src = qrUrl;

            document.getElementById('admin-qr-modal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('admin-qr-modal').classList.add('hidden');
        }

        function printQrCode() {
            var printWin = window.open('', '_blank', 'width=450,height=520');
            var imgUrl = document.getElementById('qr-modal-image').src;
            var title = document.getElementById('qr-modal-title').innerText;
            var sku = document.getElementById('qr-modal-sku').innerText.replace('KODE : ', '');
            var category = document.getElementById('qr-modal-category').innerText.replace('ITEM GIS : ', '');
            var bin = document.getElementById('qr-modal-bin').innerText.replace('LOKASI: ', '');

            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cetak Stiker Single - ${sku}</title>
                    <style>
                        @page {
                            size: 70mm 50mm;
                            margin: 0;
                        }
                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            margin: 0;
                            padding: 0;
                            background: #ffffff;
                            color: #000000;
                            width: 70mm;
                            height: 50mm;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            box-sizing: border-box;
                        }
                        .sticker-container {
                            width: 68mm;
                            height: 48mm;
                            border: 1px solid #000;
                            box-sizing: border-box;
                            display: flex;
                            flex-direction: column;
                        }
                        .row-header {
                            border-bottom: 1px solid #000;
                            padding: 4px 6px;
                            font-weight: bold;
                            font-size: 11px;
                            text-align: center;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            text-transform: uppercase;
                            min-height: 12mm;
                            line-height: 1.2;
                        }
                        .row-middle {
                            display: flex;
                            flex: 1;
                        }
                        .middle-left {
                            width: 65%;
                            display: flex;
                            flex-direction: column;
                            border-right: 1px solid #000;
                        }
                        .info-row {
                            flex: 1;
                            padding: 2px 4px;
                            font-size: 10px;
                            font-weight: bold;
                            display: flex;
                            align-items: center;
                            text-transform: uppercase;
                            line-height: 1.2;
                        }
                        .info-row:first-child {
                            border-bottom: 1px solid #000;
                        }
                        .middle-right {
                            width: 35%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 2px;
                        }
                        .qr-img {
                            width: 100%;
                            height: 100%;
                            max-height: 20mm;
                            object-fit: contain;
                        }
                        .row-footer {
                            border-top: 1px solid #000;
                            padding: 4px 6px;
                            font-size: 10px;
                            font-weight: bold;
                            display: flex;
                            align-items: center;
                            text-transform: uppercase;
                            min-height: 8mm;
                        }
                    </style>
                </head>
                <body onload="window.print(); setTimeout(function(){ window.close(); }, 500);">
                    <div class="sticker-container">
                        <div class="row-header">${title}</div>
                        <div class="row-middle">
                            <div class="middle-left">
                                <div class="info-row">ITEM GIS : ${category}</div>
                                <div class="info-row">KODE : ${sku}</div>
                            </div>
                            <div class="middle-right">
                                <img class="qr-img" src="${imgUrl}" alt="QR ${sku}">
                            </div>
                        </div>
                        <div class="row-footer">LOKASI: ${bin}</div>
                    </div>
                </body>
                </html>
            `);
            printWin.document.close();
        }
    </script>
@endpush
