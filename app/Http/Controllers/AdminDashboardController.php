<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RetrievalLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * GET /admin/dashboard (or /api/admin/dashboard)
     * Aggregate metrics, real-time activity log, inventory summary, and inventory table.
     */
    public function index(Request $request)
    {
        // 1. System Health Metrics
        $totalUsers = User::count();
        $adminsCount = User::where('role', 'admin')->count();
        $spvsCount = User::where('role', 'spv')->count();
        $operatorsCount = User::where('role', 'user')->count();
        $totalItems = Item::count();
        $todayRetrievalsCount = RetrievalLog::whereDate('picked_at', today())->count();
        $todayRetrievalsUnits = (int) RetrievalLog::whereDate('picked_at', today())->sum('quantity_picked');

        $systemHealth = [
            'total_users' => $totalUsers,
            'total_admins' => $adminsCount,
            'total_spvs' => $spvsCount,
            'active_operators' => $operatorsCount,
            'total_items' => $totalItems,
            'retrievals_today' => $todayRetrievalsCount,
            'total_qty_picked_today' => $todayRetrievalsUnits,
        ];

        // 2. Inventory Summary Counts
        $itemsCollection = Item::all();
        $inStockCount = $itemsCollection->filter(function($i) { return $i->available_stock > $i->minimum_stock; })->count();
        $lowStockCount = $itemsCollection->filter(function($i) { return $i->available_stock > 0 && $i->available_stock <= $i->minimum_stock; })->count();
        $outOfStockCount = $itemsCollection->filter(function($i) { return $i->available_stock <= 0; })->count();

        $inventorySummary = [
            'total_items' => $totalItems,
            'in_stock' => $inStockCount,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
        ];

        // 3. Activity Log Real-time (Latest 15 transactions)
        $recentRetrievals = RetrievalLog::with([
            'user:id,name,email,avatar',
            'supervisor:id,name,email,avatar',
            'item:id,sku,name,location_bin',
        ])
            ->orderBy('picked_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        // 4. Inventory List with Search & Status Filtering
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

        $perPage = (int) $request->input('per_page', -1);
        if ($perPage === -1) {
            $items = $query->latest('updated_at')->get();
        } else {
            $items = $query->latest('updated_at')->paginate($perPage > 0 ? $perPage : 10)->withQueryString();
        }

        $data = [
            'totalUsers' => $totalUsers,
            'adminsCount' => $adminsCount,
            'spvsCount' => $spvsCount,
            'supervisorsCount' => $spvsCount,
            'operatorsCount' => $operatorsCount,
            'totalItems' => $totalItems,
            'todayRetrievalsCount' => $todayRetrievalsCount,
            'todayRetrievalsUnits' => $todayRetrievalsUnits,
            'inStockCount' => $inStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'recentRetrievals' => $recentRetrievals,
            'system_health' => $systemHealth,
            'inventory_summary' => $inventorySummary,
            'activity_logs' => $recentRetrievals,
            'items' => $items,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return view('admin.dashboard', $data);
    }
}
