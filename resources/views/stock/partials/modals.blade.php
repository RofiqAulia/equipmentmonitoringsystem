<!-- Modal Camera QR Scanner Live Viewport -->
<div id="camera-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl text-center my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-camera text-cyan-600 dark:text-cyan-400 mr-2"></i> Kamera QR Scanner Active
            </h3>
            <button onclick="stopCameraScanner()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div id="qr-reader" class="w-full rounded-2xl overflow-hidden border-2 border-cyan-500/40 bg-black min-h-[260px]"></div>

        <p class="text-xs text-slate-400">Arahkan kamera perangkat Anda tepat pada gambar QR Code yang diterbitkan Admin.</p>

        <button onclick="stopCameraScanner()" type="button" class="w-full py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">
            Tutup Kamera
        </button>
    </div>
</div>

<!-- Modal Dialog Update SPV Penanggung Jawab -->
<div id="spv-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center"><i class="fa-solid fa-user-shield text-cyan-600 dark:text-cyan-400 mr-2"></i> Pilih SPV Penanggung Jawab</h3>
            <button onclick="closeSpvModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div>
            <label class="block text-xs text-slate-600 dark:text-slate-300 mb-2">Pilih Supervisor aktif yang menyetujui transaksi ini:</label>
            <select id="spv-select-option" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                @foreach(\App\Models\User::where('role', 'spv')->orderBy('name', 'asc')->get() as $spv)
                    <option value="{{ $spv->id }}" {{ Auth::user()->supervisor_id == $spv->id ? 'selected' : '' }}>{{ $spv->name }} ({{ $spv->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="flex space-x-3 pt-2">
            <button onclick="closeSpvModal()" type="button" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">Batal</button>
            <button onclick="saveSelectedSpv()" type="button" class="flex-1 py-2.5 bg-cyan-600 text-white rounded-xl text-xs font-bold hover:bg-cyan-500 transition shadow">Simpan SPV</button>
        </div>
    </div>
</div>

<!-- Modal Dialog Form Pengambilan Barang Ditemukan (Direct Popup On Scan) -->
<div id="item-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-lg w-full p-6 sm:p-7 rounded-3xl border border-cyan-500/40 bg-white dark:bg-slate-900 space-y-5 shadow-2xl relative my-auto max-h-[90vh] overflow-y-auto">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-box-archive text-cyan-600 dark:text-cyan-400 mr-2 text-lg"></i> Detail Barang Ditemukan
            </h3>
            <button onclick="closeItemModal()" type="button" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-rose-500/20 hover:text-rose-500 text-slate-400 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Detail Item (Foto, SKU, Nama, Lokasi) -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800">
            <img id="modal-item-image" src="" alt="Foto Barang" class="w-24 h-24 rounded-2xl object-cover bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-md shrink-0">
            <div class="space-y-1 text-center sm:text-left flex-1 min-w-0">
                <div class="flex items-center justify-center sm:justify-start space-x-2 flex-wrap gap-y-1">
                    <span id="modal-item-sku-badge" class="px-2.5 py-0.5 rounded-md text-xs font-mono font-bold bg-cyan-500/20 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30"></span>
                    <span id="modal-item-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                </div>
                <h4 id="modal-item-name" class="text-base font-bold text-slate-900 dark:text-white truncate"></h4>
                <p id="modal-item-location" class="text-xs text-slate-500 dark:text-slate-400 flex items-center justify-center sm:justify-start">
                    <i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> Lokasi Gudang/Rak: <span class="font-bold text-slate-800 dark:text-slate-200 ml-1"></span>
                </p>
            </div>
        </div>

        <!-- Stok KPI Indicator -->
        <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 text-center">
            <div class="text-xs text-emerald-800 dark:text-emerald-300 font-extrabold uppercase tracking-wider">Stok Tersedia Saat Ini</div>
            <div id="modal-item-available-stock" class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">0</div>
        </div>

        <!-- Form Pengambilan Transaksi Modal -->
        <form id="modal-retrieval-form" onsubmit="handleConfirmRetrieval(event)" class="space-y-4 pt-1">
            <input type="hidden" id="modal-retrieval-item-id">

            <div>
                <label for="modal_quantity_picked" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Pengambilan Barang (Unit) *</label>
                <div class="flex items-stretch w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden focus-within:border-cyan-500 focus-within:ring-1 focus-within:ring-cyan-500 transition shadow-sm">
                    <button type="button" onclick="adjustModalQty(-1)" class="px-4 sm:px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-black text-base transition flex items-center justify-center shrink-0 border-r border-slate-200 dark:border-slate-800 active:scale-95">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    
                    <input type="number" id="modal_quantity_picked" min="1" required value="1"
                           class="flex-1 min-w-0 px-2 py-3 bg-transparent border-0 text-center text-xl font-black text-cyan-600 dark:text-cyan-400 focus:outline-none focus:ring-0">
                    
                    <button type="button" onclick="adjustModalQty(1)" class="px-4 sm:px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-black text-base transition flex items-center justify-center shrink-0 border-l border-slate-200 dark:border-slate-800 active:scale-95">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="modal_notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Transaksi / Keperluan (Opsional)</label>
                <textarea id="modal_notes" rows="2" placeholder="Contoh: Ambil barang untuk perbaikan unit di area produksi A..."
                          class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition"></textarea>
            </div>

            <div class="flex space-x-3 pt-2">
                <button type="button" onclick="closeItemModal()" class="w-1/3 py-3.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-xs transition">
                    Batal
                </button>
                <button type="submit" id="modal-btn-confirm-retrieval" class="w-2/3 py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Konfirmasi & Kurangi Stok</span>
                </button>
            </div>
        </form>
    </div>
</div>
