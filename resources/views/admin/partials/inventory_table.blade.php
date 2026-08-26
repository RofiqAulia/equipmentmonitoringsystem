<!-- DataTables Table 2: Inventory Master Table -->
<div class="glass-panel p-6 rounded-3xl space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-table-list text-cyan-600 dark:text-cyan-400 mr-2"></i> Master Data Inventaris Gudang (DataTables Engine)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Wrap Text, Filtering ASC/DESC, dan Row Grouping Dinamis</p>
        </div>

        <!-- Server Filter & Interactive Grouping Controls -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Dynamic Grouping Dropdown -->
            <!-- <div class="flex items-center space-x-2">
                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 flex items-center">
                    <i class="fa-solid fa-layer-group text-cyan-500 mr-1.5"></i> Grouping:
                </label>
                <select id="group-inventory-select" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 shadow-sm cursor-pointer">
                    <option value="-1">Tanpa Grouping</option>
                    <option value="3">Group per Lokasi Gudang/Rak</option>
                    <option value="6">Group per Status Stok</option>
                </select>
            </div> -->

            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
                    <option value="">Semua Status Stok</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock (Aman)</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock (Menipis)</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (Habis)</option>
                </select>

                <select name="per_page" onchange="this.form.submit()" class="px-3.5 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 / Halaman</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 / Halaman</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 / Halaman</option>
                    <option value="-1" {{ request('per_page') == '-1' ? 'selected' : '' }}>Tampilkan Semua</option>
                </select>

                @if(request('search') || request('status') || request('per_page'))
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition" title="Reset Filter">
                        <i class="fa-solid fa-xmark mr-1"></i> Reset
                    </a>
                @endif
            </form>

            <!-- Cetak Laporan Button -->
            <a href="{{ route('admin.stock.print', ['status' => request('status'), 'search' => request('search'), 'auto_print' => 1]) }}" target="_blank" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5" title="Cetak Laporan Inventaris Gudang">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <!-- Dynamic DataTables Container with Clean Wrap Text -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
        <table id="inventoryTable" class="w-full min-w-[880px] align-middle text-left text-xs text-slate-700 dark:text-slate-300 display">
            <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-3.5 w-14 no-sort">Foto</th>
                    <th class="px-4 py-3.5 cursor-pointer sorting">SKU / QR Payload</th>
                    <th class="px-4 py-3.5 min-w-[200px] cursor-pointer sorting">Nama Barang</th>
                    <th class="px-4 py-3.5 cursor-pointer sorting">Lokasi Gudang/Rak</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Stok Available</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Min Threshold</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Status Stok</th>
                    <th class="px-4 py-3.5 text-center no-sort">Aksi QR Code</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
                        <td class="px-4 py-3 w-14">
                            <img src="{{ $item->image_url ?? 'https://placehold.co/100x100/1e293b/06b6d4?text=No+Photo' }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                        </td>
                        <td class="px-4 py-3 break-words max-w-[160px]">
                            <div class="font-bold text-cyan-600 dark:text-cyan-400 text-xs">{{ $item->sku }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 leading-snug">{{ $item->qr_code_payload }}</div>
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white break-words max-w-[240px] leading-relaxed">
                            {{ $item->name }}
                        </td>
                        <td class="px-4 py-3 break-words max-w-[140px] font-medium text-slate-700 dark:text-slate-300">
                            {{ $item->location_bin }}
                        </td>
                        <td class="px-4 py-3 text-center font-black text-sm text-slate-900 dark:text-white" data-order="{{ $item->available_stock }}">
                            {{ $item->available_stock }}
                        </td>
                        <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400 font-semibold" data-order="{{ $item->minimum_stock }}">
                            {{ $item->minimum_stock }}
                        </td>
                        <td class="px-4 py-3 text-center" data-order="{{ $item->available_stock <= 0 ? 0 : ($item->available_stock <= $item->minimum_stock ? 1 : 2) }}">
                            @if($item->available_stock <= 0)
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 uppercase tracking-wider shadow-sm">OUT OF STOCK</span>
                            @elseif($item->available_stock <= $item->minimum_stock)
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 uppercase tracking-wider shadow-sm">LOW STOCK</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 uppercase tracking-wider shadow-sm">IN STOCK</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" onclick="openQrModal('{{ $item->name }}', '{{ $item->sku }}', '{{ $item->qr_code_payload }}', '{{ $item->location_bin }}')" class="px-3 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 font-bold text-xs rounded-xl border border-cyan-500/30 transition flex items-center justify-center mx-auto shadow-sm">
                                <i class="fa-solid fa-qrcode mr-1.5"></i> Terbitkan QR
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">Tidak ada data barang inventaris yang sesuai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
