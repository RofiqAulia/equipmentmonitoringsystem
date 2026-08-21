<!-- Page Header & Active SPV Banner -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center">
            <i class="fa-solid fa-qrcode text-cyan-600 dark:text-cyan-400 mr-3"></i> Modul Pengambilan Barang Gudang
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pindai Kode QR yang diterbitkan Admin atau input SKU untuk memproses transaksi pengurangan stok.</p>
    </div>

    <!-- Supervisor Selection Widget -->
    <div class="bg-slate-100/90 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700/80 rounded-2xl p-3 flex items-center space-x-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div>
            <div class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">Supervisor Penanggung Jawab:</div>
            <div id="spv-display-name" class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                {{ Auth::user()->supervisor ? Auth::user()->supervisor->name : (Auth::user()->name ?? 'Belum Terhubung') }}
            </div>
        </div>
        <button onclick="openSpvModal()" type="button" class="ml-2 px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-semibold text-cyan-600 dark:text-cyan-400 rounded-xl border border-cyan-500/30 transition">
            <i class="fa-solid fa-arrows-rotate mr-1"></i> Ubah SPV
        </button>
    </div>
</div>
