<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockRetrievalController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public / Quick Auth APIs
Route::post('/scan-item', [StockRetrievalController::class, 'scanItem']);
Route::post('/confirm-retrieval', [StockRetrievalController::class, 'confirmRetrieval']);

Route::get('/supervisors', [AuthController::class, 'getSupervisors']);
Route::get('/operators', [AuthController::class, 'getOperators']);

// Authenticated User / Operator routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/select-supervisor', [AuthController::class, 'selectSupervisor']);
});

// Admin Protected APIs
Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});
