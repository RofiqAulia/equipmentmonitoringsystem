@extends('layouts.app')

@section('title', 'Deteksi Barang Menipis - Inventory Control System')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.35rem 0.75rem;
            color: var(--text-main);
            font-size: 0.75rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #e11d48, #be123c) !important;
            color: #ffffff !important;
            border: none !important;
        }
        table.dataTable td, table.dataTable th {
            white-space: normal !important;
            word-wrap: break-word !important;
            vertical-align: middle;
        }
        table.dataTable thead th.sorting:after {
            content: " ↕";
            opacity: 0.35;
            font-size: 0.75rem;
        }
        table.dataTable thead th.sorting_asc:after {
            content: " ▲ ASC";
            color: #e11d48;
            font-weight: 800;
            font-size: 0.65rem;
        }
        table.dataTable thead th.sorting_desc:after {
            content: " ▼ DESC";
            color: #e11d48;
            font-weight: 800;
            font-size: 0.65rem;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-panel p-6 rounded-3xl border border-pink-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-pink-500/20 text-pink-600 dark:text-pink-400 text-xs font-bold rounded-full border border-pink-500/30 uppercase tracking-wider animate-pulse">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Real-time Stock Detector
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Peringatan Kritis Gudang</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Deteksi Stok Barang Menipis & Habis</h1>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.stock.input') }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/20 transition flex items-center">
                <i class="fa-solid fa-boxes-packing mr-2"></i> Quick Restock Form
            </a>
        </div>
    </div>

    <!-- Alert Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-panel p-5 rounded-2xl border border-rose-500/40 bg-rose-500/10 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Out of Stock (Habis)</div>
                <div class="text-3xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ $outOfStockItems->count() }} <span class="text-xs text-slate-400 font-normal">SKU</span></div>
                <p class="text-[10px] text-rose-500 dark:text-rose-300 mt-1">Stok 0 unit, butuh tindakan restock segera!</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl animate-bounce">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-2xl border border-amber-500/40 bg-amber-500/10 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Low Stock (Menipis)</div>
                <div class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ $lowStockItems->count() }} <span class="text-xs text-slate-400 font-normal">SKU</span></div>
                <p class="text-[10px] text-amber-500 dark:text-amber-300 mt-1">Stok &le; batas minimum threshold</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div>
                <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Alert Ditemukan</div>
                <div class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $allLowStockItems->count() }} <span class="text-xs text-slate-400 font-normal">Item</span></div>
                <p class="text-[10px] text-slate-400 mt-1">Sistem memantau real-time setiap transaksi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
    </div>

    <!-- Detector Data Table -->
    <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-list-check text-rose-500 mr-2"></i> Daftar Barang Perlu Tindakan Restock / Pengajuan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Klik 'Restock' untuk mengisi stok atau 'Ajukan' untuk minta pengadaan</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
            <table id="lowStockTable" class="w-full min-w-[880px] align-middle text-left text-xs text-slate-700 dark:text-slate-300 display">
                <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-14 no-sort">Foto</th>
                        <th class="px-4 py-3.5 cursor-pointer sorting">SKU & QR Payload</th>
                        <th class="px-4 py-3.5 min-w-[200px] cursor-pointer sorting">Nama Barang</th>
                        <th class="px-4 py-3.5 cursor-pointer sorting">Lokasi Rak</th>
                        <th class="px-4 py-3.5 text-center cursor-pointer sorting">Sisa Stok</th>
                        <th class="px-4 py-3.5 text-center cursor-pointer sorting">Threshold Min</th>
                        <th class="px-4 py-3.5 text-center cursor-pointer sorting">Defisit Unit</th>
                        <th class="px-4 py-3.5 text-center no-sort">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                    @forelse($allLowStockItems as $item)
                        @php
                            $deficit = max(0, $item->minimum_stock - $item->available_stock);
                            $hasPendingReq = in_array($item->id, $pendingRequisitions);
                        @endphp
                        <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
                            <td class="px-4 py-3 w-14">
                                <img src="{{ $item->image_url ?? 'https://placehold.co/100x100/1e293b/06b6d4?text=Item' }}" class="w-10 h-10 rounded-xl object-cover border border-slate-300 dark:border-slate-700">
                            </td>
                            <td class="px-4 py-3 break-words max-w-[160px]">
                                <div class="font-bold text-cyan-600 dark:text-cyan-400 text-xs">{{ $item->sku }}</div>
                                <div class="text-[10px] text-slate-400 leading-snug">{{ $item->qr_code_payload }}</div>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white break-words max-w-[240px] leading-relaxed">
                                {{ $item->name }}
                                @if($hasPendingReq)
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-semibold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30">Ada Pengajuan Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-medium break-words max-w-[140px]">
                                {{ $item->location_bin }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-xs {{ $item->available_stock <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}" data-order="{{ $item->available_stock }}">
                                {{ $item->available_stock }} unit
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-slate-500 dark:text-slate-400 text-xs" data-order="{{ $item->minimum_stock }}">
                                {{ $item->minimum_stock }} unit
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-rose-500 text-xs" data-order="{{ $deficit }}">
                                -{{ $deficit }} unit
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('admin.stock.input', ['item_id' => $item->id]) }}" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl text-[10px] transition shadow-sm flex items-center">
                                        <i class="fa-solid fa-plus mr-1"></i> Restock
                                    </a>
                                    <a href="{{ route('admin.requisitions.index', ['item_id' => $item->id]) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl text-[10px] transition shadow-sm flex items-center">
                                        <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-2 block"></i>
                                Semua stok barang gudang terpantau aman di atas batas minimum.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#lowStockTable').length) {
                $('#lowStockTable').DataTable({
                    pageLength: 10,
                    order: [[4, 'asc']], // Order by Sisa Stok ASC (most critical first)
                    columnDefs: [
                        { orderable: false, targets: [0, 7] }
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari SKU, Nama Barang, atau Rak...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ alert stok",
                        paginate: { previous: "« Prev", next: "Next »" }
                    },
                    responsive: true
                });
            }
        });
    </script>
@endpush
