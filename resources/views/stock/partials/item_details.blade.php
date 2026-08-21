<!-- Column 3: Scanned Item Preview & Retrieval Form -->
<div class="h-full flex flex-col justify-between">
    <!-- Empty State Placeholder -->
    <div id="empty-state" class="glass-panel p-6 sm:p-8 rounded-3xl text-center space-y-3 h-full flex flex-col items-center justify-center min-h-[320px]">
        <div class="w-16 h-16 rounded-2xl bg-slate-200 dark:bg-slate-800/80 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto text-2xl border border-slate-300 dark:border-slate-700/50">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800 dark:text-slate-300">Belum Ada Barang Dipindai</h3>
        <p class="text-slate-500 dark:text-slate-400 text-xs max-w-sm mx-auto leading-relaxed">Gunakan kamera atau pilih barang dari dropdown di sebelah kiri untuk menampilkan detail stok & memproses transaksi pengambilan.</p>
    </div>

    <!-- Item Detail & Retrieval Form Container (Hidden initially) -->
    <div id="item-detail-card" class="hidden glass-panel p-6 rounded-3xl space-y-6 h-full flex flex-col justify-between">
        <!-- Item Info Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <img id="item-image" src="" alt="Foto Barang" class="w-24 h-24 rounded-2xl object-cover bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-md">
            <div class="space-y-1 flex-1">
                <div class="flex items-center space-x-2">
                    <span id="item-sku-badge" class="px-2.5 py-0.5 rounded-md text-xs font-mono font-bold bg-cyan-500/20 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30"></span>
                    <span id="item-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                </div>
                <h3 id="item-name" class="text-lg font-bold text-slate-900 dark:text-white"></h3>
                <p id="item-location" class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
                    <i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> Lokasi Rak Gudang: <span class="font-bold text-slate-800 dark:text-slate-200 ml-1"></span>
                </p>
            </div>
        </div>

        <!-- Stock Counter KPI -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-100 dark:bg-slate-900/80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Stok Tersedia Saat Ini</div>
                <div id="item-available-stock" class="text-3xl font-black text-slate-900 dark:text-white mt-1">0</div>
            </div>
            <div class="bg-slate-100 dark:bg-slate-900/80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ambang Minimum Stok</div>
                <div id="item-minimum-stock" class="text-3xl font-black text-amber-500 dark:text-amber-400 mt-1">0</div>
            </div>
        </div>

        <!-- Retrieval Confirmation Form -->
        <form id="retrieval-form" onsubmit="handleConfirmRetrieval(event)" class="space-y-5 pt-2">
            <input type="hidden" id="retrieval-item-id">

            <div>
                <label for="quantity_picked" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Pengambilan Barang (Unit) *</label>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="adjustQty(-1)" class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-black text-lg flex items-center justify-center transition">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    
                    <input type="number" id="quantity_picked" min="1" required value="1"
                           class="flex-1 px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-center text-xl font-black text-cyan-600 dark:text-cyan-400 focus:outline-none focus:border-cyan-500 transition">
                    
                    <button type="button" onclick="adjustQty(1)" class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-black text-lg flex items-center justify-center transition">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Transaksi / Keperluan (Opsional)</label>
                <textarea id="notes" rows="2" placeholder="Contoh: Ambil barang untuk perbaikan unit di area produksi A..."
                          class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition"></textarea>
            </div>

            <button type="submit" id="btn-confirm-retrieval" class="w-full py-4 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-2xl text-sm shadow-xl shadow-sky-600/25 transition transform active:scale-[0.98]">
                <i class="fa-solid fa-circle-check mr-2 text-base"></i> Konfirmasi Pengambilan & Kurangi Stok Database
            </button>
        </form>
    </div>
</div>
