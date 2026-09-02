<!-- DataTables Table 1: Activity Log Table -->
<div class="glass-panel p-6 rounded-3xl space-y-4">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-clock-rotate-left text-cyan-600 dark:text-cyan-400 mr-2"></i> Activity Log (DataTables Transaksi Pengambilan)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Filter Barang, Rentang Tanggal Real-time, & Cetak Laporan PDF/Print</p>
        </div>

        <!-- Grouping & Action Toolbar -->
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-slate-600 dark:text-slate-400 flex items-center">
                <i class="fa-solid fa-layer-group text-cyan-500 mr-1.5"></i> Grouping:
            </label>
            <select id="group-activity-select" class="px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 shadow-sm cursor-pointer">
                <option value="-1">Tanpa Grouping</option>
                <option value="2">Group berdasarkan Operator</option>
                <option value="3">Group berdasarkan Supervisor (SPV)</option>
                <option value="6">Group berdasarkan Lokasi Rak</option>
            </select>
        </div>
    </div>

    <!-- Filter Control Panel Form -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-end gap-3 flex-wrap">
        
        <!-- Filter 1: Item / Barang Search & Select -->
        <div class="flex-1 min-w-[200px] w-full">
            <label for="item_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                <i class="fa-solid fa-box text-cyan-500 mr-1"></i> Filter Barang / Item:
            </label>
            <select name="item_id" id="item_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
                <option value="all" {{ ($selectedItemId ?? '') == 'all' || empty($selectedItemId ?? '') ? 'selected' : '' }}>-- Semua Barang / Item --</option>
                @foreach($allItemsList ?? [] as $itemOption)
                    <option value="{{ $itemOption->id }}" {{ ($selectedItemId ?? '') == $itemOption->id ? 'selected' : '' }}>
                        [{{ $itemOption->sku }}] {{ $itemOption->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter 2: Tanggal Dari (Start Date) -->
        <div class="w-full md:w-44">
            <label for="start_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                <i class="fa-solid fa-calendar-day text-cyan-500 mr-1"></i> Tanggal Dari:
            </label>
            <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? today()->format('Y-m-d') }}"
                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
        </div>

        <!-- Filter 3: Tanggal Sampai (End Date) -->
        <div class="w-full md:w-44">
            <label for="end_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                <i class="fa-solid fa-calendar-check text-cyan-500 mr-1"></i> Tanggal Sampai:
            </label>
            <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? today()->format('Y-m-d') }}"
                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
        </div>

        <!-- Action Buttons: Submit Filter & Print Report -->
        <div class="flex items-center space-x-2 w-full md:w-auto pt-1 md:pt-0">
            <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow-md shadow-cyan-600/20 transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Terapkan Filter</span>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl transition flex items-center justify-center" title="Reset Filter ke Hari Ini">
                <i class="fa-solid fa-rotate-left"></i>
            </a>

            <!-- PRINT / UNDUH REPORT BUTTON -->
            <a href="{{ route('admin.activity-log.report', ['item_id' => $selectedItemId ?? 'all', 'start_date' => $startDate ?? today()->format('Y-m-d'), 'end_date' => $endDate ?? today()->format('Y-m-d')]) }}"
               target="_blank"
               class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/20 transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Cetak / Unduh Laporan</span>
            </a>
        </div>

    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
        <table id="activityLogTable" class="w-full min-w-[850px] text-left text-xs text-slate-700 dark:text-slate-300 display">
            <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-3 py-3 text-center w-10 cursor-pointer">No</th>
                    <th class="px-4 py-3 cursor-pointer"><i class="fa-solid fa-hashtag mr-1 text-slate-400"></i> ID / Waktu</th>
                    <th class="px-4 py-3 cursor-pointer"><i class="fa-solid fa-user mr-1 text-slate-400"></i> Operator</th>
                    <th class="px-4 py-3 cursor-pointer"><i class="fa-solid fa-user-shield mr-1 text-slate-400"></i> Supervisor (SPV)</th>
                    <th class="px-4 py-3 min-w-[180px] cursor-pointer"><i class="fa-solid fa-box mr-1 text-slate-400"></i> Barang & SKU</th>
                    <th class="px-4 py-3 text-center cursor-pointer"><i class="fa-solid fa-layer-group mr-1 text-slate-400"></i> Qty Ambil</th>
                    <th class="px-4 py-3 cursor-pointer"><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i> Lokasi Rak</th>
                    <th class="px-4 py-3 min-w-[160px] cursor-pointer"><i class="fa-solid fa-comment-dots mr-1 text-slate-400"></i> Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                @foreach($recentRetrievals as $retrieval)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
                        <td class="px-3 py-3 text-center font-bold text-slate-500 dark:text-slate-400 text-xs" data-order="{{ $loop->iteration }}">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-500 whitespace-nowrap" data-order="{{ optional($retrieval->picked_at ?? $retrieval->created_at)->timestamp ?? 0 }}">
                            <div class="font-bold text-slate-800 dark:text-slate-200">#LOG-{{ $retrieval->id }}</div>
                            <div class="text-[10px] text-slate-400">{{ optional($retrieval->picked_at ?? $retrieval->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</div>
                        </td>
                        <td class="px-4 py-3 break-words max-w-[150px]">
                            <div class="font-semibold text-slate-900 dark:text-white leading-snug">{{ $retrieval->user->name ?? 'Operator' }}</div>
                            <div class="text-[10px] text-cyan-600 dark:text-cyan-400 font-mono">OP-ID: {{ $retrieval->user_id }}</div>
                        </td>
                        <td class="px-4 py-3 break-words max-w-[160px]">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 leading-snug">
                                {{ $retrieval->supervisor->name ?? 'SPV Authorized' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 break-words max-w-[200px]">
                            <div class="font-bold text-slate-900 dark:text-white leading-snug">{{ $retrieval->item->name ?? 'N/A' }}</div>
                            <div class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400">{{ $retrieval->item->sku ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center font-black text-rose-500 text-sm whitespace-nowrap">
                            -{{ $retrieval->quantity_picked }} unit
                        </td>
                        <td class="px-4 py-3 break-words max-w-[130px] font-medium text-slate-700 dark:text-slate-300">
                            {{ $retrieval->item->location_bin ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400 break-words max-w-[220px] leading-relaxed">
                            {{ $retrieval->notes ?: '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
