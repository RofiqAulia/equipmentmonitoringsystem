<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequisition;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    /**
     * Display Input & Restock Stock Barang Form.
     */
    public function inputForm(Request $request)
    {
        $items = Item::orderBy('name')->get();
        $selectedItem = null;

        if ($request->filled('item_id')) {
            $selectedItem = Item::find($request->input('item_id'));
        }

        return view('admin.stock_input', [
            'items' => $items,
            'selectedItem' => $selectedItem,
        ]);
    }

    /**
     * Store new stock entry or update existing item stock with image file upload support.
     */
    public function storeStock(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:new,existing',
            'item_id' => 'nullable|required_if:mode,existing|exists:items,id',
            'sku' => 'nullable|required_if:mode,new|string|max:50',
            'name' => 'nullable|required_if:mode,new|string|max:255',
            'gis_category' => 'nullable|string|in:ATK & Fotocopy,Biaya Packaging,Biaya Kebersihan,Sparepart Inventaris',
            'location_bin' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'minimum_stock' => 'required|integer|min:0',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,heic,avif,ico,tiff|max:10240',
            'image_file_camera' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,heic,avif,ico,tiff|max:10240',
        ]);

        $uploadedImageUrl = null;

        // Handle Image File Upload (File Gallery or Camera Capture)
        $file = $request->file('image_file') ?? $request->file('image_file_camera');
        if ($file) {
            $uploadPath = public_path('uploads/items');
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move($uploadPath, $filename);
            
            $uploadedImageUrl = asset('uploads/items/' . $filename);
        }

        if ($request->input('mode') === 'existing') {
            $item = Item::findOrFail($request->input('item_id'));
            $item->available_stock += (int) $request->input('quantity');
            $item->location_bin = $request->input('location_bin');
            $item->minimum_stock = (int) $request->input('minimum_stock');
            
            if ($uploadedImageUrl) {
                $item->image_url = $uploadedImageUrl;
            }
            
            $item->save();

            return redirect()->route('admin.stock.input')
                ->with('success', "Stok barang '{$item->name}' (SKU: {$item->sku}) berhasil ditambah +{$request->input('quantity')} unit. Total stok sekarang: {$item->available_stock}.");
        }

        // Mode New Item
        $sku = strtoupper(trim($request->input('sku')));

        if (Item::where('sku', $sku)->exists()) {
            return back()->withInput()->with('error', "SKU '{$sku}' sudah terdaftar dalam sistem. Gunakan opsi Restock Barang Existing.");
        }

        $defaultPlaceholder = 'https://placehold.co/100x100/1e293b/06b6d4?text=' . urlencode(trim($request->input('name')));

        $item = Item::create([
            'sku' => $sku,
            'qr_code_payload' => 'QR-' . $sku,
            'name' => trim($request->input('name')),
            'gis_category' => $request->input('gis_category') ?: null,
            'location_bin' => trim($request->input('location_bin')),
            'available_stock' => (int) $request->input('quantity'),
            'minimum_stock' => (int) $request->input('minimum_stock'),
            'image_url' => $uploadedImageUrl ?: $defaultPlaceholder,
        ]);

        return redirect()->route('admin.stock.input')
            ->with('success', "Barang baru '{$item->name}' (SKU: {$item->sku}) berhasil ditambahkan ke inventaris dengan stok awal {$item->available_stock} unit.");
    }

    /**
     * Display Deteksi Barang Menipis (Low Stock Detector).
     */
    public function lowStockDetector()
    {
        $allLowStockItems = Item::whereColumn('available_stock', '<=', 'minimum_stock')
            ->orderBy('available_stock', 'asc')
            ->get();

        $outOfStockItems = $allLowStockItems->where('available_stock', '<=', 0);
        $lowStockItems = $allLowStockItems->where('available_stock', '>', 0);

        // Pending requisitions map
        $pendingRequisitions = ItemRequisition::where('status', 'pending')
            ->pluck('item_id')
            ->filter()
            ->toArray();

        return view('admin.low_stock', [
            'allLowStockItems' => $allLowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'lowStockItems' => $lowStockItems,
            'pendingRequisitions' => $pendingRequisitions,
        ]);
    }

    /**
     * Display printable view for Warehouse Inventory Report (Cetak Laporan Inventaris Gudang).
     */
    public function printReport(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('location_bin', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $statusFilter = $request->input('status');
            if ($statusFilter === 'out_of_stock') {
                $query->where('available_stock', '<=', 0);
            } elseif ($statusFilter === 'low_stock') {
                $query->where('available_stock', '>', 0)
                    ->whereColumn('available_stock', '<=', 'minimum_stock');
            } elseif ($statusFilter === 'in_stock') {
                $query->whereColumn('available_stock', '>', 'minimum_stock');
            }
        }

        $items = $query->orderBy('name', 'asc')->get();

        $summary = [
            'total_items' => $items->count(),
            'total_stock' => $items->sum('available_stock'),
            'in_stock' => $items->filter(fn($i) => $i->available_stock > $i->minimum_stock)->count(),
            'low_stock' => $items->filter(fn($i) => $i->available_stock > 0 && $i->available_stock <= $i->minimum_stock)->count(),
            'out_of_stock' => $items->filter(fn($i) => $i->available_stock <= 0)->count(),
        ];

        return view('admin.reports.print_inventory', [
            'items' => $items,
            'summary' => $summary,
            'filterStatus' => $request->input('status', 'all'),
            'printedAt' => now()->translatedFormat('d F Y, H:i') . ' WIB',
            'printedBy' => auth()->user()->name ?? 'Administrator',
        ]);
    }

    /**
     * Update stock item details.
     */
    public function updateItem(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gis_category' => 'nullable|string|in:ATK & Fotocopy,Biaya Packaging,Biaya Kebersihan,Sparepart Inventaris',
            'location_bin' => 'required|string|max:100',
            'available_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,heic,avif,ico,tiff|max:10240',
            'image_file_camera' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,heic,avif,ico,tiff|max:10240',
        ]);

        $file = $request->file('image_file') ?? $request->file('image_file_camera');
        if ($file) {
            $uploadPath = public_path('uploads/items');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move($uploadPath, $filename);
            $item->image_url = asset('uploads/items/' . $filename);
        }

        $item->name = trim($request->input('name'));
        $item->gis_category = $request->input('gis_category') ?: null;
        $item->location_bin = trim($request->input('location_bin'));
        $item->available_stock = (int) $request->input('available_stock');
        $item->minimum_stock = (int) $request->input('minimum_stock');
        $item->save();

        return redirect()->back()->with('success', "Barang '{$item->name}' (SKU: {$item->sku}) berhasil diperbarui.");
    }

    /**
     * Delete a stock item.
     */
    public function destroyItem(Item $item)
    {
        $name = $item->name;
        $sku = $item->sku;
        $item->delete();

        return redirect()->back()->with('success', "Barang '{$name}' (SKU: {$sku}) berhasil dihapus dari inventaris.");
    }

    /**
     * Display printable view for Batch QR Code printing (Kertas A4 / Stiker 4x6 / Custom Grid).
     */
    public function qrPrintIndex(Request $request)
    {
        $sortBy = $request->input('sort_by', 'name'); // 'name', 'sku', 'id'
        $sortDir = strtolower($request->input('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $mode = $request->input('mode', 'all'); // 'all', 'range', 'selected'
        $grid = $request->input('grid_layout', 'grid-3x5');
        $search = trim($request->input('search', ''));

        $query = Item::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('location_bin', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        if ($sortBy === 'sku') {
            $query->orderBy('sku', $sortDir);
        } elseif ($sortBy === 'id') {
            $query->orderBy('id', $sortDir);
        } else { // default 'name' (Abjad)
            $query->orderBy('name', $sortDir);
        }

        $allItems = $query->get();

        // Add 1-indexed sequential order number to each item for reference
        $allItems->each(function ($item, $index) {
            $item->seq_num = $index + 1;
        });

        $filteredItems = $allItems;

        if ($mode === 'range') {
            $from = (int) $request->input('range_from', 1);
            $to = (int) $request->input('range_to', min(15, max(1, $allItems->count())));
            
            if ($from < 1) $from = 1;
            if ($to > $allItems->count()) $to = $allItems->count();
            if ($from > $to) $from = $to;

            $filteredItems = $allItems->slice($from - 1, ($to - $from + 1))->values();
        } elseif ($mode === 'selected') {
            $selectedIds = (array) $request->input('selected_ids', []);
            if (!empty($selectedIds)) {
                $filteredItems = $allItems->filter(function ($item) use ($selectedIds) {
                    return in_array($item->id, $selectedIds);
                })->values();
            }
        }

        return view('admin.qr_print', [
            'allItems' => $allItems,
            'filteredItems' => $filteredItems,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'mode' => $mode,
            'search' => $search,
            'rangeFrom' => $request->input('range_from', 1),
            'rangeTo' => $request->input('range_to', min(15, max(1, $allItems->count()))),
            'selectedIds' => (array) $request->input('selected_ids', []),
            'gridLayout' => $grid,
            'printedAt' => now()->translatedFormat('d F Y, H:i') . ' WIB',
            'printedBy' => auth()->user()->name ?? 'Supervisor',
        ]);
    }
}


