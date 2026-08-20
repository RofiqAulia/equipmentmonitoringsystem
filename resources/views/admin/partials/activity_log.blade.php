<!-- DataTables Table 1: Activity Log Table -->
<div class="glass-panel p-6 rounded-3xl space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-clock-rotate-left text-cyan-600 dark:text-cyan-400 mr-2"></i> Activity Log (DataTables Transaksi Pengambilan)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pencarian, Pengurutan (ASC/DESC), & Grouping Interaktif Transaksi</p>
        </div>

        <!-- Grouping Dropdown for Activity Log -->
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-slate-600 dark:text-slate-400 flex items-center">
                <i class="fa-solid fa-layer-group text-cyan-500 mr-1.5"></i> Grouping:
            </label>
            <select id="group-activity-select" class="px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 shadow-sm cursor-pointer">
                <option value="-1">Tanpa Grouping</option>
                <option value="1">Group berdasarkan Operator</option>
                <option value="2">Group berdasarkan Supervisor (SPV)</option>
                <option value="5">Group berdasarkan Lokasi Rak</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800/80 p-2">
        <table id="activityLogTable" class="w-full min-w-[850px] text-left text-xs text-slate-700 dark:text-slate-300 display">
            <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-slate-800">
                <tr>
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
                @forelse($recentRetrievals as $retrieval)
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition">
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
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">Belum ada aktivitas pengambilan stok barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
