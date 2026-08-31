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
                    @foreach($allLowStockItems as $item)
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
                                    <button type="button" 
                                            onclick="openQuickRequisitionModal({{ json_encode(['id' => $item->id, 'name' => $item->name, 'sku' => $item->sku, 'available_stock' => $item->available_stock]) }}, {{ $deficit }})"
                                            class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl text-[10px] transition shadow-sm flex items-center">
                                        <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hidden Quick Requisition Form -->
<form id="quick-requisition-form" action="{{ route('admin.requisitions.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="item_id" id="quick_item_id">
    <input type="hidden" name="item_name" id="quick_item_name">
    <input type="hidden" name="sku" id="quick_sku">
    <input type="hidden" name="quantity_requested" id="quick_qty">
    <input type="hidden" name="reason" id="quick_reason">
</form>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
                $.fn.dataTable.ext.errMode = 'none';
            }
            if ($('#lowStockTable').length) {
                $('#lowStockTable').DataTable({
                    pageLength: -1,
                    lengthMenu: [[-1, 10, 25, 50], ["Tampilkan Semua", 10, 25, 50]],
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

            @if($outOfStockItems->count() > 0)
                setTimeout(() => {
                    Swal.fire({
                        title: 'Peringatan Stok Habis!',
                        html: 'Terdapat <strong class="text-rose-500 dark:text-rose-400 font-black">{{ $outOfStockItems->count() }} SKU barang</strong> dalam kondisi <strong>Stok 0 (Habis)</strong>.<br><span class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">Segera lakukan restock atau klik tombol "Ajukan" untuk membuat pengadaan barang.</span>',
                        icon: 'warning',
                        confirmButtonText: 'Mengerti & Review Data',
                        confirmButtonColor: '#e11d48',
                        customClass: {
                            popup: 'swal2-popup font-sans rounded-3xl border border-slate-200 dark:border-slate-800'
                        }
                    });
                }, 400);
            @endif
        });

        function openQuickRequisitionModal(item, deficitQty) {
            const recommendedQty = deficitQty > 0 ? deficitQty : 10;
            Swal.fire({
                title: 'Ajukan Pengadaan Barang',
                html: `
                    <div class="text-left space-y-3 font-sans text-xs">
                        <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-2xl">
                            <div class="font-black text-slate-900 dark:text-white text-sm">${item.name}</div>
                            <div class="text-[11px] font-mono text-cyan-600 dark:text-cyan-400 mt-0.5">SKU: ${item.sku || '-'}</div>
                            <div class="text-[11px] text-amber-600 dark:text-amber-400 font-extrabold mt-1">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Rekomendasi Restock Defisit: ${recommendedQty} unit
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Unit Diajukan *</label>
                            <input type="number" id="swal_req_qty" min="1" value="${recommendedQty}" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Alasan / Catatan Pengajuan *</label>
                            <select onchange="if(this.value){ document.getElementById('swal_req_reason').value = this.value; }" class="w-full mb-2 px-3 py-2 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200">
                                <option value="">-- Pilih Alasan Standar Cepat --</option>
                                <option value="Stok barang di gudang habis (Stok 0), butuh restock mendesak">🚨 Stok Habis (Stok 0), Restock Mendesak</option>
                                <option value="Stok menipis mendekati batas minimum gudang">⚠️ Stok Menipis (Mendekati Threshold)</option>
                                <option value="Antisipasi lonjakan permintaan operasional gudang">📈 Antisipasi Lonjakan Permintaan</option>
                                <option value="Pengadaan cadangan / buffer stok operasional">📦 Buffer Stok Operasional</option>
                            </select>
                            <textarea id="swal_req_reason" rows="2" placeholder="Atau ketikkan alasan pengajuan kustom..." class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500"></textarea>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-paper-plane mr-1"></i> Kirim Pengajuan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal2-popup font-sans rounded-3xl border border-slate-200 dark:border-slate-800'
                },
                preConfirm: () => {
                    const qty = document.getElementById('swal_req_qty').value;
                    const reason = document.getElementById('swal_req_reason').value;
                    if (!qty || qty < 1) {
                        Swal.showValidationMessage('Jumlah unit diajukan minimal 1!');
                        return false;
                    }
                    if (!reason || !reason.trim()) {
                        Swal.showValidationMessage('Alasan pengajuan wajib diisi!');
                        return false;
                    }
                    return { qty: parseInt(qty), reason: reason.trim() };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    document.getElementById('quick_item_id').value = item.id;
                    document.getElementById('quick_item_name').value = item.name;
                    document.getElementById('quick_sku').value = item.sku || '';
                    document.getElementById('quick_qty').value = result.value.qty;
                    document.getElementById('quick_reason').value = result.value.reason;
                    document.getElementById('quick-requisition-form').submit();
                }
            });
        }
    </script>
@endpush
