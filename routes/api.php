<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\ProductLotApiController;
use App\Http\Controllers\Api\GlobalSearchController;
use App\Http\Controllers\LineWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// LINE Webhook (Public with signature verification)
Route::post('/webhook/line', [LineWebhookController::class, 'handle']);

Route::middleware(['auth', 'staff'])->group(function () {
    // Global Search
    Route::get('/global-search', [GlobalSearchController::class, 'search']);

    // Dashboard stats
    Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);

    // Products (Staff can view)
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{product}', [ProductApiController::class, 'show']);
    Route::get('/products/{product}/lots', [ProductApiController::class, 'lots']);

    // Product Lots (Expiry)
    Route::get('/product-lots', [ProductLotApiController::class, 'index']);
    Route::get('/product-lots/export', [ProductLotApiController::class, 'export']);

    // Orders
    Route::apiResource('orders', OrderApiController::class);
    Route::post('/orders/{order}/refund', [OrderApiController::class, 'refund']);

    // Customers
    Route::get('/customers/search', [CustomerApiController::class, 'search']);
    Route::apiResource('customers', CustomerApiController::class);

    // Admin only API routes
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('products', ProductApiController::class)->except(['index', 'show']);
    });
});
