<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Master Data
Route::apiResource('categories', CategoryController::class);
Route::apiResource('units', UnitController::class);
Route::apiResource('suppliers', SupplierController::class);

// Products & Inventory
Route::apiResource('products', ProductController::class);
Route::get('batches', [BatchController::class, 'index']);
Route::put('batches/{batch}', [BatchController::class, 'update']);

// Transactions
Route::post('sales', [SaleController::class, 'store']);
Route::get('sales', [SaleController::class, 'index']);
Route::get('sales/{sale}', [SaleController::class, 'show']);

Route::post('purchases', [PurchaseController::class, 'store']);
Route::get('purchases', [PurchaseController::class, 'index']);

// Reports
Route::get('reports/daily-sales', [ReportController::class, 'dailySales']);
Route::get('reports/low-stock', [ReportController::class, 'lowStock']);
