<!-- DataTables Table 2: Inventory Master Table -->
<div class="glass-panel p-6 rounded-3xl space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-table-list text-cyan-600 dark:text-cyan-400 mr-2"></i> Master Data Inventaris Gudang (DataTables Engine)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Wrap Text, Filtering ASC/DESC, dan Row Grouping Dinamis</p>
        </div>

        <!-- Server Filter & Interactive Controls -->
        <div class="flex flex-wrap items-center gap-3">
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
        </div>
    </div>

    <!-- Dynamic DataTables Container with Clean Wrap Text -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
        <table id="inventoryTable" class="w-full min-w-[960px] align-middle text-left text-xs text-slate-700 dark:text-slate-300 display">
            <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-3.5 w-14 no-sort">Foto</th>
                    <th class="px-4 py-3.5 cursor-pointer sorting">SKU / QR Payload</th>
                    <th class="px-4 py-3.5 min-w-[180px] cursor-pointer sorting">Nama Barang</th>
                    <th class="px-4 py-3.5 cursor-pointer sorting">Lokasi Gudang/Rak</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Stok Available</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Min Threshold</th>
                    <th class="px-4 py-3.5 text-center cursor-pointer sorting">Status Stok</th>
                    <th class="px-4 py-3.5 text-center no-sort">Aksi QR Code</th>
                    <th class="px-4 py-3.5 text-center no-sort min-w-[130px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
                        <td class="px-4 py-3 w-14">
                            <img src="{{ $item->image_url ?? 'https://placehold.co/100x100/1e293b/06b6d4?text=No+Photo' }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                        </td>
                        <td class="px-4 py-3 break-words max-w-[150px]">
                            <div class="font-bold text-cyan-600 dark:text-cyan-400 text-xs">{{ $item->sku }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 leading-snug">{{ $item->qr_code_payload }}</div>
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white break-words max-w-[220px] leading-relaxed">
                            {{ $item->name }}
                        </td>
                        <td class="px-4 py-3 break-words max-w-[130px] font-medium text-slate-700 dark:text-slate-300">
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
                        <!-- Kolom 8: Aksi QR Code -->
                        <td class="px-4 py-3 text-center">
                            <button type="button" onclick="openQrModal('{{ addslashes($item->name) }}', '{{ $item->sku }}', '{{ $item->qr_code_payload }}', '{{ $item->location_bin }}')" class="px-3 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 font-bold text-xs rounded-xl border border-cyan-500/30 transition flex items-center justify-center mx-auto shadow-sm">
                                <i class="fa-solid fa-qrcode mr-1.5"></i> Terbitkan QR
                            </button>
                        </td>
                        <!-- Kolom 9: Aksi (Detail, Edit, Hapus) -->
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Lihat Detail (Modal Pop-up) -->
                                <button type="button" onclick='openDetailModal(@json($item))' class="p-2 bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 rounded-xl border border-sky-500/30 transition shadow-sm" title="Lihat Detail Barang">
                                    <i class="fa-solid fa-circle-info text-xs"></i>
                                </button>
                                <!-- Edit Barang (Modal Concept) -->
                                <button type="button" onclick='openEditModal(@json($item))' class="p-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-xl border border-amber-500/30 transition shadow-sm" title="Edit Barang">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <!-- Hapus Barang (Notifikasi Pop-up Konfirmasi) -->
                                <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->sku }}')" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-500/30 transition shadow-sm" title="Hapus Barang">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-slate-500">Tidak ada data barang inventaris yang sesuai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal 1: Detail Barang (Pop Up View) -->
<div id="detail-item-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-lg w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-5 shadow-2xl my-auto max-h-[90vh] overflow-y-auto text-left">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-boxes-stacked text-cyan-600 dark:text-cyan-400 mr-2"></i> Detail Inventaris Barang
            </h3>
            <button onclick="closeDetailModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <img id="detail-item-image" src="" alt="Foto Barang" class="w-24 h-24 rounded-2xl object-cover border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-md">
            <div class="space-y-1 text-center sm:text-left flex-1">
                <h4 id="detail-item-name" class="text-lg font-extrabold text-slate-900 dark:text-white"></h4>
                <div class="text-xs font-mono text-cyan-600 dark:text-cyan-400 font-bold" id="detail-item-sku"></div>
                <div id="detail-item-status" class="pt-1"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="p-3 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-slate-400 uppercase text-[10px] font-bold">Lokasi Gudang / Rak</div>
                <div id="detail-item-location" class="font-extrabold text-slate-900 dark:text-white text-sm mt-0.5"></div>
            </div>
            <div class="p-3 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-slate-400 uppercase text-[10px] font-bold">QR Payload</div>
                <div id="detail-item-qr" class="font-mono font-bold text-slate-700 dark:text-slate-300 text-xs mt-0.5 truncate"></div>
            </div>
            <div class="p-3 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-slate-400 uppercase text-[10px] font-bold">Stok Available</div>
                <div id="detail-item-stock" class="font-black text-slate-900 dark:text-white text-base mt-0.5"></div>
            </div>
            <div class="p-3 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-slate-400 uppercase text-[10px] font-bold">Minimum Threshold</div>
                <div id="detail-item-min" class="font-extrabold text-slate-900 dark:text-white text-base mt-0.5"></div>
            </div>
        </div>

        <div class="pt-2">
            <button type="button" onclick="closeDetailModal()" class="w-full py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal 2: Edit Barang (Modal Form) -->
<div id="edit-item-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-lg w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl my-auto max-h-[90vh] overflow-y-auto text-left">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-pen-to-square text-amber-500 mr-2"></i> Edit Data Barang Inventaris
            </h3>
            <button onclick="closeEditModal()" type="button" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="edit-item-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">SKU Barang (Read Only)</label>
                <input type="text" id="edit-item-sku" class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-500 cursor-not-allowed" readonly>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="edit-item-name" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Gudang / Rak <span class="text-rose-500">*</span></label>
                <input type="text" name="location_bin" id="edit-item-location" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Stok Available <span class="text-rose-500">*</span></label>
                    <input type="number" name="available_stock" id="edit-item-stock" min="0" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Minimum Stock <span class="text-rose-500">*</span></label>
                    <input type="number" name="minimum_stock" id="edit-item-min" min="0" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ganti Foto Barang (Opsional)</label>
                <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-600 dark:file:text-amber-400 hover:file:bg-amber-500/20">
            </div>

            <div class="flex space-x-2 pt-3 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition shadow">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Hapus Barang (Notifikasi Pop Up Konfirmasi) -->
<div id="delete-item-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel max-w-sm w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-700 space-y-4 shadow-2xl text-center my-auto">
        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center mx-auto text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div>
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Konfirmasi Hapus Barang</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Apakah Anda yakin ingin menghapus barang ini secara permanen dari gudang?</p>
        </div>

        <div class="p-3 bg-rose-500/10 rounded-2xl border border-rose-500/20 text-left">
            <div id="delete-item-name" class="font-extrabold text-slate-900 dark:text-white text-xs"></div>
            <div id="delete-item-sku" class="font-mono text-[10px] text-rose-600 dark:text-rose-400 font-bold mt-0.5"></div>
        </div>

        <form id="delete-item-form" action="" method="POST" class="flex space-x-2 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-300 dark:hover:bg-slate-700 transition">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow">
                <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus
            </button>
        </form>
    </div>
</div>

<script>
    // 1. Detail Modal Handlers
    function openDetailModal(item) {
        document.getElementById('detail-item-image').src = item.image_url || 'https://placehold.co/100x100/1e293b/06b6d4?text=No+Photo';
        document.getElementById('detail-item-name').innerText = item.name;
        document.getElementById('detail-item-sku').innerText = 'SKU: ' + item.sku;
        document.getElementById('detail-item-location').innerText = item.location_bin;
        document.getElementById('detail-item-qr').innerText = item.qr_code_payload;
        document.getElementById('detail-item-stock').innerText = item.available_stock + ' Unit';
        document.getElementById('detail-item-min').innerText = item.minimum_stock + ' Unit';

        let statusHtml = '';
        if (item.available_stock <= 0) {
            statusHtml = '<span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-600 border border-rose-500/30">OUT OF STOCK</span>';
        } else if (item.available_stock <= item.minimum_stock) {
            statusHtml = '<span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-500/20 text-amber-600 border border-amber-500/30">LOW STOCK</span>';
        } else {
            statusHtml = '<span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-600 border border-emerald-500/30">IN STOCK</span>';
        }
        document.getElementById('detail-item-status').innerHTML = statusHtml;

        document.getElementById('detail-item-modal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detail-item-modal').classList.add('hidden');
    }

    // 2. Edit Modal Handlers
    function openEditModal(item) {
        document.getElementById('edit-item-form').action = '/admin/stock/' + item.id;
        document.getElementById('edit-item-sku').value = item.sku;
        document.getElementById('edit-item-name').value = item.name;
        document.getElementById('edit-item-location').value = item.location_bin;
        document.getElementById('edit-item-stock').value = item.available_stock;
        document.getElementById('edit-item-min').value = item.minimum_stock;

        document.getElementById('edit-item-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-item-modal').classList.add('hidden');
    }

    // 3. Delete Modal Handlers
    function openDeleteModal(id, name, sku) {
        document.getElementById('delete-item-form').action = '/admin/stock/' + id;
        document.getElementById('delete-item-name').innerText = name;
        document.getElementById('delete-item-sku').innerText = 'SKU: ' + sku;

        document.getElementById('delete-item-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-item-modal').classList.add('hidden');
    }
</script>
