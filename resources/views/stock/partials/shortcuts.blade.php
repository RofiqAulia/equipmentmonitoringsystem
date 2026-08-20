<!-- Quick Sample Barcode / SKU Test Shortcuts -->
<div class="glass-card p-4 rounded-2xl space-y-2">
    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center justify-between">
        <span class="flex items-center"><i class="fa-solid fa-bolt text-amber-500 dark:text-amber-400 mr-1.5"></i> Shortcut Cepat Uji Coba Pindai SKU / QR Payload:</span>
        <span class="text-[11px] text-cyan-600 dark:text-cyan-400 font-bold">Klik salah satu SKU untuk simulasi pindaian</span>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach(\App\Models\Item::take(6)->get() as $shortcutItem)
            <button onclick="autoScanPayload('{{ $shortcutItem->qr_code_payload }}')" type="button" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/80 hover:bg-cyan-500/20 hover:text-cyan-600 dark:hover:text-cyan-300 text-slate-700 dark:text-slate-300 rounded-xl text-xs border border-slate-300 dark:border-slate-700 font-mono transition flex items-center space-x-1.5">
                <i class="fa-solid fa-qrcode text-[10px] text-cyan-500"></i>
                <span>{{ $shortcutItem->sku }}</span>
                <span class="text-[10px] px-1.5 py-0.2 bg-slate-200 dark:bg-slate-700 rounded text-slate-500 dark:text-slate-400 font-sans">Stok: {{ $shortcutItem->available_stock }}</span>
            </button>
        @endforeach
    </div>
</div>
