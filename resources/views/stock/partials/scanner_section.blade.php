<!-- Column 1: Live Camera Scan Trigger Panel -->
<div class="glass-panel p-6 rounded-3xl space-y-4 text-center border border-cyan-500/30 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent flex flex-col justify-between h-full">
    <div class="space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mx-auto text-2xl shadow-lg shadow-cyan-500/10">
            <i class="fa-solid fa-camera"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Pindai dengan Kamera Device / HP</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Arahkan kamera ke QR Code yang telah diterbitkan oleh Admin gudang.</p>
        </div>
    </div>
    
    <button type="button" onclick="startCameraScanner()" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-sky-600 hover:from-cyan-500 hover:to-sky-500 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-cyan-600/25 transition transform active:scale-95 flex items-center justify-center space-x-2">
        <i class="fa-solid fa-qrcode text-sm"></i>
        <span>Buka Kamera QR Scanner</span>
    </button>
</div>

<!-- Column 2: Manual Input SKU / Payload Form -->
<div class="glass-panel p-6 rounded-3xl space-y-4 flex flex-col justify-between h-full">
    <div class="space-y-4">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center">
            <i class="fa-solid fa-barcode text-cyan-600 dark:text-cyan-400 mr-2"></i> Input Manual / Select Barang
        </h2>

        <!-- Dropdown Select Barang Langsung -->
        <div>
            <label for="select-item-dropdown" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                <i class="fa-solid fa-magnifying-glass text-cyan-600 dark:text-cyan-400 mr-1"></i> Cari & Pilih Barang dari Database Gudang:
            </label>
            <select id="select-item-dropdown" class="select2-searchable w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition">
                <option value="">-- Cari atau Pilih Barang untuk Pengambilan --</option>
                @foreach(\App\Models\Item::orderBy('name', 'asc')->get() as $selectItem)
                    <option value="{{ $selectItem->qr_code_payload }}">
                        [{{ $selectItem->sku }}] {{ $selectItem->name }} (Stok: {{ $selectItem->available_stock }} unit)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="relative flex items-center py-1">
            <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
            <span class="flex-shrink mx-2 text-[10px] uppercase font-bold text-slate-400">Atau Ketik Manual Payload</span>
            <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
        </div>

        <form id="scan-form" onsubmit="handleScanItem(event)" class="space-y-4">
            <div>
                <label for="qr_payload" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Payload QR Code / Code SKU</label>
                <div class="relative">
                    <input type="text" id="qr_payload" required placeholder="Contoh: SKU-ELEK-001..." 
                           class="w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-mono text-cyan-700 dark:text-cyan-300 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                    <button type="submit" title="Cari Data Barang" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-cyan-600 dark:text-cyan-400 hover:opacity-75">
                        <i class="fa-solid fa-magnifying-glass text-base"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs shadow transition flex items-center justify-center space-x-2">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Cari Detail Barang</span>
            </button>
        </form>

        <!-- Scan Status Loading / Notification -->
        <div id="scan-feedback" class="hidden p-3 rounded-xl text-xs font-medium"></div>
    </div>
</div>
