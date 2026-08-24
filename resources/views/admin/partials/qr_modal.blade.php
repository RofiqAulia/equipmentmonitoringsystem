<!-- Modal Terbitkan QR Code untuk Operator -->
<div id="admin-qr-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-sm w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl text-center my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-qrcode text-cyan-600 dark:text-cyan-400 mr-2"></i> Penerbitan QR Code Barang
            </h3>
            <button onclick="closeQrModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-inner inline-block mx-auto">
            <img id="qr-modal-image" src="" alt="QR Code Barang" class="w-48 h-48 mx-auto object-contain">
        </div>

        <div>
            <h4 id="qr-modal-title" class="font-extrabold text-slate-900 dark:text-white text-base"></h4>
            <div id="qr-modal-sku" class="text-xs font-mono text-cyan-600 dark:text-cyan-400 font-bold mt-0.5"></div>
            <div id="qr-modal-bin" class="text-xs text-slate-500 dark:text-slate-400 mt-1"></div>
        </div>

        <div class="p-3 bg-cyan-500/10 rounded-xl border border-cyan-500/20 text-[11px] text-cyan-700 dark:text-cyan-300 text-left">
            <i class="fa-solid fa-circle-info mr-1"></i> Tampilkan layar ini atau cetak QR Code ini untuk di-scan oleh Operator pada modul pengambilan barang.
        </div>

        <div class="flex space-x-2 pt-2">
            <button onclick="closeQrModal()" type="button" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">Tutup</button>
            <button onclick="printQrCode()" type="button" class="flex-1 py-2.5 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-500 transition shadow">
                <i class="fa-solid fa-print mr-1"></i> Cetak QR
            </button>
        </div>
    </div>
</div>
