<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceived;
use App\Models\Customer;
use App\Models\User;
use App\Models\Supplier;
use App\Models\StockAdjustment;
use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\DeliveryLog;
use App\Models\CalendarEvent;
use App\Models\Backup;
use App\Models\Category;
use App\Models\Prescription;
use Carbon\Carbon;

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
        // Register Product Stock Observer for real-time LINE notifications
        Product::observe(\App\Observers\ProductStockObserver::class);

        // Share sidebar badge counts with sidebar
        View::composer('components.sidebar', function ($view) {
            $userId = auth()->id();
            $today = Carbon::today();

            // Sales Operations
            $todayOrders = Order::whereDate('created_at', $today)->count();

            // Calendar - upcoming events today
            $todayEvents = CalendarEvent::whereDate('start_time', $today)->count();

            // Messenger - unread messages
            $unreadMessages = 0;
            if ($userId) {
                $userRooms = ChatRoom::whereHas('participants', fn($q) => $q->where('user_id', $userId))->pluck('id');
                $unreadMessages = Message::whereIn('chat_room_id', $userRooms)
                    ->where('sender_id', '!=', $userId)
                    ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $userId))
                    ->count();
            }

            // Inventory
            $lowStockCount = Product::where('is_active', true)
                ->whereColumn('stock_qty', '<=', 'min_stock')
                ->count();

            $expiringCount = ProductLot::where('expiry_date', '<=', $today->copy()->addDays(30))
                ->where('expiry_date', '>', $today)
                ->where('quantity', '>', 0)
                ->count();

            $expiredCount = ProductLot::where('expiry_date', '<', $today)
                ->where('quantity', '>', 0)
                ->count();

            // Stock adjustments - total count (recent 7 days)
            $recentAdjustments = StockAdjustment::where('created_at', '>=', $today->copy()->subDays(7))->count();

            // Categories count
            $categoriesCount = Category::count();

            // Purchasing - pending counts
            $pendingPOs = PurchaseOrder::where('status', 'pending')->count();
            $allPOs = PurchaseOrder::count();
            $pendingGRs = GoodsReceived::where('status', 'pending')->count();
            $allGRs = GoodsReceived::count();

            // Suppliers with pending orders
            $suppliersWithPending = Supplier::whereHas('purchaseOrders', fn($q) => $q->where('status', 'pending'))->count();

            // People & Logs - total counts
            $totalCustomers = Customer::count();
            $activeUsers = User::where('status', 'active')->count();
            $recentActivities = ActivityLog::where('created_at', '>=', $today->copy()->subDays(7))->count();

            // Backups - total count
            $totalBackups = Backup::count();

            // Notifications / Delivery
            $pendingDeliveries = DeliveryLog::where('status', 'pending')->count();

            // Share all counts
            $view->with('sidebarBadges', [
                // Sales Operations
                'dashboard' => $todayOrders,
                'pos' => 0,
                'orders' => $todayOrders,
                'calendar' => $todayEvents,
                'messenger' => $unreadMessages,
                'prescriptions' => Prescription::where('status', 'pending')->count(),
                'controlled_drugs' => \App\Models\ControlledDrugLog::where('status', 'pending')->count(),

                // Inventory
                'products' => $lowStockCount,
                'stock_adjustments' => $recentAdjustments,
                'expiry' => $expiringCount + $expiredCount,
                'categories' => $categoriesCount,

                // Purchasing
                'suppliers' => $suppliersWithPending ?: Supplier::count(),
                'purchase_orders' => $pendingPOs ?: $allPOs,
                'goods_received' => $pendingGRs ?: $allGRs,

                // People & Logs
                'customers' => $totalCustomers,
                'users' => $activeUsers,
                'activity_logs' => $recentActivities,

                // Reports (no badges needed)
                'reports_sales' => 0,
                'reports_inventory' => 0,
                'reports_finance' => 0,

                // Settings
                'profile' => 1,
                'settings' => 0,
                'hardware' => 0,
                'backup' => $totalBackups,

                // Extra for notification center
                'notifications' => $pendingDeliveries,
            ]);
        });

        // Share notification data with header
        View::composer('components.header', function ($view) {
            $userId = auth()->id();
            $today = Carbon::today();
            $notifications = collect();

            // 1. Today's Orders
            $todayOrders = Order::whereDate('created_at', $today)->count();
            if ($todayOrders > 0) {
                $notifications->push([
                    'type' => 'order',
                    'icon' => 'ph-fill ph-receipt',
                    'color' => 'bg-green-100 text-green-600',
                    'title' => __('notifications.new_orders'),
                    'message' => $todayOrders . ' ' . __('notifications.orders_today'),
                    'link' => route('orders.index'),
                    'time' => now(),
                ]);
            }

            // 2. Low Stock Alerts
            $lowStockCount = Product::where('is_active', true)
                ->whereColumn('stock_qty', '<=', 'min_stock')
                ->count();
            if ($lowStockCount > 0) {
                $notifications->push([
                    'type' => 'low_stock',
                    'icon' => 'ph-fill ph-warning',
                    'color' => 'bg-orange-100 text-orange-600',
                    'title' => __('notifications.low_stock'),
                    'message' => $lowStockCount . ' ' . __('notifications.products_low_stock'),
                    'link' => route('products.index', ['filter' => 'low_stock']),
                    'time' => now(),
                ]);
            }

            // 3. Expiring Products
            $expiringCount = ProductLot::where('expiry_date', '<=', $today->copy()->addDays(30))
                ->where('expiry_date', '>', $today)
                ->where('quantity', '>', 0)
                ->count();
            if ($expiringCount > 0) {
                $notifications->push([
                    'type' => 'expiry',
                    'icon' => 'ph-fill ph-calendar-x',
                    'color' => 'bg-red-100 text-red-600',
                    'title' => __('notifications.expiring_soon'),
                    'message' => $expiringCount . ' ' . __('notifications.products_expiring'),
                    'link' => route('expiry.index'),
                    'time' => now(),
                ]);
            }

            // 4. Expired Products
            $expiredCount = ProductLot::where('expiry_date', '<', $today)
                ->where('quantity', '>', 0)
                ->count();
            if ($expiredCount > 0) {
                $notifications->push([
                    'type' => 'expired',
                    'icon' => 'ph-fill ph-skull',
                    'color' => 'bg-red-100 text-red-700',
                    'title' => __('notifications.expired'),
                    'message' => $expiredCount . ' ' . __('notifications.products_expired'),
                    'link' => route('expiry.index'),
                    'time' => now(),
                ]);
            }

            // 5. Unread Messages
            $unreadMessages = 0;
            if ($userId) {
                $userRooms = ChatRoom::whereHas('participants', fn($q) => $q->where('user_id', $userId))->pluck('id');
                $unreadMessages = Message::whereIn('chat_room_id', $userRooms)
                    ->where('sender_id', '!=', $userId)
                    ->whereDoesntHave('reads', fn($q) => $q->where('user_id', $userId))
                    ->count();
            }
            if ($unreadMessages > 0) {
                $notifications->push([
                    'type' => 'message',
                    'icon' => 'ph-fill ph-chat-circle-text',
                    'color' => 'bg-blue-100 text-blue-600',
                    'title' => __('notifications.new_messages'),
                    'message' => $unreadMessages . ' ' . __('notifications.unread_messages'),
                    'link' => route('messenger.index'),
                    'time' => now(),
                ]);
            }

            // 6. Pending Purchase Orders
            $pendingPOs = PurchaseOrder::where('status', 'pending')->count();
            if ($pendingPOs > 0) {
                $notifications->push([
                    'type' => 'purchase_order',
                    'icon' => 'ph-fill ph-shopping-cart',
                    'color' => 'bg-purple-100 text-purple-600',
                    'title' => __('notifications.pending_po'),
                    'message' => $pendingPOs . ' ' . __('notifications.po_awaiting'),
                    'link' => route('purchase-orders.index', ['status' => 'pending']),
                    'time' => now(),
                ]);
            }

            // 7. Pending Goods Received
            $pendingGRs = GoodsReceived::where('status', 'pending')->count();
            if ($pendingGRs > 0) {
                $notifications->push([
                    'type' => 'goods_received',
                    'icon' => 'ph-fill ph-package',
                    'color' => 'bg-indigo-100 text-indigo-600',
                    'title' => __('notifications.pending_gr'),
                    'message' => $pendingGRs . ' ' . __('notifications.gr_awaiting'),
                    'link' => route('goods-received.index', ['status' => 'pending']),
                    'time' => now(),
                ]);
            }

            // 8. Today's Events
            $todayEvents = CalendarEvent::whereDate('start_time', $today)->count();
            if ($todayEvents > 0) {
                $notifications->push([
                    'type' => 'calendar',
                    'icon' => 'ph-fill ph-calendar-check',
                    'color' => 'bg-teal-100 text-teal-600',
                    'title' => __('notifications.todays_events'),
                    'message' => $todayEvents . ' ' . __('notifications.events_scheduled'),
                    'link' => route('calendar.index'),
                    'time' => now(),
                ]);
            }

            // 9. Recent Activity (last hour)
            $recentActivities = ActivityLog::where('created_at', '>=', now()->subHour())->count();
            if ($recentActivities > 0) {
                $notifications->push([
                    'type' => 'activity',
                    'icon' => 'ph-fill ph-clock-counter-clockwise',
                    'color' => 'bg-gray-100 text-gray-600',
                    'title' => __('notifications.recent_activity'),
                    'message' => $recentActivities . ' ' . __('notifications.actions_last_hour'),
                    'link' => route('activity-logs.index'),
                    'time' => now(),
                ]);
            }

            // 10. New Customers Today
            $newCustomers = Customer::whereDate('created_at', $today)->count();
            if ($newCustomers > 0) {
                $notifications->push([
                    'type' => 'customer',
                    'icon' => 'ph-fill ph-user-plus',
                    'color' => 'bg-emerald-100 text-emerald-600',
                    'title' => __('notifications.new_customers'),
                    'message' => $newCustomers . ' ' . __('notifications.customers_registered'),
                    'link' => route('customers.index'),
                    'time' => now(),
                ]);
            }

            // Count user's recent activities (last 7 days)
            $recentActivityCount = 0;
            if (auth()->check()) {
                $recentActivityCount = ActivityLog::where('user_id', auth()->id())
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
            }

            $view->with([
                'headerNotifications' => $notifications->take(8),
                'notificationCount' => $notifications->count(),
                'recentActivityCount' => $recentActivityCount,
            ]);
        });
    }
}
