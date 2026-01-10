<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\GoodsReceivedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\Api\GlobalSearchController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ControlledDrugController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\AdditionalReportController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\MemberTierController;
use App\Http\Controllers\ShiftNoteController;
use App\Http\Controllers\MedicalCalculatorController;
use App\Http\Controllers\DrugInteractionController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Staff: All daily operations (POS, Orders, Products view, etc.)
| Admin: Full access including settings, reports, user management
|--------------------------------------------------------------------------
*/

// Language switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Test 403 error page
Route::get('/test-403', function () {
    abort(403);
});

// Public Landing Page
Route::get('/welcome', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::get('/terms', [\App\Http\Controllers\LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [\App\Http\Controllers\LandingController::class, 'privacy'])->name('privacy');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/register/verify', [AuthController::class, 'verifyEmail'])->name('register.verify');

/*
|--------------------------------------------------------------------------
| Staff Routes (All authenticated users: staff + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'staff'])->group(function () {
    // Global Search API
    Route::get('/api/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

    // AI Chat API
    Route::post('/api/ai-chat', [\App\Http\Controllers\AiChatController::class, 'chat'])->name('ai.chat');

    // =============================================
    // SALES OPERATIONS (All users)
    // =============================================

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Point of Sale
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        Route::post('/open-shift', [PosController::class, 'openShift'])->name('open-shift');
        Route::post('/close-shift', [PosController::class, 'closeShift'])->name('close-shift');
        Route::get('/search-products', [PosController::class, 'searchProducts'])->name('search-products');
        Route::get('/barcode', [PosController::class, 'getProductByBarcode'])->name('barcode');
        Route::get('/search-customers', [PosController::class, 'searchCustomers'])->name('search-customers');
        Route::get('/check-allergies', [PosController::class, 'checkAllergies'])->name('check-allergies');
        Route::post('/hold-order', [PosController::class, 'holdOrder'])->name('hold-order');
        Route::get('/held-orders', [PosController::class, 'getHeldOrders'])->name('held-orders');
        Route::post('/recall-order/{index}', [PosController::class, 'recallOrder'])->name('recall-order');
        Route::delete('/held-order/{index}', [PosController::class, 'deleteHeldOrder'])->name('delete-held-order');
        Route::get('/recent-sales', [PosController::class, 'getRecentSales'])->name('recent-sales');
        Route::get('/receipt/{order}', [PosController::class, 'printReceipt'])->name('receipt');
        Route::post('/acknowledge-alert', [PosController::class, 'acknowledgeAlert'])->name('acknowledge-alert');
    });

    // Orders (Staff can view and refund)
    Route::resource('orders', OrderController::class);
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::post('/orders/{order}/refund', [OrderController::class, 'refund'])->name('orders.refund');

    // Calendar (All users can view and add their own events)
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
    Route::get('/calendar/current-shift', [CalendarController::class, 'getCurrentShift'])->name('calendar.current-shift');
    Route::post('/calendar/add-shift', [CalendarController::class, 'addShift'])->name('calendar.add-shift');
    Route::post('/calendar/add-appointment', [CalendarController::class, 'addAppointment'])->name('calendar.add-appointment');

    // Messenger (All users)
    Route::prefix('messenger')->name('messenger.')->group(function () {
        Route::get('/', [MessengerController::class, 'index'])->name('index');
        Route::get('/{chatRoom}/messages', [MessengerController::class, 'getMessages'])->name('messages');
        Route::post('/{chatRoom}/send', [MessengerController::class, 'sendMessage'])->name('send');
        Route::post('/start-chat', [MessengerController::class, 'startChat'])->name('start-chat');
        Route::post('/create-group', [MessengerController::class, 'createGroup'])->name('create-group');
        Route::get('/delivery-logs', [MessengerController::class, 'deliveryLogs'])->name('delivery-logs');
        Route::post('/delivery-logs/{log}/resend', [MessengerController::class, 'resendDelivery'])->name('resend-delivery');
        Route::get('/search', [MessengerController::class, 'searchMessages'])->name('search');
    });

    // Notifications (All users)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/count', [NotificationController::class, 'count'])->name('count');
        Route::get('/settings', [NotificationController::class, 'settings'])->name('settings');
        Route::post('/settings', [NotificationController::class, 'saveSettings'])->name('settings.save');
        Route::post('/dismiss/{type}/{id}', [NotificationController::class, 'dismiss'])->name('dismiss');
        Route::post('/test-line', [NotificationController::class, 'testLine'])->name('test-line');
        Route::post('/test-email', [NotificationController::class, 'testEmail'])->name('test-email');
    });

    // =============================================
    // INVENTORY (Staff: View only for Products)
    // =============================================

    // Products (Staff can view only)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Expiry Management (All users)
    Route::prefix('expiry')->name('expiry.')->group(function () {
        Route::get('/', [ExpiryController::class, 'index'])->name('index');
        Route::get('/export', [ExpiryController::class, 'export'])->name('export');
    });

    // Prescriptions (All users)
    Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('index');
        Route::get('/create', [PrescriptionController::class, 'create'])->name('create');
        Route::post('/', [PrescriptionController::class, 'store'])->name('store');
        Route::get('/refill-reminders', [PrescriptionController::class, 'refillReminders'])->name('refill-reminders');
        Route::get('/search-products', [PrescriptionController::class, 'searchProducts'])->name('search-products');
        Route::post('/check-interactions', [PrescriptionController::class, 'checkInteractions'])->name('check-interactions');
        Route::get('/{prescription}', [PrescriptionController::class, 'show'])->name('show');
        Route::get('/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('edit');
        Route::put('/{prescription}', [PrescriptionController::class, 'update'])->name('update');
        Route::delete('/{prescription}', [PrescriptionController::class, 'destroy'])->name('destroy');
        Route::post('/{prescription}/dispense', [PrescriptionController::class, 'dispense'])->name('dispense');
        Route::post('/{prescription}/refill', [PrescriptionController::class, 'refill'])->name('refill');
    });

    // Controlled Drugs (All users - important for pharmacy operations)
    Route::prefix('controlled-drugs')->name('controlled-drugs.')->group(function () {
        Route::get('/', [ControlledDrugController::class, 'index'])->name('index');
        Route::get('/create', [ControlledDrugController::class, 'create'])->name('create');
        Route::post('/', [ControlledDrugController::class, 'store'])->name('store');
        Route::get('/pending', [ControlledDrugController::class, 'pending'])->name('pending');
        Route::get('/fda-report', [ControlledDrugController::class, 'fdaReport'])->name('fda-report');
        Route::get('/fda-report/export', [ControlledDrugController::class, 'exportFdaReport'])->name('fda-report.export');
        Route::get('/products', [ControlledDrugController::class, 'getProducts'])->name('products');
        Route::get('/{controlledDrug}', [ControlledDrugController::class, 'show'])->name('show');
        Route::post('/{controlledDrug}/approve', [ControlledDrugController::class, 'approve'])->name('approve');
        Route::post('/{controlledDrug}/reject', [ControlledDrugController::class, 'reject'])->name('reject');
        Route::delete('/{controlledDrug}', [ControlledDrugController::class, 'destroy'])->name('destroy');
    });

    // =============================================
    // TOOLS (All users)
    // =============================================

    // Barcode Scanner & Label Printing
    Route::prefix('barcode')->name('barcode.')->group(function () {
        Route::get('/', [BarcodeController::class, 'index'])->name('index');
        Route::get('/labels', [BarcodeController::class, 'labels'])->name('labels');
        Route::get('/lookup', [BarcodeController::class, 'lookup'])->name('lookup');
        Route::post('/generate-labels', [BarcodeController::class, 'generateLabels'])->name('generate-labels');
        Route::post('/add-to-cart', [BarcodeController::class, 'addToCart'])->name('add-to-cart');
    });

    // Shift Notes (All users)
    Route::resource('shift-notes', ShiftNoteController::class);
    Route::post('shift-notes/{shift_note}/toggle-pin', [ShiftNoteController::class, 'togglePin'])->name('shift-notes.toggle-pin');

    // Medical Calculators (All users)
    Route::get('/calculators', [MedicalCalculatorController::class, 'index'])->name('calculators.index');
    Route::post('/calculators/pediatric', [MedicalCalculatorController::class, 'pediatric'])->name('calculators.pediatric');
    Route::post('/calculators/bmi', [MedicalCalculatorController::class, 'bmi'])->name('calculators.bmi');
    Route::post('/calculators/egfr', [MedicalCalculatorController::class, 'egfr'])->name('calculators.egfr');

    // Drug Interactions (All users)
    Route::prefix('drug-interactions')->name('drug-interactions.')->group(function () {
        Route::get('/', [DrugInteractionController::class, 'index'])->name('index');
        Route::get('/search', [DrugInteractionController::class, 'search'])->name('search');
        Route::get('/suggest', [DrugInteractionController::class, 'suggest'])->name('suggest');
    });

    // =============================================
    // PURCHASING (Staff: Goods Received only)
    // =============================================

    // Goods Received (All users can receive goods)
    Route::get('/goods-received', [GoodsReceivedController::class, 'index'])->name('goods-received.index');
    Route::get('/goods-received/{goods_received}', [GoodsReceivedController::class, 'show'])->name('goods-received.show');

    // =============================================
    // PEOPLE & LOGS (Staff: Customers only)
    // =============================================

    // Customers (Staff can view, create, edit - but NOT delete)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    // =============================================
    // REPORTS (Staff: Expiring Products only)
    // =============================================
    Route::get('/reports/expiring-products', [AdditionalReportController::class, 'expiringProducts'])->name('reports.expiring-products');

    // =============================================
    // PROFILE (All users)
    // =============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'updateProfile'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::patch('/language', [ProfileController::class, 'updateLanguage'])->name('language');
        Route::patch('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin-Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {

        // =============================================
        // INVENTORY MANAGEMENT (Admin only)
        // =============================================

        // Categories (Admin only)
        Route::resource('categories', CategoryController::class);

        // Full Product Management (Admin only: create, edit, delete)
        Route::post('/products/bulk-update-category', [ProductController::class, 'bulkUpdateCategory'])->name('products.bulk_update_category');
        Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk_delete');
        Route::resource('products', ProductController::class)->except(['index', 'show']);

        // Stock Adjustments (Admin only - high risk)
        Route::resource('stock-adjustments', StockAdjustmentController::class)->only(['index', 'store', 'show']);

        // =============================================
        // PURCHASING (Admin only)
        // =============================================

        // Suppliers (Admin only)
        Route::resource('suppliers', SupplierController::class);

        // Purchase Orders (Admin only)
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('/purchase-orders/{purchase_order}/send', [PurchaseOrderController::class, 'send'])->name('purchase-orders.send');
        Route::post('/purchase-orders/{purchase_order}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');

        // Goods Received - Full access (Admin only: create, edit, delete)
        Route::get('/goods-received/create-from-po/{purchase_order}', [GoodsReceivedController::class, 'createFromPo'])->name('goods-received.create-from-po');
        Route::get('/goods-received/create', [GoodsReceivedController::class, 'create'])->name('goods-received.create');
        Route::post('/goods-received', [GoodsReceivedController::class, 'store'])->name('goods-received.store');
        Route::get('/goods-received/{goods_received}/edit', [GoodsReceivedController::class, 'edit'])->name('goods-received.edit');
        Route::put('/goods-received/{goods_received}', [GoodsReceivedController::class, 'update'])->name('goods-received.update');
        Route::delete('/goods-received/{goods_received}', [GoodsReceivedController::class, 'destroy'])->name('goods-received.destroy');

        // =============================================
        // PEOPLE & LOGS (Admin only)
        // =============================================

        // Customer Delete (Admin only)
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // Staff/Users Management (Admin only)
        Route::resource('users', UserController::class);

        // Activity Logs (Admin only)
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::post('/activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

        // =============================================
        // REPORTS & ANALYTICS (Admin only, except expiring-products)
        // =============================================
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales', [SalesReportController::class, 'index'])->name('sales');
            Route::get('/sales/export', [SalesReportController::class, 'export'])->name('sales.export');
            Route::get('/inventory', [InventoryReportController::class, 'index'])->name('inventory');
            Route::get('/inventory/export', [InventoryReportController::class, 'export'])->name('inventory.export');
            Route::get('/inventory/stock-card/{productId}', [InventoryReportController::class, 'stockCard'])->name('inventory.stock-card');
            Route::get('/finance', [FinanceReportController::class, 'index'])->name('finance');
            Route::get('/finance/export', [FinanceReportController::class, 'export'])->name('finance.export');
            Route::get('/product-profit', [AdditionalReportController::class, 'productProfit'])->name('product-profit');
            Route::get('/loyal-customers', [AdditionalReportController::class, 'loyalCustomers'])->name('loyal-customers');
        });

        // =============================================
        // PROMOTIONS & MARKETING (Admin only)
        // =============================================

        // Promotions & Discounts
        Route::prefix('promotions')->name('promotions.')->group(function () {
            Route::get('/', [PromotionController::class, 'index'])->name('index');
            Route::get('/create', [PromotionController::class, 'create'])->name('create');
            Route::post('/', [PromotionController::class, 'store'])->name('store');
            Route::get('/{promotion}', [PromotionController::class, 'show'])->name('show');
            Route::get('/{promotion}/edit', [PromotionController::class, 'edit'])->name('edit');
            Route::put('/{promotion}', [PromotionController::class, 'update'])->name('update');
            Route::delete('/{promotion}', [PromotionController::class, 'destroy'])->name('destroy');
            Route::post('/{promotion}/toggle', [PromotionController::class, 'toggle'])->name('toggle');
        });

        // API endpoints for promotions (used by POS)
        Route::get('/api/promotions/active', [PromotionController::class, 'getActivePromotions'])->name('api.promotions.active');
        Route::post('/api/promotions/apply-code', [PromotionController::class, 'applyCode'])->name('api.promotions.apply-code');
        Route::post('/api/promotions/calculate', [PromotionController::class, 'calculateDiscounts'])->name('api.promotions.calculate');

        // Bundles
        Route::prefix('bundles')->name('bundles.')->group(function () {
            Route::get('/', [BundleController::class, 'index'])->name('index');
            Route::get('/create', [BundleController::class, 'create'])->name('create');
            Route::post('/', [BundleController::class, 'store'])->name('store');
            Route::get('/{bundle}', [BundleController::class, 'show'])->name('show');
            Route::get('/{bundle}/edit', [BundleController::class, 'edit'])->name('edit');
            Route::put('/{bundle}', [BundleController::class, 'update'])->name('update');
            Route::delete('/{bundle}', [BundleController::class, 'destroy'])->name('destroy');
            Route::post('/{bundle}/toggle', [BundleController::class, 'toggle'])->name('toggle');
        });

        // API endpoints for bundles (used by POS)
        Route::get('/api/bundles/available', [BundleController::class, 'getAvailableBundles'])->name('api.bundles.available');
        Route::post('/api/bundles/{bundle}/add-to-cart', [BundleController::class, 'addToCart'])->name('api.bundles.add-to-cart');

        // Member Tiers
        Route::prefix('member-tiers')->name('member-tiers.')->group(function () {
            Route::get('/', [MemberTierController::class, 'index'])->name('index');
            Route::get('/create', [MemberTierController::class, 'create'])->name('create');
            Route::post('/', [MemberTierController::class, 'store'])->name('store');
            Route::get('/{memberTier}/edit', [MemberTierController::class, 'edit'])->name('edit');
            Route::put('/{memberTier}', [MemberTierController::class, 'update'])->name('update');
            Route::delete('/{memberTier}', [MemberTierController::class, 'destroy'])->name('destroy');
            Route::post('/recalculate-all', [MemberTierController::class, 'recalculateAll'])->name('recalculate-all');
            Route::get('/statistics', [MemberTierController::class, 'statistics'])->name('statistics');
        });

        // =============================================
        // SETTINGS (Admin only)
        // =============================================
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/store', [SettingsController::class, 'updateStore'])->name('store');
            Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial');
            Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications');
            Route::post('/loyalty', [SettingsController::class, 'updateLoyalty'])->name('loyalty');
            Route::post('/receipt', [SettingsController::class, 'updateReceipt'])->name('receipt');

            // Hardware Settings
            Route::get('/hardware', [HardwareController::class, 'index'])->name('hardware.index');
            Route::post('/hardware/printer', [HardwareController::class, 'updatePrinter'])->name('hardware.printer');
            Route::post('/hardware/cash-drawer', [HardwareController::class, 'updateCashDrawer'])->name('hardware.cash-drawer');
            Route::post('/hardware/barcode-scanner', [HardwareController::class, 'updateBarcodeScanner'])->name('hardware.barcode-scanner');
            Route::post('/hardware/test-printer', [HardwareController::class, 'testPrinter'])->name('hardware.test-printer');
            Route::post('/hardware/test-cash-drawer', [HardwareController::class, 'testCashDrawer'])->name('hardware.test-cash-drawer');

            // Backup Settings
            Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup/settings', [BackupController::class, 'updateSettings'])->name('backup.settings');
            Route::post('/backup/create', [BackupController::class, 'createBackup'])->name('backup.create');
            Route::get('/backup/{backup}/download', [BackupController::class, 'download'])->name('backup.download');
            Route::delete('/backup/{backup}', [BackupController::class, 'destroy'])->name('backup.destroy');
        });
    });
});
