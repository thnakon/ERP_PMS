<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPageVisit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Batch;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use App\Models\Purchase;

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
        View::composer('layouts.sidebar', function ($view) {
            $badgeCounts = [
                'manage_products' => 0,
                'categories' => 0,
                'expiry_management' => 0,
                'stock_adjustments' => 0,
                'suppliers' => 0,
                'purchase_orders' => 0,
                'goods_received' => 0,
            ];

            if (Auth::check()) {
                $user = Auth::user();
                $visits = UserPageVisit::where('user_id', $user->id)->pluck('last_visited_at', 'route_name');

                // Helper to get last visit or default to user creation (or now if we want to hide old stuff)
                // If we want "New since last visit", default should be "Now" (so 0 count) if never visited?
                // Or default to "User Created At" (so everything is new)?
                // User said: "Someone adds a new category... another person sees notification".
                // This implies if I haven't visited "Categories" yet, I should see ALL categories as new? Or just recent ones?
                // Usually "Unread" means "Since I last looked". If I never looked, everything is unread.
                // Let's use User->created_at as fallback.
                $getLastVisit = function ($route) use ($visits, $user) {
                    return $visits[$route] ?? $user->created_at ?? '1970-01-01 00:00:00';
                };

                // 1. Manage Products (New Products)
                $lastVisitProducts = $getLastVisit('inventorys.manage-products');
                $badgeCounts['manage_products'] = Product::where('created_at', '>', $lastVisitProducts)->count();

                // 2. Categories (New Categories)
                $lastVisitCategories = $getLastVisit('inventorys.categories');
                $badgeCounts['categories'] = Category::where('created_at', '>', $lastVisitCategories)->count();

                // 3. Expiry Management (New Batches)
                $lastVisitExpiry = $getLastVisit('inventorys.expiry-management');
                $badgeCounts['expiry_management'] = Batch::where('created_at', '>', $lastVisitExpiry)->count();

                // 4. Stock Adjustments (New Adjustments)
                $lastVisitAdjustments = $getLastVisit('inventorys.stock-adjustments');
                $badgeCounts['stock_adjustments'] = StockAdjustment::where('created_at', '>', $lastVisitAdjustments)->count();

                // 5. Suppliers (New Suppliers)
                $lastVisitSuppliers = $getLastVisit('purchasing.suppliers');
                $badgeCounts['suppliers'] = Supplier::where('created_at', '>', $lastVisitSuppliers)->count();

                // 6. Purchase Orders (New POs)
                $lastVisitPOs = $getLastVisit('purchasing.purchaseOrders');
                $badgeCounts['purchase_orders'] = Purchase::where('created_at', '>', $lastVisitPOs)->count();

                // 7. Goods Received (New Completed POs)
                $lastVisitGR = $getLastVisit('purchasing.goodsReceived');
                $badgeCounts['goods_received'] = Purchase::where('status', 'completed')
                    ->where('updated_at', '>', $lastVisitGR) // Use updated_at for completion time approx
                    ->count();
                // Notifications Count (Unread)
                $lastRead = Auth::user()->last_read_notifications_at ?? '1970-01-01 00:00:00';
                $badgeCounts['notifications'] = \App\Models\ActivityLog::where('created_at', '>', $lastRead)->count();
            }

            $view->with('badgeCounts', $badgeCounts);
        });

        // Register Global Observer
        \App\Models\User::observe(\App\Observers\GlobalActivityObserver::class);
        \App\Models\Product::observe(\App\Observers\GlobalActivityObserver::class);
        \App\Models\Category::observe(\App\Observers\GlobalActivityObserver::class);
        \App\Models\Supplier::observe(\App\Observers\GlobalActivityObserver::class);
        \App\Models\StockAdjustment::observe(\App\Observers\GlobalActivityObserver::class);
        if (class_exists(\App\Models\Batch::class)) {
            \App\Models\Batch::observe(\App\Observers\GlobalActivityObserver::class);
        }
        if (class_exists(\App\Models\Purchase::class)) {
            \App\Models\Purchase::observe(\App\Observers\GlobalActivityObserver::class);
        }

        // View Composer for Header Notifications
        View::composer('layouts.header', function ($view) {
            $notifications = [];
            $unreadCount = 0;
            if (Auth::check()) {
                $notifications = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
                $lastRead = Auth::user()->last_read_notifications_at ?? '1970-01-01 00:00:00';
                $unreadCount = \App\Models\ActivityLog::where('created_at', '>', $lastRead)->count();
            }
            $view->with('notifications', $notifications)->with('unreadCount', $unreadCount);
        });
    }
}
