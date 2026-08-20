<!-- Modal Camera QR Scanner Live Viewport -->
<div id="camera-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl text-center">
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
<div id="spv-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center"><i class="fa-solid fa-user-shield text-cyan-600 dark:text-cyan-400 mr-2"></i> Pilih SPV Penanggung Jawab</h3>
            <button onclick="closeSpvModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div>
            <label class="block text-xs text-slate-600 dark:text-slate-300 mb-2">Pilih Supervisor aktif yang menyetujui transaksi ini:</label>
            <select id="spv-select-option" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500">
                @foreach(\App\Models\User::where('role', 'admin')->get() as $spv)
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
