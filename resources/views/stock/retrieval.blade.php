@extends('layouts.app')

@section('title', 'Ambil Barang Gudang - Inventory Control System')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Page Header & Active SPV Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-qrcode text-cyan-600 dark:text-cyan-400 mr-3"></i> Modul Pengambilan Barang Gudang
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pindai Kode QR / SKU untuk melihat ketersediaan stok & memproses pengambilan.</p>
        </div>

        <!-- Supervisor Selection Widget -->
        <div class="bg-slate-100/90 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700/80 rounded-2xl p-3 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-semibold">Supervisor Penanggung Jawab:</div>
                <div id="spv-display-name" class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                    {{ Auth::user()->supervisor ? Auth::user()->supervisor->name : 'Belum Terhubung' }}
                </div>
            </div>
            <button onclick="openSpvModal()" type="button" class="ml-2 px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-semibold text-cyan-600 dark:text-cyan-400 rounded-xl border border-cyan-500/30 transition">
                <i class="fa-solid fa-arrows-rotate mr-1"></i> Ubah SPV
            </button>
        </div>
    </div>

    <!-- Quick Sample Barcode / SKU Test Shortcuts -->
    <div class="glass-card p-4 rounded-2xl">
        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 flex items-center">
            <i class="fa-solid fa-bolt text-amber-500 dark:text-amber-400 mr-1.5"></i> Shortcut Pengujian Pindai SKU / QR Payload:
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="autoScanPayload('SKU-ELEK-001')" type="button" class="px-3 py-1 bg-slate-100 dark:bg-slate-800/80 hover:bg-cyan-500/20 hover:text-cyan-600 dark:hover:text-cyan-300 text-slate-700 dark:text-slate-300 rounded-lg text-xs border border-slate-300 dark:border-slate-700 font-mono transition">
                SKU-ELEK-001 (In Stock: 42)
            </button>
            <button onclick="autoScanPayload('SKU-CABL-003')" type="button" class="px-3 py-1 bg-slate-100 dark:bg-slate-800/80 hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-300 text-slate-700 dark:text-slate-300 rounded-lg text-xs border border-slate-300 dark:border-slate-700 font-mono transition">
                SKU-CABL-003 (Low Stock)
            </button>
            <button onclick="autoScanPayload('SKU-PAL-004')" type="button" class="px-3 py-1 bg-slate-100 dark:bg-slate-800/80 hover:bg-rose-500/20 hover:text-rose-600 dark:hover:text-rose-300 text-slate-700 dark:text-slate-300 rounded-lg text-xs border border-slate-300 dark:border-slate-700 font-mono transition">
                SKU-PAL-004 (Out of Stock: 0)
            </button>
        </div>
    </div>

    <!-- Scanner Input & Transaction Card Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Scan Payload Form -->
        <div class="lg:col-span-5 space-y-6">
            <div class="glass-panel p-6 rounded-3xl space-y-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-barcode text-cyan-600 dark:text-cyan-400 mr-2"></i> Pindai / Input SKU
                </h2>

                <form id="scan-form" onsubmit="handleScanItem(event)" class="space-y-4">
                    <div>
                        <label for="qr_payload" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Payload QR / Code SKU</label>
                        <div class="relative">
                            <input type="text" id="qr_payload" required placeholder="Contoh: SKU-ELEK-001..." 
                                   class="w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-mono text-cyan-700 dark:text-cyan-300 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                            <button type="submit" title="Scan" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-cyan-600 dark:text-cyan-400 hover:opacity-75">
                                <i class="fa-solid fa-magnifying-glass text-base"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-cyan-600/20 transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-camera"></i>
                        <span>Cari Detail Barang</span>
                    </button>
                </form>

                <!-- Scan Status Loading / Notification -->
                <div id="scan-feedback" class="hidden p-3 rounded-xl text-xs font-medium"></div>
            </div>
        </div>

        <!-- Right Column: Scanned Item Preview & Retrieval Form -->
        <div class="lg:col-span-7">
            <!-- Empty State Placeholder -->
            <div id="empty-state" class="glass-panel p-10 rounded-3xl text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-200 dark:bg-slate-800/80 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto text-2xl border border-slate-300 dark:border-slate-700/50">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300">Belum Ada Barang Dipindai</h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs max-w-sm mx-auto">Silakan masukkan payload QR atau klik salah satu shortcut SKU di atas untuk menampilkan detail stok & form pengambilan.</p>
            </div>

            <!-- Item Detail & Retrieval Form Container (Hidden initially) -->
            <div id="item-detail-card" class="hidden glass-panel p-6 rounded-3xl space-y-6">
                <!-- Item Info Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 pb-6 border-b border-slate-200 dark:border-slate-800">
                    <img id="item-image" src="" alt="Foto Barang" class="w-24 h-24 rounded-2xl object-cover bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700">
                    <div class="space-y-1 flex-1">
                        <div class="flex items-center space-x-2">
                            <span id="item-sku-badge" class="px-2.5 py-0.5 rounded-md text-xs font-mono font-bold bg-cyan-500/20 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30"></span>
                            <span id="item-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                        </div>
                        <h3 id="item-name" class="text-lg font-bold text-slate-900 dark:text-white"></h3>
                        <p id="item-location" class="text-xs text-slate-500 dark:text-slate-400 flex items-center">
                            <i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i> Lokasi Rak: <span class="font-semibold text-slate-800 dark:text-slate-200 ml-1"></span>
                        </p>
                    </div>
                </div>

                <!-- Stock Counter KPI -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-100 dark:bg-slate-900/80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Stok Tersedia</div>
                        <div id="item-available-stock" class="text-2xl font-black text-slate-900 dark:text-white mt-1">0</div>
                    </div>
                    <div class="bg-slate-100 dark:bg-slate-900/80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Ambang Minimum</div>
                        <div id="item-minimum-stock" class="text-2xl font-black text-amber-500 dark:text-amber-400 mt-1">0</div>
                    </div>
                </div>

                <!-- Retrieval Confirmation Form -->
                <form id="retrieval-form" onsubmit="handleConfirmRetrieval(event)" class="space-y-4 pt-2">
                    <input type="hidden" id="retrieval-item-id">

                    <div>
                        <label for="quantity_picked" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Jumlah Pengambilan (Unit)</label>
                        <input type="number" id="quantity_picked" min="1" required value="1"
                               class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-base font-bold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Transaksi / Keperluan (Opsional)</label>
                        <textarea id="notes" rows="2" placeholder="Contoh: Pengambilan untuk restock lini perakitan B..."
                                  class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition"></textarea>
                    </div>

                    <button type="submit" id="btn-confirm-retrieval" class="w-full py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-sm shadow-xl shadow-sky-600/20 transition transform active:scale-[0.98]">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> Konfirmasi Pengambilan & Kurangi Stok
                    </button>
                </form>
            </div>
        </div>
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
@endsection

@push('scripts')
<script>
    window.SELECTED_SPV_ID = "{{ Auth::user()->supervisor_id ?? '' }}";
    window.RETRIEVAL_ROUTES = {
        scan: "{{ route('stock.scan') }}",
        confirm: "{{ route('stock.confirm') }}",
        selectSpv: "{{ route('user.select-spv') }}"
    };
</script>
<script src="{{ asset('js/retrieval.js') }}"></script>
@endpush
