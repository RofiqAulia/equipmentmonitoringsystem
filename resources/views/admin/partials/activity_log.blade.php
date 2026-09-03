<!-- DataTables Table 1: Activity Log Table (Unified Component) -->
<div class="glass-panel p-6 rounded-3xl space-y-4">
    <!-- Component Header & Status Summary -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-clock-rotate-left text-cyan-600 dark:text-cyan-400 mr-2"></i> Activity Log (DataTables Transaksi Pengambilan)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Filter Barang, Rentang Tanggal, & Grouping Real-Time langsung di DataTables</p>
        </div>

        <div class="flex items-center space-x-3 shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping mr-2"></span> Real-time DataTables
            </span>
            <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700">
                Ditemukan: <strong id="activity-filtered-count" class="text-cyan-600 dark:text-cyan-400 font-black text-sm">0</strong> data
            </span>
        </div>
    </div>

    <!-- Integrated DataTables Control Panel Toolbar (Embedded Directly Inside DataTables Header) -->
    <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 space-y-3 shadow-inner">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            
            <!-- Filter 1: Item / Barang Search & Select -->
            <div>
                <label for="item_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    <i class="fa-solid fa-box text-cyan-500 mr-1"></i> Filter Barang / Item:
                </label>
                <select name="item_id" id="item_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm cursor-pointer">
                    <option value="all" {{ ($selectedItemId ?? '') == 'all' || empty($selectedItemId ?? '') ? 'selected' : '' }}>-- Semua Barang / Item --</option>
                    @foreach($allItemsList ?? [] as $itemOption)
                        <option value="{{ $itemOption->id }}" {{ ($selectedItemId ?? '') == $itemOption->id ? 'selected' : '' }}>
                            [{{ $itemOption->sku }}] {{ $itemOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter 2: Tanggal Dari (Start Date) -->
            <div>
                <label for="start_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    <i class="fa-solid fa-calendar-day text-cyan-500 mr-1"></i> Tanggal Dari:
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? today()->format('Y-m-d') }}"
                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
            </div>

            <!-- Filter 3: Tanggal Sampai (End Date) -->
            <div>
                <label for="end_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    <i class="fa-solid fa-calendar-check text-cyan-500 mr-1"></i> Tanggal Sampai:
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? today()->format('Y-m-d') }}"
                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 transition shadow-sm">
            </div>

            <!-- Filter 4: Grouping Select -->
            <div>
                <label for="group-activity-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    <i class="fa-solid fa-layer-group text-cyan-500 mr-1"></i> Grouping Tabel:
                </label>
                <select id="group-activity-select" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-cyan-500 shadow-sm cursor-pointer">
                    <option value="-1">Tanpa Grouping</option>
                    <option value="2">Group berdasarkan Operator</option>
                    <option value="3">Group berdasarkan Supervisor (SPV)</option>
                    <option value="4">Group berdasarkan Barang & SKU</option>
                    <option value="6">Group berdasarkan Lokasi Rak</option>
                </select>
            </div>

        </div>

        <!-- Quick Presets & Report Actions Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 pt-2 border-t border-slate-200/80 dark:border-slate-800/80 text-xs">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="font-bold text-slate-600 dark:text-slate-400 flex items-center mr-1">
                    <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Preset Rentang:
                </span>
                <button type="button" id="btn-preset-today" onclick="setPresetDate('today')" class="preset-btn px-2.5 py-1 bg-white dark:bg-slate-900 hover:bg-cyan-600 hover:text-white text-slate-700 dark:text-slate-300 font-semibold rounded-lg border border-slate-300 dark:border-slate-700 transition shadow-sm">Hari Ini</button>
                <button type="button" id="btn-preset-7days" onclick="setPresetDate('7days')" class="preset-btn px-2.5 py-1 bg-white dark:bg-slate-900 hover:bg-cyan-600 hover:text-white text-slate-700 dark:text-slate-300 font-semibold rounded-lg border border-slate-300 dark:border-slate-700 transition shadow-sm">7 Hari Terakhir</button>
                <button type="button" id="btn-preset-month" onclick="setPresetDate('month')" class="preset-btn px-2.5 py-1 bg-white dark:bg-slate-900 hover:bg-cyan-600 hover:text-white text-slate-700 dark:text-slate-300 font-semibold rounded-lg border border-slate-300 dark:border-slate-700 transition shadow-sm">Bulan Ini</button>
                <button type="button" id="btn-preset-all" onclick="setPresetDate('all')" class="preset-btn px-2.5 py-1 bg-white dark:bg-slate-900 hover:bg-cyan-600 hover:text-white text-slate-700 dark:text-slate-300 font-semibold rounded-lg border border-slate-300 dark:border-slate-700 transition shadow-sm">Semua Tanggal</button>
                
                <button type="button" onclick="resetActivityFilter()" class="px-2.5 py-1 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-lg transition" title="Reset Filter ke Hari Ini">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                </button>
            </div>

            <!-- PRINT / UNDUH REPORT BUTTON -->
            <div class="flex items-center space-x-2 pt-1 md:pt-0 shrink-0">
                <a id="btn-print-report" href="{{ route('admin.activity-log.report', ['item_id' => $selectedItemId ?? 'all', 'start_date' => $startDate ?? today()->format('Y-m-d'), 'end_date' => $endDate ?? today()->format('Y-m-d')]) }}"
                   target="_blank"
                   class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/20 transition flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-print text-xs"></i>
                    <span>Cetak / Unduh Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- DataTables Native Table View -->
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
                    @php
                        $dt = $retrieval->picked_at ?? $retrieval->created_at;
                        $logDate = '';
                        if ($dt) {
                            if ($dt instanceof \Carbon\CarbonInterface) {
                                $logDate = $dt->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d');
                            } else {
                                $logDate = \Carbon\Carbon::parse($dt)->setTimezone('Asia/Jakarta')->format('Y-m-d');
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/50 transition log-activity-row"
                        data-date="{{ $logDate }}"
                        data-item-id="{{ $retrieval->item_id }}">
                        <td class="px-3 py-3 text-center font-bold text-slate-500 dark:text-slate-400 text-xs"
                            data-order="{{ $loop->iteration }}"
                            data-date="{{ $logDate }}"
                            data-item-id="{{ $retrieval->item_id }}">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-500 whitespace-nowrap"
                            data-order="{{ optional($retrieval->picked_at ?? $retrieval->created_at)->timestamp ?? 0 }}"
                            data-search="[DATE:{{ $logDate }}][ITEM:{{ $retrieval->item_id }}] #LOG-{{ $retrieval->id }} {{ optional($retrieval->picked_at ?? $retrieval->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB"
                            data-date="{{ $logDate }}"
                            data-item-id="{{ $retrieval->item_id }}">
                            <!-- Zero-width inline tag so DataTables text readers never omit it -->
                            <span style="display:inline-block; font-size:0; width:0; height:0; overflow:hidden;" class="log-metadata-tag">[DATE:{{ $logDate }}][ITEM:{{ $retrieval->item_id }}]</span>
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

@push('scripts')
<script>
    function syncActivityLogDataAttributes() {
        if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable && $.fn.DataTable.isDataTable('#activityLogTable')) {
            var table = $('#activityLogTable').DataTable();
            var dtSettings = table.settings()[0];
            if (dtSettings && dtSettings.aoData) {
                dtSettings.aoData.forEach(function(rowObj) {
                    if (rowObj.nTr) {
                        rowObj._date = rowObj.nTr.getAttribute('data-date');
                        rowObj._itemId = rowObj.nTr.getAttribute('data-item-id');
                    }
                });
            }
        }
    }

    (function() {
        if (typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
            // Clear duplicate registrations of activityLogDateFilter
            if ($.fn.dataTable.ext.search) {
                for (var i = $.fn.dataTable.ext.search.length - 1; i >= 0; i--) {
                    if ($.fn.dataTable.ext.search[i].name === 'activityLogDateFilter') {
                        $.fn.dataTable.ext.search.splice(i, 1);
                    }
                }
            }

            var activityLogDateFilter = function(settings, searchData, index, rowData, counter) {
                // Only skip if the table is explicitly a different table (not activityLogTable)
                if (settings.nTable && settings.nTable.id && settings.nTable.id !== 'activityLogTable') {
                    return true;
                }

                var min = $('#start_date').val();
                var max = $('#end_date').val();
                var selectedItem = $('#item_id').val();

                var rowNode = settings.aoData[index] ? settings.aoData[index].nTr : null;
                var rowDate = '';
                var rowItemId = '';

                // Tier 1: Check TR node data attributes directly
                if (rowNode) {
                    rowDate = rowNode.getAttribute('data-date') || '';
                    rowItemId = rowNode.getAttribute('data-item-id') || '';
                }

                // Tier 2: Parse [DATE:YYYY-MM-DD] tag from searchData[1]
                if (!rowDate && searchData && searchData[1]) {
                    var dMatch = searchData[1].match(/\[DATE:(\d{4}-\d{2}-\d{2})\]/);
                    if (dMatch) rowDate = dMatch[1];
                }
                if (!rowItemId && searchData && searchData[1]) {
                    var iMatch = searchData[1].match(/\[ITEM:(\d+)\]/);
                    if (iMatch) rowItemId = iMatch[1];
                }

                // Tier 3: Fallback parse rendered text date ("31 Aug 2026", "03 Sep 2026") from cell string
                if (!rowDate && searchData && searchData[1]) {
                    var textMatch = searchData[1].match(/(\d{1,2})\s+([A-Za-z]{3})\s+(\d{4})/);
                    if (textMatch) {
                        var day = String(textMatch[1]).padStart(2, '0');
                        var monthStr = textMatch[2].toLowerCase();
                        var year = textMatch[3];
                        var monthMap = {
                            'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04', 'may': '05', 'jun': '06',
                            'jul': '07', 'aug': '08', 'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12',
                            'agt': '08', 'okt': '10', 'des': '12'
                        };
                        var m = monthMap[monthStr.substring(0, 3)] || '01';
                        rowDate = year + '-' + m + '-' + day;
                    }
                }

                // Filter Item
                if (selectedItem && selectedItem !== 'all' && selectedItem !== '') {
                    if (String(rowItemId) !== String(selectedItem)) {
                        return false;
                    }
                }

                // Filter Date Range (start_date <= rowDate <= end_date)
                if (min || max) {
                    if (!rowDate) {
                        return false; // Hide if date missing when date filter active
                    }
                    if (min && rowDate < min) {
                        return false; // Hide if older than start date
                    }
                    if (max && rowDate > max) {
                        return false; // Hide if newer than end date
                    }
                }

                return true;
            };

            Object.defineProperty(activityLogDateFilter, 'name', { value: 'activityLogDateFilter' });
            $.fn.dataTable.ext.search.push(activityLogDateFilter);
        }
    })();

    function applyActivityFilter() {
        if ($.fn.DataTable.isDataTable('#activityLogTable')) {
            var table = $('#activityLogTable').DataTable();
            syncActivityLogDataAttributes();
            table.draw();
            updateActivityFilterSummary();
        }
    }

    function applyActivityGrouping() {
        if ($.fn.DataTable.isDataTable('#activityLogTable')) {
            var table = $('#activityLogTable').DataTable();
            var colIdx = parseInt($('#group-activity-select').val());
            if (colIdx >= 0) {
                if (table.rowGroup) {
                    table.rowGroup().dataSrc(colIdx).enable().draw();
                } else {
                    table.order([[colIdx, 'asc']]).draw();
                }
            } else {
                if (table.rowGroup) {
                    table.rowGroup().disable();
                }
                table.order([[0, 'desc']]).draw();
            }
        }
    }

    function setPresetActiveHighlight(presetKey) {
        $('.preset-btn').removeClass('bg-cyan-600 text-white shadow-md border-cyan-600').addClass('bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700');
        if (presetKey === 'today') {
            $('#btn-preset-today').removeClass('bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700').addClass('bg-cyan-600 text-white font-bold shadow-md border-cyan-600');
        } else if (presetKey === '7days') {
            $('#btn-preset-7days').removeClass('bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700').addClass('bg-cyan-600 text-white font-bold shadow-md border-cyan-600');
        } else if (presetKey === 'month') {
            $('#btn-preset-month').removeClass('bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700').addClass('bg-cyan-600 text-white font-bold shadow-md border-cyan-600');
        } else if (presetKey === 'all') {
            $('#btn-preset-all').removeClass('bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700').addClass('bg-cyan-600 text-white font-bold shadow-md border-cyan-600');
        }
    }

    function resetActivityFilter() {
        var today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);
        $('#end_date').val(today);
        $('#item_id').val('all');
        $('#group-activity-select').val('-1');
        setPresetActiveHighlight('today');
        applyActivityGrouping();
        applyActivityFilter();
    }

    function setPresetDate(preset) {
        var todayObj = new Date();
        var year = todayObj.getFullYear();
        var month = String(todayObj.getMonth() + 1).padStart(2, '0');
        var day = String(todayObj.getDate()).padStart(2, '0');
        var todayStr = year + '-' + month + '-' + day;

        if (preset === 'today') {
            $('#start_date').val(todayStr);
            $('#end_date').val(todayStr);
        } else if (preset === '7days') {
            var d = new Date();
            d.setDate(d.getDate() - 6);
            var dYear = d.getFullYear();
            var dMonth = String(d.getMonth() + 1).padStart(2, '0');
            var dDay = String(d.getDate()).padStart(2, '0');
            $('#start_date').val(dYear + '-' + dMonth + '-' + dDay);
            $('#end_date').val(todayStr);
        } else if (preset === 'month') {
            var mStart = year + '-' + month + '-01';
            $('#start_date').val(mStart);
            $('#end_date').val(todayStr);
        } else if (preset === 'all') {
            $('#start_date').val('');
            $('#end_date').val('');
        }
        setPresetActiveHighlight(preset);
        applyActivityFilter();
    }

    function updateActivityFilterSummary() {
        if ($.fn.DataTable.isDataTable('#activityLogTable')) {
            var table = $('#activityLogTable').DataTable();
            var info = table.page.info();
            $('#activity-filtered-count').text(info.recordsDisplay);
        }

        var itemId = $('#item_id').val() || 'all';
        var startDate = $('#start_date').val() || '';
        var endDate = $('#end_date').val() || '';
        var baseUrl = "{{ route('admin.activity-log.report') }}";
        var printUrl = baseUrl + "?item_id=" + encodeURIComponent(itemId) + "&start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate);
        $('#btn-print-report').attr('href', printUrl);
    }

    $(document).ready(function() {
        setPresetActiveHighlight('today');

        $('#start_date, #end_date, #item_id').on('change input', function() {
            applyActivityFilter();
        });

        $('#group-activity-select').on('change', function() {
            applyActivityGrouping();
        });

        setTimeout(function() {
            applyActivityGrouping();
            applyActivityFilter();
        }, 100);
    });
</script>
@endpush


