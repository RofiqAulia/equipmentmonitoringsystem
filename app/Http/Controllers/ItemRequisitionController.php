<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemRequisitionController extends Controller
{
    /**
     * Display Pengajuan Barang List & Form.
     */
    public function index(Request $request)
    {
        $query = ItemRequisition::with(['item', 'requester', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requisitions = $query->latest()->get();

        $stats = [
            'total' => ItemRequisition::count(),
            'pending' => ItemRequisition::where('status', 'pending')->count(),
            'approved' => ItemRequisition::where('status', 'approved')->count(),
            'completed' => ItemRequisition::where('status', 'completed')->count(),
            'rejected' => ItemRequisition::where('status', 'rejected')->count(),
        ];

        // Low stock items for quick requisition dropdown
        $lowStockItems = Item::whereColumn('available_stock', '<=', 'minimum_stock')->get();

        $selectedItemId = $request->query('item_id');
        if ($selectedItemId && !$lowStockItems->contains('id', $selectedItemId)) {
            $item = Item::find($selectedItemId);
            if ($item) {
                $lowStockItems->push($item);
            }
        }

        return view('admin.requisitions', [
            'requisitions' => $requisitions,
            'stats' => $stats,
            'lowStockItems' => $lowStockItems,
            'selectedItemId' => $selectedItemId,
        ]);
    }

    /**
     * Create a new procurement requisition.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'item_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'quantity_requested' => 'required|integer|min:1',
            'reason' => 'required|string',
        ]);

        $itemId = $request->input('item_id');
        $itemName = $request->input('item_name');
        $sku = $request->input('sku');

        if ($itemId && !$itemName) {
            $item = Item::find($itemId);
            $itemName = $item->name;
            $sku = $item->sku;
        }

        ItemRequisition::create([
            'item_id' => $itemId,
            'item_name' => $itemName,
            'sku' => $sku,
            'requested_by' => Auth::id(),
            'quantity_requested' => (int) $request->input('quantity_requested'),
            'status' => 'pending',
            'reason' => $request->input('reason'),
        ]);

        return redirect()->route('admin.requisitions.index')
            ->with('success', "Pengajuan barang '{$itemName}' sebanyak {$request->input('quantity_requested')} unit berhasil diajukan.");
    }

    /**
     * Update requisition status (Approve / Reject / Complete).
     */
    public function updateStatus(Request $request, ItemRequisition $requisition)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $requisition->status;
        $newStatus = $request->input('status');

        $requisition->status = $newStatus;
        $requisition->approved_by = Auth::id();
        if ($request->filled('admin_notes')) {
            $requisition->admin_notes = $request->input('admin_notes');
        }
        $requisition->save();

        // If requisition marked as completed, automatically add to inventory stock!
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            if ($requisition->item_id && $item = Item::find($requisition->item_id)) {
                $item->available_stock += $requisition->quantity_requested;
                $item->save();
            }
        }

        $statusText = match ($newStatus) {
            'approved' => 'DISETUJUI',
            'rejected' => 'DITOLAK',
            'completed' => 'SELESAI (Stok otomatis ditambahkan)',
            default => strtoupper($newStatus),
        };

        return redirect()->route('admin.requisitions.index')
            ->with('success', "Status pengajuan barang #{$requisition->id} ('{$requisition->item_name}') berhasil diperbarui menjadi {$statusText}.");
    }
}
