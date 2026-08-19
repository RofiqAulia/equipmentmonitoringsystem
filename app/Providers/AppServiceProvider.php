<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Item;
use App\Models\ItemRequisition;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('items')) {
                    $lowStockCount = Item::whereColumn('available_stock', '<=', 'minimum_stock')->count();
                    $pendingReqCount = Schema::hasTable('item_requisitions') ? ItemRequisition::where('status', 'pending')->count() : 0;
                    $view->with('global_low_stock_count', $lowStockCount);
                    $view->with('global_pending_req_count', $pendingReqCount);
                }
            } catch (\Throwable $e) {
                $view->with('global_low_stock_count', 0);
                $view->with('global_pending_req_count', 0);
            }
        });
    }
}
