<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RetrievalLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockRetrievalController extends Controller
{
    /**
     * POST /api/scan-item
     * Lookup item by QR Payload or SKU.
     */
    public function scanItem(Request $request): JsonResponse
    {
        $request->validate([
            'payload' => 'required|string',
        ]);

        $payload = trim($request->input('payload'));

        $item = Item::where('qr_code_payload', $payload)
            ->orWhere('sku', $payload)
            ->first();

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => "Barang dengan QR Payload / SKU '{$payload}' tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail barang berhasil ditemukan.',
            'item' => [
                'id' => $item->id,
                'sku' => $item->sku,
                'qr_code_payload' => $item->qr_code_payload,
                'name' => $item->name,
                'location_bin' => $item->location_bin,
                'available_stock' => $item->available_stock,
                'minimum_stock' => $item->minimum_stock,
                'stock_status' => $item->stock_status,
                'image_url' => $item->image_url,
            ],
        ]);
    }

    /**
     * POST /api/confirm-retrieval
     * Process stock retrieval transaction securely with DB::transaction.
     */
    public function confirmRetrieval(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity_picked' => 'required|integer|min:1',
            'supervisor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $userId = Auth::id() ?? $request->input('user_id');
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna/Operator tidak valid atau belum terautentikasi.',
            ], 401);
        }

        // Determine supervisor_id from request input, session user, or smart fallback
        $supervisorId = $request->input('supervisor_id') ?? $user->supervisor_id;

        if (! $supervisorId) {
            if ($user->role === 'admin' || $user->role === 'spv') {
                $supervisorId = $user->id;
            } else {
                $supervisorId = User::whereIn('role', ['spv', 'admin'])->value('id') ?? $user->id;
            }
        }

        $itemId = $request->input('item_id');
        $quantityPicked = (int) $request->input('quantity_picked');
        $notes = $request->input('notes');

        try {
            $result = DB::transaction(function () use ($itemId, $quantityPicked, $user, $supervisorId, $notes) {
                /** @var Item $item */
                $item = Item::where('id', $itemId)->lockForUpdate()->first();

                // Validate stock availability
                if ($quantityPicked > $item->available_stock) {
                    throw new \InvalidArgumentException(
                        "Stok tidak mencukupi. Permintaan: {$quantityPicked}, Stok tersedia: {$item->available_stock}"
                    );
                }

                // Deduct stock
                $item->available_stock -= $quantityPicked;
                $item->save();

                // Save retrieval log record
                $log = RetrievalLog::create([
                    'user_id' => $user->id,
                    'supervisor_id' => $supervisorId,
                    'item_id' => $item->id,
                    'quantity_picked' => $quantityPicked,
                    'picked_at' => now(),
                    'notes' => $notes,
                ]);

                // Check low stock warning condition
                $isLowStock = $item->available_stock <= $item->minimum_stock;
                if ($isLowStock) {
                    Log::warning("[STOCK ALERT] Item SKU {$item->sku} ({$item->name}) reached low stock level. Remaining: {$item->available_stock}, Min threshold: {$item->minimum_stock}");
                }

                return [
                    'item' => $item->fresh(),
                    'log' => $log->load(['user:id,name', 'supervisor:id,name', 'item:id,name,sku']),
                    'is_low_stock' => $isLowStock,
                ];
            });

            // Update user's active supervisor_id if explicitly specified
            if ($request->filled('supervisor_id')) {
                $user->supervisor_id = $supervisorId;
                $user->save();
            }

            $lowStockWarning = $result['is_low_stock'];
            $warningMessage = $lowStockWarning
                ? "PERINGATAN: Stok barang '{$result['item']->name}' berada pada atau di bawah batas minimum (Sisa: {$result['item']->available_stock}, Min: {$result['item']->minimum_stock})!"
                : null;

            return response()->json([
                'success' => true,
                'message' => 'Pengambilan barang berhasil diproses dan stok telah dikurangi.',
                'data' => [
                    'retrieval_log' => $result['log'],
                    'remaining_stock' => $result['item']->available_stock,
                    'stock_status' => $result['item']->stock_status,
                    'item' => $result['item'],
                    'low_stock_alert' => $lowStockWarning,
                    'warning_message' => $warningMessage,
                ],
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Stock retrieval transaction failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses transaksi pengambilan barang.',
            ], 500);
        }
    }
}
