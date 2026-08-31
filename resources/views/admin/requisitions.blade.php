@extends('layouts.app')

@section('title', 'Pengajuan Barang & Pengadaan Stok - Inventory Control System')

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
            background: #0284c7 !important;
            color: #ffffff !important;
            border: none !important;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-sky-500/10 text-sky-600 dark:text-sky-400 text-xs font-bold rounded-full border border-sky-500/30 uppercase tracking-wider">
                    <i class="fa-solid fa-clipboard-list mr-1"></i> Pengadaan Barang
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Pengadaan & Restock Barang</span>
            </div>
               <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Pengadaan Barang & Restock</h1>
        </div>

        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleRequisitionModal()" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/20 transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Buat Pengajuan Baru
            </button>
        </div>
    </div>

    <!-- Requisition KPI Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-panel p-4 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div>
                <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Pengajuan</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">{{ $stats['total'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-500/10 text-slate-600 dark:text-slate-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>

        <div class="glass-panel p-4 rounded-2xl border border-amber-500/30 bg-amber-500/5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-amber-600 dark:text-amber-400">Menunggu Persetujuan</div>
                <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-0.5">{{ $stats['pending'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="glass-panel p-4 rounded-2xl border border-blue-500/30 bg-blue-500/5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-blue-600 dark:text-blue-400">Disetujui (Approved)</div>
                <div class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-0.5">{{ $stats['approved'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-thumbs-up"></i>
            </div>
        </div>

        <div class="glass-panel p-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/5 flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Selesai (Restocked)</div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $stats['completed'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- Requisitions Data Table -->
    <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-list-ul text-amber-500 mr-2"></i> Daftar Riwayat Pengajuan Barang
            </h2>

            <!-- Status Filter -->
            <form action="{{ route('admin.requisitions.index') }}" method="GET" class="flex items-center space-x-2">
                <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
            <table id="requisitionsTable" class="w-full min-w-[850px] align-middle text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 whitespace-nowrap">ID & Waktu</th>
                        <th class="px-4 py-3.5 min-w-[180px]">Nama Barang & SKU</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Qty Diajukan</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Pemohon</th>
                        <th class="px-4 py-3.5 min-w-[160px]">Alasan Pengajuan</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Status</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                    @foreach($requisitions as $req)
                        <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
                            <td class="px-4 py-3 font-mono text-slate-500 whitespace-nowrap">
                                <div class="font-bold text-slate-800 dark:text-slate-200">#REQ-{{ $req->id }}</div>
                                <div class="text-[10px] text-slate-400">{{ $req->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 min-w-[180px]">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $req->item_name }}</div>
                                <div class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400">{{ $req->sku ?? ($req->item ? $req->item->sku : '-') }}</div>
                            </td>
                            <td class="px-4 py-3 text-center font-black text-amber-600 dark:text-amber-400 text-sm whitespace-nowrap">
                                +{{ $req->quantity_requested }} unit
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $req->requester->name ?? 'Admin/Operator' }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400 min-w-[160px] truncate" title="{{ $req->reason }}">
                                {{ $req->reason }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($req->status === 'pending')
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 uppercase tracking-wider animate-pulse">Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 uppercase tracking-wider">Approved</span>
                                @elseif($req->status === 'completed')
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">Completed</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 uppercase tracking-wider">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($req->status === 'pending')
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <form action="{{ route('admin.requisitions.update-status', $req->id) }}" method="POST" class="inline" onsubmit="return confirmAction(this, 'menyetujui', 'approved');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-[10px] font-bold transition shadow-sm">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.requisitions.update-status', $req->id) }}" method="POST" class="inline" onsubmit="return confirmAction(this, 'menolak', 'rejected');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-[10px] font-bold transition shadow-sm">Tolak</button>
                                        </form>
                                    </div>
                                @elseif($req->status === 'approved')
                                    <form action="{{ route('admin.requisitions.update-status', $req->id) }}" method="POST" class="inline" onsubmit="return confirmAction(this, 'menyelesaikan restock', 'completed');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-[10px] font-bold transition shadow-sm">Tandai Restocked</button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">No Action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Pengajuan Barang -->
<div id="modal-requisition" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm hidden overflow-y-auto">
    <div class="w-full max-w-lg glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4 shadow-2xl my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-circle-plus text-amber-500 mr-2"></i> Form Buat Pengajuan Barang
            </h3>
            <button onclick="toggleRequisitionModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.requisitions.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih dari Stok Menipis (Opsional)</label>
                <select name="item_id" id="req_item_id" onchange="onSelectReqItem(this)" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                    <option value="">-- Pilih Barang Menipis / Manual --</option>
                    @foreach($lowStockItems as $lItem)
                        @php
                            $deficit = max(1, $lItem->minimum_stock - $lItem->available_stock);
                            $isSelected = (string) request('item_id') === (string) $lItem->id;
                        @endphp
                        <option value="{{ $lItem->id }}" 
                                data-name="{{ $lItem->name }}" 
                                data-sku="{{ $lItem->sku }}"
                                data-deficit="{{ $deficit }}"
                                {{ $isSelected ? 'selected' : '' }}>
                            [{{ $lItem->sku }}] {{ $lItem->name }} (Sisa Stok: {{ $lItem->available_stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Barang *</label>
                <input type="text" name="item_name" id="req_item_name" required placeholder="Contoh: Module Control Board" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode SKU (Opsional)</label>
                    <input type="text" name="sku" id="req_sku" placeholder="Contoh: SKU-123" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-mono text-cyan-600 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Unit Diajukan *</label>
                    <input type="number" name="quantity_requested" id="req_qty" min="1" value="10" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alasan / Catatan Pengajuan *</label>
                <!-- Quick Preset Reason Chips -->
                <div class="flex flex-wrap gap-1.5 mb-2">
                    <button type="button" onclick="setReqPresetReason('Stok barang di gudang habis (Stok 0)')" class="px-2 py-0.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 text-[10px] font-semibold rounded-lg border border-rose-500/20 transition">🚨 Stok Habis (0)</button>
                    <button type="button" onclick="setReqPresetReason('Stok menipis mendekati batas minimum')" class="px-2 py-0.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 text-[10px] font-semibold rounded-lg border border-amber-500/20 transition">⚠️ Stok Menipis</button>
                    <button type="button" onclick="setReqPresetReason('Antisipasi lonjakan permintaan operasional')" class="px-2 py-0.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 text-[10px] font-semibold rounded-lg border border-sky-500/20 transition">📈 Lonjakan Permintaan</button>
                    <button type="button" onclick="setReqPresetReason('Cadangan / buffer stok operasional gudang')" class="px-2 py-0.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-semibold rounded-lg border border-emerald-500/20 transition">📦 Buffer Stok</button>
                </div>
                <textarea name="reason" id="req_reason" rows="3" required placeholder="Pilih preset di atas atau ketik alasan kustom..." class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500"></textarea>
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="toggleRequisitionModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/20 transition">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
            $.fn.dataTable.ext.errMode = 'none';
        }
        if ($('#requisitionsTable').length) {
            $('#requisitionsTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari riwayat pengajuan...",
                    lengthMenu: "_MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengajuan",
                    paginate: { previous: "« Prev", next: "Next »" }
                },
                responsive: true
            });
        }

        // Auto open modal and populate form if item_id is passed in query string
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('item_id')) {
            const select = document.getElementById('req_item_id');
            if (select && select.value) {
                onSelectReqItem(select);
            }
            toggleRequisitionModal();
            setTimeout(() => {
                const reasonField = document.getElementById('req_reason');
                if (reasonField) reasonField.focus();
            }, 300);
        }
    });

    window.confirmAction = function(form, actionText, actionType = 'default') {
        let title = 'Konfirmasi Tindakan';
        let icon = 'question';
        let confirmBtnColor = '#0284c7';
        let confirmBtnText = 'Ya, Proses';

        if (actionType === 'approved') {
            title = 'Setujui Pengajuan Barang?';
            icon = 'question';
            confirmBtnColor = '#0284c7';
            confirmBtnText = '<i class="fa-solid fa-thumbs-up mr-1"></i> Ya, Setujui';
        } else if (actionType === 'rejected') {
            title = 'Tolak Pengajuan Barang?';
            icon = 'warning';
            confirmBtnColor = '#e11d48';
            confirmBtnText = '<i class="fa-solid fa-ban mr-1"></i> Ya, Tolak';
        } else if (actionType === 'completed') {
            title = 'Selesaikan & Tambah Stok?';
            icon = 'success';
            confirmBtnColor = '#10b981';
            confirmBtnText = '<i class="fa-solid fa-circle-check mr-1"></i> Ya, Tandai Restocked';
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: `Apakah Anda yakin ingin <strong>${actionText}</strong> pengajuan barang ini?`,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal',
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal2-popup font-sans rounded-3xl border border-slate-200 dark:border-slate-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        return false;
    };

    function setReqPresetReason(text) {
        const field = document.getElementById('req_reason');
        if (field) {
            field.value = text;
            field.focus();
        }
    }

    function toggleRequisitionModal() {
        const modal = document.getElementById('modal-requisition');
        if (modal) modal.classList.toggle('hidden');
    }

    function onSelectReqItem(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) return;
        const name = opt.getAttribute('data-name');
        const sku = opt.getAttribute('data-sku');
        const deficit = opt.getAttribute('data-deficit');
        if (name) document.getElementById('req_item_name').value = name;
        if (sku) document.getElementById('req_sku').value = sku;
        if (deficit && document.getElementById('req_qty')) {
            document.getElementById('req_qty').value = deficit;
        }
    }
</script>
@endpush
