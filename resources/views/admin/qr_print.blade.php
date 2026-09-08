@extends('layouts.app')

@section('title', 'Cetak QR Code Master Data - Admin Panel')

@push('styles')
<style>
    /* Styling Khusus Cetak Kertas A4 & Label Stiker Modern */
    @media print {
        @page {
            size: A4 portrait;
            margin: 6mm 6mm;
        }

        body {
            background: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        }

        .no-print, 
        nav, 
        aside, 
        footer, 
        #sidebar-backdrop {
            display: none !important;
        }

        .main-content-container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        .lg\:pl-\[17\.5rem\] {
            padding-left: 0 !important;
        }

        .print-container {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .qr-grid-container {
            display: grid !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Preset Grid Stiker 3x5 (15 Label per Lembar A4 - Pas 1 Halaman Presisi) */
        .grid-3x5 {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 3.5mm !important;
        }

        /* Preset Grid Stiker 4x6 (24 Label per Lembar A4) */
        .grid-4x6 {
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 2.5mm !important;
        }

        /* Preset Grid Stiker 2x4 (8 Label per Lembar A4) */
        .grid-2x4 {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 4mm !important;
        }

        /* Preset Grid Single (1 Label per Halaman) */
        .grid-single {
            grid-template-columns: repeat(1, 1fr) !important;
        }

        /* Modern QR Card Container untuk Cetak */
        .qr-card {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 5px 6px !important;
            background: #ffffff !important;
            color: #0f172a !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            text-align: center !important;
            box-sizing: border-box !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .grid-3x5 .qr-card {
            height: 52mm !important;
            max-height: 52mm !important;
        }

        .grid-4x6 .qr-card {
            height: 42mm !important;
            max-height: 42mm !important;
        }

        .grid-2x4 .qr-card {
            height: 65mm !important;
        }

        /* Modern Card Header Accent */
        .qr-card-header {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-b: 1px solid #f1f5f9 !important;
            padding-bottom: 2px !important;
            margin-bottom: 2px !important;
        }

        .qr-card-seq {
            font-size: 8px !important;
            font-weight: 800 !important;
            background: #0f172a !important;
            color: #ffffff !important;
            padding: 1px 4px !important;
            border-radius: 3px !important;
            line-height: 1 !important;
        }

        .qr-card-sku {
            font-size: 9px !important;
            font-family: monospace !important;
            font-weight: 900 !important;
            color: #0369a1 !important;
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            padding: 1px 5px !important;
            border-radius: 4px !important;
            letter-spacing: 0.5px !important;
        }

        /* QR Code Container Box */
        .qr-image-wrapper {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 2px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .qr-card img {
            width: 80px !important;
            height: 80px !important;
            max-width: 100% !important;
            object-fit: contain !important;
        }

        .grid-3x5 .qr-card img {
            width: 98px !important;
            height: 98px !important;
        }

        .grid-4x6 .qr-card img {
            width: 72px !important;
            height: 72px !important;
        }

        .grid-2x4 .qr-card img {
            width: 130px !important;
            height: 130px !important;
        }

        .grid-single .qr-card img {
            width: 220px !important;
            height: 220px !important;
        }

        /* Card Content Details */
        .qr-card-title {
            font-size: 10px !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            margin-top: 2px !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            width: 100% !important;
        }

        .qr-card-bin {
            font-size: 10px !important;
            font-weight: 900 !important;
            color: #ffffff !important;
            background: #0f172a !important;
            padding: 2px 6px !important;
            border-radius: 4px !important;
            margin-top: 2px !important;
            letter-spacing: 0.5px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 main-content-container">
    
    <!-- 1. HEADER CONTROL BAR (No-Print) -->
    <div class="no-print glass-panel p-6 rounded-3xl space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                        Master Data Gudang
                    </span>
                    <span class="text-xs text-slate-400">&bull; Modul Cetak QR Code</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center">
                    <i class="fa-solid fa-qrcode text-sky-600 dark:text-sky-400 mr-3"></i> Cetak Batch QR Code Barang
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Cetak kode QR dengan tata letak modern, rapi, dan presisi untuk kertas A4 atau kertas stiker label.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.master-data') }}" class="px-3.5 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition flex items-center shadow-sm">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
                </a>

                <button onclick="window.print()" type="button" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-black text-xs transition flex items-center shadow-lg shadow-sky-600/30 active:scale-95">
                    <i class="fa-solid fa-print mr-2 text-sm"></i> Cetak Kertas / Stiker A4
                </button>
            </div>
        </div>

        <!-- 2. FILTER & GRID LAYOUT CONTROLLER FORM -->
        <form action="{{ route('admin.stock.qr-print') }}" method="GET" id="qr-filter-form" class="pt-4 border-t border-slate-200 dark:border-slate-800 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Searching Nama Barang / SKU -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-sky-500"></i> Cari Barang / SKU
                </label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ketik kata kunci..." class="w-full pl-8 pr-3 py-2 text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-xs text-slate-400"></i>
                </div>
            </div>

            <!-- Mode Pilihan Item -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                    <i class="fa-solid fa-filter mr-1 text-sky-500"></i> Mode Pilihan Cetak
                </label>
                <select name="mode" id="mode-select" onchange="toggleModeFields()" class="w-full px-3 py-2 text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
                    <option value="all" {{ $mode === 'all' ? 'selected' : '' }}>Semua Item ({{ $allItems->count() }} Barang)</option>
                    <option value="range" {{ $mode === 'range' ? 'selected' : '' }}>Rentang Nomor (misal: No 1 - 15)</option>
                    <option value="selected" {{ $mode === 'selected' ? 'selected' : '' }}>Pilihan Item Manual (Checkbox)</option>
                </select>
            </div>

            <!-- Pengurutan Data (Sorting) -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                    <i class="fa-solid fa-arrow-down-a-z mr-1 text-sky-500"></i> Pengurutan Data
                </label>
                <div class="flex space-x-1.5">
                    <select name="sort_by" class="w-full px-3 py-2 text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
                        <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Berdasarkan Abjad (Nama)</option>
                        <option value="sku" {{ $sortBy === 'sku' ? 'selected' : '' }}>Berdasarkan Kode SKU</option>
                        <option value="id" {{ $sortBy === 'id' ? 'selected' : '' }}>Berdasarkan Urutan Nomer ID</option>
                    </select>
                    <select name="sort_dir" class="w-24 px-2 py-2 text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
                        <option value="asc" {{ $sortDir === 'asc' ? 'selected' : '' }}>A-Z / 1-9</option>
                        <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Z-A / 9-1</option>
                    </select>
                </div>
            </div>

            <!-- Preset Layout Grid Stiker -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                    <i class="fa-solid fa-table-cells mr-1 text-sky-500"></i> Layout Kertas / Stiker
                </label>
                <select name="grid_layout" id="grid-layout-select" onchange="applyGridLayout()" class="w-full px-3 py-2 text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
                    <option value="grid-3x5" {{ $gridLayout === 'grid-3x5' ? 'selected' : '' }}>Stiker 3x5 (15 Kode / Lembar A4 - Default)</option>
                    <option value="grid-4x6" {{ $gridLayout === 'grid-4x6' ? 'selected' : '' }}>Stiker 4x6 (24 Kode / Lembar A4)</option>
                    <option value="grid-2x4" {{ $gridLayout === 'grid-2x4' ? 'selected' : '' }}>Stiker 2x4 (8 Kode / Lembar A4)</option>
                    <option value="grid-single" {{ $gridLayout === 'grid-single' ? 'selected' : '' }}>Stiker Single (1 QR / Lembar)</option>
                </select>
            </div>

            <!-- Submit Filter Button -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs transition shadow-md shadow-sky-600/20 flex items-center justify-center">
                    <i class="fa-solid fa-check mr-1.5"></i> Terapkan Filter
                </button>
            </div>
        </form>

        <!-- Dynamic Form Fields untuk Mode Rentang (Range) -->
        <div id="range-fields-container" class="pt-3 border-t border-slate-200 dark:border-slate-800 {{ $mode === 'range' ? '' : 'hidden' }}">
            <div class="p-3 bg-sky-500/10 rounded-2xl border border-sky-500/20 flex flex-wrap items-center gap-3">
                <div class="text-xs font-bold text-sky-700 dark:text-sky-300 flex items-center">
                    <i class="fa-solid fa-arrow-right-1-9 mr-1.5 text-base"></i> Tentukan Rentang Nomor Urut Barang:
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Dari No:</span>
                    <input type="number" form="qr-filter-form" name="range_from" value="{{ $rangeFrom }}" min="1" max="{{ $allItems->count() }}" class="w-20 px-2.5 py-1 text-xs font-bold text-center rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white">
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Sampai No:</span>
                    <input type="number" form="qr-filter-form" name="range_to" value="{{ $rangeTo }}" min="1" max="{{ $allItems->count() }}" class="w-20 px-2.5 py-1 text-xs font-bold text-center rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white">
                </div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Total data tersedia: {{ $allItems->count() }} item</span>
            </div>
        </div>

        <!-- Dynamic Item Selection List untuk Mode Selected (Manual Checkbox) -->
        <div id="selected-fields-container" class="pt-3 border-t border-slate-200 dark:border-slate-800 space-y-3 {{ $mode === 'selected' ? '' : 'hidden' }}">
            <div class="flex items-center justify-between">
                <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                    Pilih Item yang Ingin Dicetak (Centang Kotak):
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="selectAllItems(true)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-sky-600 hover:text-white transition">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="selectAllItems(false)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition">
                        Batal Semua
                    </button>
                    <input type="text" id="item-search-input" onkeyup="filterItemCheckboxes()" placeholder="Cari nama/SKU..." class="px-2.5 py-1 text-xs rounded-lg bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="max-h-48 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-900/70 rounded-2xl border border-slate-200 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($allItems as $item)
                    <label class="item-checkbox-label flex items-center space-x-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 hover:border-sky-500 cursor-pointer text-xs transition">
                        <input type="checkbox" form="qr-filter-form" name="selected_ids[]" value="{{ $item->id }}" class="item-checkbox rounded text-sky-600 focus:ring-sky-500 w-4 h-4" {{ in_array($item->id, $selectedIds) ? 'checked' : '' }}>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900 dark:text-white truncate search-name">{{ $item->name }}</div>
                            <div class="font-mono text-[10px] text-sky-600 dark:text-sky-400 font-extrabold search-sku">{{ $item->sku }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 3. INFORMASI STATUS HASIL FILTER (No-Print) -->
    <div class="no-print flex items-center justify-between px-4 py-3 bg-sky-500/10 rounded-2xl border border-sky-500/20 text-xs">
        <div class="flex items-center space-x-2 text-sky-700 dark:text-sky-300 font-bold">
            <i class="fa-solid fa-circle-info text-base"></i>
            <span>Siap Dicetak: <strong class="text-sky-600 dark:text-sky-400 font-extrabold text-sm">{{ $filteredItems->count() }} Label QR Code</strong></span>
            <span class="text-slate-400 font-normal">| Format Layout: <span id="current-grid-label" class="uppercase font-mono font-bold">{{ str_replace('grid-', '', $gridLayout) }}</span></span>
        </div>
        <div class="text-slate-500 dark:text-slate-400 text-[11px]">
            Estimasi Halaman A4: <strong class="text-slate-900 dark:text-white font-bold">{{ ceil($filteredItems->count() / ($gridLayout === 'grid-4x6' ? 24 : ($gridLayout === 'grid-3x5' ? 15 : ($gridLayout === 'grid-2x4' ? 8 : 1)))) }} Lembar</strong>
        </div>
    </div>

    <!-- 4. AREA PRATINJAU & AREA CETAK A4 (MODERN PRINTABLE AREA) -->
    <div class="print-container">
        @if($filteredItems->isEmpty())
            <div class="no-print p-12 text-center glass-panel rounded-3xl space-y-3">
                <i class="fa-solid fa-qrcode text-4xl text-slate-400"></i>
                <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Tidak Ada Item QR Code yang Dipilih</h3>
                <p class="text-xs text-slate-500">Silakan sesuaikan filter atau centang pilihan item barang di atas.</p>
            </div>
        @else
            <!-- Grid Container QR Code Modern -->
            <div id="printable-qr-grid" class="qr-grid-container {{ $gridLayout }} grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($filteredItems as $item)
                    @php
                        $qrPayload = $item->qr_code_payload ?? ('QR-' . $item->sku);
                        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qrPayload);
                    @endphp
                    
                    <!-- Modern Styled Card -->
                    <div class="qr-card relative p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center flex flex-col items-center justify-between shadow-sm transition hover:shadow-md">
                        
                        <!-- Header Accent Bar (SKU on top right, no INVENTORY CONTROL) -->
                        <div class="qr-card-header w-full flex items-center justify-between pb-1 mb-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="qr-card-seq text-[9px] font-black bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-1.5 py-0.5 rounded">
                                #{{ sprintf('%02d', $item->seq_num ?? $loop->iteration) }}
                            </span>
                            <span class="qr-card-sku font-mono text-[10px] font-black tracking-wider text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/80 px-2 py-0.5 rounded border border-sky-200 dark:border-sky-800">
                                {{ $item->sku }}
                            </span>
                        </div>

                        <!-- High-Density QR Image Container -->
                        <div class="qr-image-wrapper p-1.5 bg-white rounded-xl border border-slate-200 shadow-inner my-0.5">
                            <img src="{{ $qrApiUrl }}" alt="QR {{ $item->sku }}" class="w-24 h-24 sm:w-28 sm:h-28 object-contain mx-auto">
                        </div>

                        <!-- Item Details & Prominent Location Badge -->
                        <div class="w-full space-y-1 mt-1">
                            <div class="qr-card-title text-xs font-bold text-slate-900 dark:text-white truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </div>

                            <!-- Prominent Location Badge (Tanpa Ikon) -->
                            <div class="qr-card-bin py-1 px-2.5 rounded-lg bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 font-black text-xs flex items-center justify-center shadow-sm">
                                <span>RAK: {{ strtoupper($item->location_bin) }}</span>
                            </div>

                            <!-- Tombol Cetak Stiker Mandiri / Thermal Label (No-Print) -->
                            <button onclick="printSingleSticker('{{ addslashes($item->name) }}', '{{ addslashes($item->sku) }}', '{{ $qrApiUrl }}', '{{ addslashes($item->location_bin) }}', '{{ sprintf('%02d', $item->seq_num ?? $loop->iteration) }}')" type="button" title="Cetak Stiker Single untuk Printer Thermal" class="no-print mt-1.5 w-full py-1.5 px-2 text-[10px] font-extrabold rounded-xl bg-sky-500/10 hover:bg-sky-600 text-sky-600 hover:text-white dark:text-sky-400 dark:hover:text-white transition flex items-center justify-center space-x-1 border border-sky-500/20 shadow-sm active:scale-95">
                                <i class="fa-solid fa-print text-xs"></i>
                                <span>Cetak Stiker Single</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function printSingleSticker(name, sku, qrUrl, bin, seqNum) {
        var win = window.open('', '_blank', 'width=450,height=520');
        win.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Stiker Single - ${sku}</title>
                <style>
                    @page {
                        size: auto;
                        margin: 2mm;
                    }
                    body {
                        font-family: 'Inter', system-ui, -apple-system, sans-serif;
                        margin: 0;
                        padding: 10px;
                        text-align: center;
                        background: #ffffff;
                        color: #0f172a;
                    }
                    .sticker-box {
                        border: 2px dashed #0f172a;
                        border-radius: 12px;
                        padding: 12px;
                        display: inline-block;
                        width: 100%;
                        max-width: 260px;
                        box-sizing: border-box;
                    }
                    .header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 1px solid #cbd5e1;
                        padding-bottom: 4px;
                        margin-bottom: 6px;
                    }
                    .seq {
                        font-size: 10px;
                        font-weight: 900;
                        background: #0f172a;
                        color: #ffffff;
                        padding: 2px 6px;
                        border-radius: 4px;
                    }
                    .sku {
                        font-size: 11px;
                        font-family: monospace;
                        font-weight: 900;
                        color: #0369a1;
                        background: #f0f9ff;
                        border: 1px solid #bae6fd;
                        padding: 2px 6px;
                        border-radius: 4px;
                    }
                    img {
                        width: 145px;
                        height: 145px;
                        margin: 4px 0;
                        object-fit: contain;
                    }
                    .title {
                        font-size: 13px;
                        font-weight: 800;
                        margin-top: 4px;
                        color: #0f172a;
                        line-height: 1.2;
                    }
                    .bin-box {
                        font-size: 12px;
                        font-weight: 900;
                        color: #ffffff;
                        background: #0f172a;
                        padding: 4px 10px;
                        border-radius: 6px;
                        display: block;
                        margin-top: 8px;
                        letter-spacing: 0.5px;
                    }
                </style>
            </head>
            <body onload="window.print(); setTimeout(function(){ window.close(); }, 500);">
                <div class="sticker-box">
                    <div class="header">
                        <span class="seq">#${seqNum}</span>
                        <span class="sku">${sku}</span>
                    </div>
                    <img src="${qrUrl}" alt="QR ${sku}">
                    <div class="title">${name}</div>
                    <div class="bin-box">RAK: ${bin.toUpperCase()}</div>
                </div>
            </body>
            </html>
        `);
        win.document.close();
    }

    function toggleModeFields() {
        const mode = document.getElementById('mode-select').value;
        const rangeContainer = document.getElementById('range-fields-container');
        const selectedContainer = document.getElementById('selected-fields-container');

        if (rangeContainer) {
            rangeContainer.classList.toggle('hidden', mode !== 'range');
        }
        if (selectedContainer) {
            selectedContainer.classList.toggle('hidden', mode !== 'selected');
        }
    }

    function selectAllItems(status) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = status;
        });
    }

    function filterItemCheckboxes() {
        const query = document.getElementById('item-search-input').value.toLowerCase();
        const labels = document.querySelectorAll('.item-checkbox-label');

        labels.forEach(label => {
            const name = label.querySelector('.search-name').innerText.toLowerCase();
            const sku = label.querySelector('.search-sku').innerText.toLowerCase();
            if (name.includes(query) || sku.includes(query)) {
                label.style.display = 'flex';
            } else {
                label.style.display = 'none';
            }
        });
    }

    function applyGridLayout() {
        const layout = document.getElementById('grid-layout-select').value;
        const gridContainer = document.getElementById('printable-qr-grid');
        const labelDisplay = document.getElementById('current-grid-label');

        if (gridContainer) {
            gridContainer.className = 'qr-grid-container ' + layout + ' grid gap-4';
            
            if (layout === 'grid-3x5') {
                gridContainer.classList.add('grid-cols-1', 'sm:grid-cols-2', 'md:grid-cols-3');
            } else if (layout === 'grid-4x6') {
                gridContainer.classList.add('grid-cols-2', 'sm:grid-cols-3', 'md:grid-cols-4');
            } else if (layout === 'grid-2x4') {
                gridContainer.classList.add('grid-cols-1', 'sm:grid-cols-2');
            } else {
                gridContainer.classList.add('grid-cols-1');
            }
        }

        if (labelDisplay) {
            labelDisplay.innerText = layout.replace('grid-', '');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleModeFields();
        applyGridLayout();
    });
</script>
@endpush
