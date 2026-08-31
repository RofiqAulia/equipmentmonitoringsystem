@extends('layouts.app')

@section('title', 'Input / Restock Stok Barang - Inventory Control System')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 text-xs font-bold rounded-full border border-cyan-500/30 uppercase tracking-wider">
                    <i class="fa-solid fa-boxes-packing mr-1"></i> Stock Management
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Input & Restock Gudang</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Input / Restock Stok Barang</h1>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Input -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-6">
            
            <!-- Mode Switcher Tabs -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-900/90 rounded-2xl border border-slate-200 dark:border-slate-800">
                <button type="button" onclick="switchMode('existing')" id="tab-existing" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition text-white bg-sky-600 shadow-md">
                    <i class="fa-solid fa-rotate mr-1.5"></i> Restock Barang Existing
                </button>
                <button type="button" onclick="switchMode('new')" id="tab-new" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <i class="fa-solid fa-plus-circle mr-1.5"></i> Tambah Barang Baru
                </button>
            </div>

            <!-- Form with File Upload Support (multipart/form-data) -->
            <form action="{{ route('admin.stock.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="mode" id="form-mode" value="existing">

                <!-- Mode 1: Select Existing Item -->
                <div id="section-existing" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Barang yang Akan Di-Restock *</label>
                        <select name="item_id" id="item_id_select" onchange="onSelectItem(this)" class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                            <option value="">-- Pilih Barang dari Database Gudang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                        data-sku="{{ $item->sku }}"
                                        data-bin="{{ $item->location_bin }}"
                                        data-stock="{{ $item->available_stock }}"
                                        data-min="{{ $item->minimum_stock }}"
                                        data-image="{{ $item->image_url }}"
                                        {{ (request('item_id') == $item->id || (isset($selectedItem) && $selectedItem->id == $item->id)) ? 'selected' : '' }}>
                                    [{{ $item->sku }}] {{ $item->name }} — (Stok Saat Ini: {{ $item->available_stock }} unit | Rak: {{ $item->location_bin }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Mode 2: Input New Item -->
                <div id="section-new" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode SKU Barang *</label>
                            <div class="relative">
                                <input type="text" name="sku" id="sku_input" placeholder="Contoh: SKU-KB-001" class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-cyan-600 dark:text-cyan-400 placeholder-slate-400 focus:outline-none focus:border-cyan-500">
                                <i class="fa-solid fa-barcode absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Barang *</label>
                            <input type="text" name="name" id="name_input" placeholder="Contoh: Keypad Module V2" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500">
                        </div>
                    </div>
                </div>

                <!-- Shared Input Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-slate-200 dark:border-slate-800">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Gudang/Rak *</label>
                        <input type="text" name="location_bin" id="location_bin_input" placeholder="Contoh: Gudang A/10" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1" id="label-quantity">Jumlah Stok Ditambahkan *</label>
                        <input type="number" name="quantity" min="1" value="10" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Batas Stok Minimum *</label>
                        <input type="number" name="minimum_stock" id="minimum_stock_input" min="0" value="5" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <!-- Direct File Upload Dropzone (All Image Types: PNG, JPG, WEBP, GIF, SVG, BMP, HEIC, etc.) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        <i class="fa-solid fa-cloud-arrow-up text-cyan-500 mr-1"></i> Upload File Gambar Barang (Mendukung Semua Jenis Gambar)
                    </label>
                    
                    <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-cyan-500 dark:hover:border-cyan-500 rounded-2xl p-4 transition text-center bg-slate-50 dark:bg-slate-900/50 cursor-pointer group" onclick="document.getElementById('image_file_input').click()">
                        <input type="file" name="image_file" id="image_file_input" accept="image/*,.png,.jpg,.jpeg,.gif,.webp,.svg,.bmp,.heic,.avif" onchange="previewSelectedImage(this)" class="hidden">
                        
                        <div class="space-y-1">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-lg group-hover:scale-110 transition duration-200">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                Klik untuk upload gambar dari perangkat Anda
                            </div>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                Format: PNG, JPG, JPEG, WEBP, GIF, SVG, BMP, HEIC (Maksimal 10MB)
                            </p>
                        </div>
                        <div id="file-name-display" class="mt-2 text-xs font-semibold text-cyan-600 dark:text-cyan-400 hidden"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-2xl shadow-lg shadow-sky-600/25 transition flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2 text-sm"></i> Simpan Input / Restock Stok Barang
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Item Preview Card -->
        <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4 flex items-center">
                    <i class="fa-solid fa-eye text-sky-600 dark:text-sky-400 mr-2"></i> Preview Barang & Gambar
                </h3>

                <div class="text-center p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-3">
                    <img id="preview-image" src="https://placehold.co/150x150/1e293b/0284c7?text=Preview" alt="Item Preview" class="w-28 h-28 mx-auto rounded-2xl object-cover border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-md">
                    
                    <div>
                        <div id="preview-name" class="font-bold text-slate-900 dark:text-white text-sm">Pilih Barang untuk Preview</div>
                        <div id="preview-sku" class="text-xs font-bold text-sky-600 dark:text-sky-400 mt-0.5">SKU: -</div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 text-left text-xs border-t border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Stok Saat Ini:</span>
                            <span id="preview-stock" class="font-black text-slate-900 dark:text-white text-sm">0 unit</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Min Threshold:</span>
                            <span id="preview-min" class="font-bold text-amber-500 text-sm">0 unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-xs text-sky-700 dark:text-sky-300 space-y-1">
                <div class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> Informasi Direct Upload</div>
                <p class="text-[11px] text-slate-600 dark:text-slate-400">
                    File gambar yang diupload langsung disimpan secara aman ke server lokal gudang (`public/uploads/items`) tanpa memerlukan tautan URL eksternal.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePreviewState() {
        const mode = document.getElementById('form-mode').value;
        const fileInput = document.getElementById('image_file_input');
        const hasUploadedFile = fileInput && fileInput.files && fileInput.files[0];
        const defaultPlaceholder = 'https://placehold.co/150x150/1e293b/0284c7?text=Preview';

        if (mode === 'new') {
            const skuVal = document.getElementById('sku_input').value.trim();
            const nameVal = document.getElementById('name_input').value.trim();
            const qtyVal = document.querySelector('input[name="quantity"]').value;
            const minVal = document.getElementById('minimum_stock_input').value;

            document.getElementById('preview-name').innerText = nameVal || 'Input Barang Baru';
            document.getElementById('preview-sku').innerText = 'SKU: ' + (skuVal || '-');
            document.getElementById('preview-stock').innerText = (qtyVal || '0') + ' unit (Stok Awal)';
            document.getElementById('preview-min').innerText = (minVal || '0') + ' unit';

            if (!hasUploadedFile) {
                document.getElementById('preview-image').src = 'https://placehold.co/150x150/1e293b/0284c7?text=Barang+Baru';
            }
        } else {
            // Mode existing
            const select = document.getElementById('item_id_select');
            if (select && select.value) {
                const option = select.options[select.selectedIndex];
                const name = option.text.split('—')[0].trim();
                const sku = option.getAttribute('data-sku');
                const stock = option.getAttribute('data-stock');
                const min = option.getAttribute('data-min');
                const image = option.getAttribute('data-image');
                const addedQty = document.querySelector('input[name="quantity"]').value || 0;

                document.getElementById('preview-name').innerText = name;
                document.getElementById('preview-sku').innerText = 'SKU: ' + (sku || '-');
                
                const currentStockNum = parseInt(stock) || 0;
                const addedNum = parseInt(addedQty) || 0;
                const totalEst = currentStockNum + addedNum;

                document.getElementById('preview-stock').innerHTML = `${currentStockNum} unit <span class="text-[10px] text-emerald-500 font-bold block">(+${addedNum} Restock → ${totalEst} unit)</span>`;
                document.getElementById('preview-min').innerText = (min || '0') + ' unit';

                if (!hasUploadedFile) {
                    document.getElementById('preview-image').src = image || 'https://placehold.co/150x150/1e293b/06b6d4?text=No+Photo';
                }
            } else {
                // No item selected
                document.getElementById('preview-name').innerText = 'Pilih Barang untuk Preview';
                document.getElementById('preview-sku').innerText = 'SKU: -';
                document.getElementById('preview-stock').innerText = '0 unit';
                document.getElementById('preview-min').innerText = '0 unit';
                if (!hasUploadedFile) {
                    document.getElementById('preview-image').src = defaultPlaceholder;
                }
            }
        }
    }

    function switchMode(mode) {
        document.getElementById('form-mode').value = mode;
        const tabExisting = document.getElementById('tab-existing');
        const tabNew = document.getElementById('tab-new');
        const secExisting = document.getElementById('section-existing');
        const secNew = document.getElementById('section-new');
        const labelQty = document.getElementById('label-quantity');

        if (mode === 'existing') {
            tabExisting.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold transition text-white bg-sky-600 shadow-md';
            tabNew.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
            secExisting.classList.remove('hidden');
            secNew.classList.add('hidden');
            labelQty.innerText = 'Jumlah Stok Ditambahkan *';
        } else {
            tabNew.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold transition text-white bg-sky-600 shadow-md';
            tabExisting.className = 'flex-1 py-2.5 rounded-xl text-xs font-bold transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
            secNew.classList.remove('hidden');
            secExisting.classList.add('hidden');
            labelQty.innerText = 'Stok Awal Barang *';
        }
        updatePreviewState();
    }

    function previewSelectedImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            };
            
            reader.readAsDataURL(file);

            const display = document.getElementById('file-name-display');
            if (display) {
                display.innerText = 'File Terpilih: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                display.classList.remove('hidden');
            }
        } else {
            const display = document.getElementById('file-name-display');
            if (display) display.classList.add('hidden');
            updatePreviewState();
        }
    }

    function onSelectItem(select) {
        const option = select.options[select.selectedIndex];
        if (option && option.value) {
            const bin = option.getAttribute('data-bin');
            const min = option.getAttribute('data-min');
            if (bin) document.getElementById('location_bin_input').value = bin;
            if (min) document.getElementById('minimum_stock_input').value = min;
        }
        updatePreviewState();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputs = [
            document.getElementById('sku_input'),
            document.getElementById('name_input'),
            document.getElementById('minimum_stock_input'),
            document.querySelector('input[name="quantity"]')
        ];

        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', updatePreviewState);
            }
        });

        const select = document.getElementById('item_id_select');
        if (select && select.value) {
            onSelectItem(select);
        } else {
            updatePreviewState();
        }
    });
</script>
@endpush
