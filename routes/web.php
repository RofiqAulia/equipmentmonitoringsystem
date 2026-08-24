<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockRetrievalController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin() 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('stock.retrieval');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/captcha/refresh', [AuthController::class, 'generateCaptcha'])->name('captcha.refresh');

// Operator Quick Auth & SPV Selection
Route::post('/user/quick-auth', [AuthController::class, 'quickUserAuth'])->name('user.quick-auth');
Route::post('/user/select-supervisor', [AuthController::class, 'selectSupervisor'])->name('user.select-spv');
Route::get('/supervisors', [AuthController::class, 'getSupervisors']);

// Stock Retrieval Routes
Route::middleware('auth')->group(function () {
    Route::get('/stock/retrieval', function () {
        return view('stock.retrieval');
    })->name('stock.retrieval');

    Route::post('/stock/scan', [StockRetrievalController::class, 'scanItem'])->name('stock.scan');
    Route::post('/stock/confirm', [StockRetrievalController::class, 'confirmRetrieval'])->name('stock.confirm');
});

use App\Http\Controllers\AdminStockController;
use App\Http\Controllers\ItemRequisitionController;

// Admin Dashboard & Management Routes
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Stock Input & Restock Barang
    Route::get('/admin/stock/input', [AdminStockController::class, 'inputForm'])->name('admin.stock.input');
    Route::post('/admin/stock/input', [AdminStockController::class, 'storeStock'])->name('admin.stock.store');
    
    // Pengajuan Barang (Procurement Requisitions)
    Route::get('/admin/requisitions', [ItemRequisitionController::class, 'index'])->name('admin.requisitions.index');
    Route::post('/admin/requisitions', [ItemRequisitionController::class, 'store'])->name('admin.requisitions.store');
    Route::patch('/admin/requisitions/{requisition}/status', [ItemRequisitionController::class, 'updateStatus'])->name('admin.requisitions.update-status');
    
    // Deteksi Barang Menipis (Low Stock Detector)
    Route::get('/admin/low-stock', [AdminStockController::class, 'lowStockDetector'])->name('admin.low-stock');
});
