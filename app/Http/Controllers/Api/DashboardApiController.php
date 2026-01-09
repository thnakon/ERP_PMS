<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductLot;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function stats()
    {
        $todayRevenue = Order::where('status', 'completed')
            ->whereDate('paid_at', today())
            ->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', today())->count();

        $lowStock = Product::whereColumn('stock_qty', '<=', 'min_stock')
            ->where('is_active', true)
            ->count();

        $expiringCritical = ProductLot::expiringWithin(7)->count();

        return response()->json([
            'today_revenue' => $todayRevenue,
            'today_orders' => $todayOrders,
            'low_stock' => $lowStock,
            'expiring_critical' => $expiringCritical,
        ]);
    }
}
