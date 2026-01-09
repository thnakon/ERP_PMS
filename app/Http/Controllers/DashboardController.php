<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceived;
use App\Models\PosShift;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // === KPI Statistics ===
        $stats = [
            // Today's Performance
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_completed' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count(),
            'today_customers' => Customer::whereDate('created_at', $today)->count(),

            // This Week
            'week_revenue' => Order::where('created_at', '>=', $startOfWeek)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'week_orders' => Order::where('created_at', '>=', $startOfWeek)->count(),

            // This Month
            'month_revenue' => Order::where('created_at', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'month_orders' => Order::where('created_at', '>=', $startOfMonth)->count(),

            // Inventory Alerts
            'low_stock' => Product::where('is_active', true)
                ->whereColumn('stock_qty', '<=', 'min_stock')
                ->count(),
            'expiring_critical' => ProductLot::where('expiry_date', '<=', $today->copy()->addDays(7))
                ->where('expiry_date', '>', $today)
                ->where('quantity', '>', 0)
                ->count(),
            'expiring_warning' => ProductLot::where('expiry_date', '<=', $today->copy()->addDays(30))
                ->where('expiry_date', '>', $today->copy()->addDays(7))
                ->where('quantity', '>', 0)
                ->count(),
            'expired' => ProductLot::where('expiry_date', '<', $today)
                ->where('quantity', '>', 0)
                ->count(),

            // Purchasing
            'pending_pos' => PurchaseOrder::where('status', 'pending')->count(),
            'pending_grs' => GoodsReceived::where('status', 'pending')->count(),

            // Totals
            'total_products' => Product::where('is_active', true)->count(),
            'total_customers' => Customer::count(),
            'total_users' => User::where('status', 'active')->count(),
        ];

        // === Average Ticket Size ===
        $stats['avg_ticket'] = $stats['today_orders'] > 0
            ? $stats['today_revenue'] / $stats['today_orders']
            : 0;

        // === Weekly Sales Chart Data ===
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            $chartData[] = [
                'label' => $date->format('D'),
                'date' => $date->format('d/m'),
                'value' => (float) $revenue,
            ];
        }
        $stats['chart_data'] = $chartData;

        // === Low Stock Products ===
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->orderBy('stock_qty', 'asc')
            ->limit(5)
            ->get();

        // === Expiring Products ===
        $expiringProducts = ProductLot::with('product')
            ->where('expiry_date', '<=', $today->copy()->addDays(30))
            ->where('expiry_date', '>', $today)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->limit(5)
            ->get();

        // === Recent Orders ===
        $recentOrders = Order::with(['customer', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // === Recent Activities ===
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(8)
            ->get();

        // === Top Selling Products (This Week) ===
        $topProducts = Product::withCount(['orderItems as sold_qty' => function ($query) use ($startOfWeek) {
            $query->whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('created_at', '>=', $startOfWeek)
                    ->where('status', 'completed');
            });
        }])
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        // === Current Shift ===
        $currentShift = PosShift::getCurrentShift(auth()->id());

        // === Today's Pharmacist on Duty ===
        $pharmacistOnDuty = User::where('role', 'pharmacist')
            ->where('status', 'active')
            ->first();

        // === Hourly Sales (Today) ===
        $hourlySales = [];
        for ($h = 8; $h <= 20; $h++) {
            $start = Carbon::today()->setHour($h);
            $end = $start->copy()->addHour();
            $revenue = Order::where('created_at', '>=', $start)
                ->where('created_at', '<', $end)
                ->where('status', 'completed')
                ->sum('total_amount');
            $hourlySales[] = [
                'hour' => sprintf('%02d:00', $h),
                'value' => (float) $revenue,
            ];
        }
        $stats['hourly_sales'] = $hourlySales;

        // === Staff-Specific Data ===
        $isAdmin = auth()->user()->isAdmin();
        $currentUserId = auth()->id();

        // My Today's Sales (for staff)
        $myTodayOrders = Order::whereDate('created_at', $today)
            ->where('user_id', $currentUserId)
            ->count();
        $myTodayRevenue = Order::whereDate('created_at', $today)
            ->where('user_id', $currentUserId)
            ->where('status', 'completed')
            ->sum('total_amount');

        $stats['my_today_orders'] = $myTodayOrders;
        $stats['my_today_revenue'] = $myTodayRevenue;

        // Today's Tasks for Staff
        $todaysTasks = collect();

        // Check for pending prescriptions
        $pendingPrescriptions = \App\Models\Prescription::where('status', 'pending')
            ->orWhere('status', 'approved')
            ->count();
        if ($pendingPrescriptions > 0) {
            $todaysTasks->push([
                'type' => 'prescription',
                'icon' => 'ph-prescription',
                'color' => 'purple',
                'title' => __('dashboard.pending_prescriptions'),
                'count' => $pendingPrescriptions,
                'link' => route('prescriptions.index') . '?status=pending',
            ]);
        }

        // Check for expiring products (critical)
        if ($stats['expiring_critical'] > 0) {
            $todaysTasks->push([
                'type' => 'expiry',
                'icon' => 'ph-calendar-x',
                'color' => 'red',
                'title' => __('dashboard.products_expiring_soon'),
                'count' => $stats['expiring_critical'],
                'link' => route('expiry.index'),
            ]);
        }

        // Check for low stock
        if ($stats['low_stock'] > 0) {
            $todaysTasks->push([
                'type' => 'stock',
                'icon' => 'ph-warning',
                'color' => 'orange',
                'title' => __('dashboard.low_stock_alert'),
                'count' => $stats['low_stock'],
                'link' => route('products.index') . '?filter=low_stock',
            ]);
        }

        // Check for goods to receive
        $pendingGr = \App\Models\GoodsReceived::where('status', 'pending')->count();
        if ($pendingGr > 0) {
            $todaysTasks->push([
                'type' => 'receiving',
                'icon' => 'ph-package',
                'color' => 'blue',
                'title' => __('dashboard.goods_to_receive'),
                'count' => $pendingGr,
                'link' => route('goods-received.index'),
            ]);
        }

        // Recent Shift Notes (for staff communication)
        $recentShiftNotes = \App\Models\ShiftNote::with('user')
            ->orderByDesc('is_pinned')
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'lowStockProducts',
            'expiringProducts',
            'recentOrders',
            'recentActivities',
            'topProducts',
            'currentShift',
            'pharmacistOnDuty',
            'isAdmin',
            'todaysTasks',
            'recentShiftNotes'
        ));
    }
}
