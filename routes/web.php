<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PeoplesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //pos
    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');

    //orders - sale
    Route::get('/orders-sales', [\App\Http\Controllers\OrdersController::class, 'index'])->name('orders.index');

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //setting
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

    // [!!! อัปเดตส่วนของ Reports !!!]
    // สมมติว่าคุณมี ReportsController ที่จะจัดการ view()
    // และเปลี่ยน 'index' (ของเดิม) ให้ชี้ไปที่ 'sales' เพื่อความชัดเจน

    // Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index'); // <-- ของเดิม

    //Inventory
    Route::get('/inventorys/manage-products', [InventoryController::class, 'manageProducts'])->name('inventorys.manage-products');
    Route::post('/inventorys/products', [InventoryController::class, 'storeProduct'])->name('inventorys.products.store');
    Route::put('/inventorys/products/{id}', [InventoryController::class, 'updateProduct'])->name('inventorys.products.update');
    Route::delete('/inventorys/products/{id}', [InventoryController::class, 'destroyProduct'])->name('inventorys.products.destroy');
    Route::post('/inventorys/products/bulk-delete', [InventoryController::class, 'bulkDestroyProducts'])->name('inventorys.products.bulk-delete');
    Route::get('/inventorys/categories', [InventoryController::class, 'categories'])->name('inventorys.categories');
    Route::post('/inventorys/categories', [InventoryController::class, 'storeCategory'])->name('inventorys.categories.store');
    Route::put('/inventorys/categories/{id}', [InventoryController::class, 'updateCategory'])->name('inventorys.categories.update');
    Route::delete('/inventorys/categories/{id}', [InventoryController::class, 'destroyCategory'])->name('inventorys.categories.destroy');
    Route::post('/inventorys/categories/bulk-delete', [InventoryController::class, 'bulkDestroyCategories'])->name('inventorys.categories.bulk-delete');
    Route::get('/inventorys/expiry-management', [InventoryController::class, 'expiryManagement'])->name('inventorys.expiry-management');
    Route::get('/inventorys/stock-adjustments', [InventoryController::class, 'stockAdjustments'])->name('inventorys.stock-adjustments');

    //report subpages
    Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/finance', [ReportsController::class, 'finance'])->name('reports.finance');
    Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');

    //people subpages
    Route::get('/peoples/patients-customer', [PeoplesController::class, 'patientscustomer'])->name('peoples.patients-customer');
    Route::get('/peoples/staff-user', [PeoplesController::class, 'staffuser'])->name('peoples.staff-user');
    Route::get('/peoples/recent', [PeoplesController::class, 'recent'])->name('peoples.recent');

    //Purchasing subpages
    Route::get('/purchasing/suppliers', [PurchaseController::class, 'suppliers'])->name('purchasing.suppliers');
    Route::post('/purchasing/suppliers', [PurchaseController::class, 'storeSupplier'])->name('purchasing.suppliers.store');
    Route::put('/purchasing/suppliers/{id}', [PurchaseController::class, 'updateSupplier'])->name('purchasing.suppliers.update');
    Route::delete('/purchasing/suppliers/{id}', [PurchaseController::class, 'destroySupplier'])->name('purchasing.suppliers.destroy');
    Route::post('/purchasing/suppliers/bulk-delete', [PurchaseController::class, 'bulkDestroySupplier'])->name('purchasing.suppliers.bulk_destroy');
    Route::get('/purchasing/purchase-orders', [PurchaseController::class, 'purchaseOrders'])->name('purchasing.purchaseOrders');
    Route::post('/purchasing/purchase-orders', [PurchaseController::class, 'storePurchaseOrder'])->name('purchasing.purchaseOrders.store');
    Route::put('/purchasing/purchase-orders/{id}', [PurchaseController::class, 'updatePurchaseOrder'])->name('purchasing.purchaseOrders.update');
    Route::delete('/purchasing/purchase-orders/{id}', [PurchaseController::class, 'destroyPurchaseOrder'])->name('purchasing.purchaseOrders.destroy');
    Route::post('/purchasing/purchase-orders/bulk-delete', [PurchaseController::class, 'bulkDestroyPurchaseOrder'])->name('purchasing.purchaseOrders.bulk_destroy');
    Route::get('/purchasing/goods-received', [PurchaseController::class, 'goodsReceived'])->name('purchasing.goodsReceived');
});

// Route สำหรับ Live Search (ที่ JavaScript เรียก)
Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('search.live');

// Route สำหรับ Search ปกติ (กด Enter)
Route::get('/search', [SearchController::class, 'fullSearch'])->name('search.full');

// Route สำหรับ AI Search (กด Atom)
Route::get('/ai-search', [SearchController::class, 'aiSearch'])->name('search.ai');

require __DIR__ . '/auth.php';
