<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductLot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdditionalReportController extends Controller
{
    /**
     * Expiring products report (by month) - Uses product_lots table
     */
    public function expiringProducts(Request $request)
    {
        $months = $request->get('months', 6);

        // Get products expiring within the specified months from product_lots
        $expiryDate = Carbon::now()->addMonths($months);

        $expiringLots = ProductLot::with('product')
            ->where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>', Carbon::now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->map(function ($lot) {
                return (object) [
                    'id' => $lot->id,
                    'product_id' => $lot->product_id,
                    'name' => $lot->product->name ?? 'Unknown',
                    'name_th' => $lot->product->name_th ?? null,
                    'sku' => $lot->product->sku ?? '',
                    'lot_number' => $lot->lot_number,
                    'expiry_date' => $lot->expiry_date,
                    'stock_qty' => $lot->quantity,
                    'cost_price' => $lot->cost_price ?: ($lot->product->cost_price ?? 0),
                ];
            });

        // Group by month
        $groupedByMonth = $expiringLots->groupBy(function ($item) {
            return Carbon::parse($item->expiry_date)->format('Y-m');
        })->map(function ($items, $month) {
            $totalValue = $items->sum(function ($item) {
                return $item->stock_qty * $item->cost_price;
            });

            return [
                'month' => $month,
                'month_name' => Carbon::parse($month)->translatedFormat('F Y'),
                'products' => $items,
                'total_items' => $items->count(),
                'total_units' => $items->sum('stock_qty'),
                'total_value' => $totalValue,
            ];
        })->sortKeys();

        // Summary statistics
        $summary = [
            'total_products' => $expiringLots->count(),
            'total_units' => $expiringLots->sum('stock_qty'),
            'total_value' => $expiringLots->sum(function ($p) {
                return $p->stock_qty * $p->cost_price;
            }),
            'expired_count' => ProductLot::where('expiry_date', '<', Carbon::now())
                ->where('quantity', '>', 0)
                ->count(),
        ];

        // Expired products (already expired)
        $expiredProducts = ProductLot::with('product')
            ->where('expiry_date', '<', Carbon::now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($lot) {
                return (object) [
                    'id' => $lot->id,
                    'name' => $lot->product->name ?? 'Unknown',
                    'name_th' => $lot->product->name_th ?? null,
                    'sku' => $lot->product->sku ?? '',
                    'lot_number' => $lot->lot_number,
                    'expiry_date' => $lot->expiry_date,
                    'stock_qty' => $lot->quantity,
                ];
            });

        return view('reports.expiring-products', compact(
            'groupedByMonth',
            'summary',
            'expiredProducts',
            'months'
        ));
    }

    /**
     * Product profit/loss report
     */
    public function productProfit(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get product sales with profit calculation
        $productStats = OrderItem::select(
            'order_items.product_id',
            'products.name as product_name',
            'products.name_th as product_name_th',
            'products.sku',
            'products.cost_price',
            'categories.name as category_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.subtotal) as total_sales'),
            DB::raw('SUM(order_items.quantity * products.cost_price) as total_cost'),
            DB::raw('SUM(order_items.subtotal - (order_items.quantity * products.cost_price)) as profit')
        )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('order_items.product_id', 'products.name', 'products.name_th', 'products.sku', 'products.cost_price', 'categories.name')
            ->orderByDesc('profit')
            ->get()
            ->map(function ($item) {
                $item->profit_margin = $item->total_sales > 0
                    ? ($item->profit / $item->total_sales) * 100
                    : 0;
                return $item;
            });

        // Summary
        $summary = [
            'total_sales' => $productStats->sum('total_sales'),
            'total_cost' => $productStats->sum('total_cost'),
            'total_profit' => $productStats->sum('profit'),
            'profit_margin' => $productStats->sum('total_sales') > 0
                ? ($productStats->sum('profit') / $productStats->sum('total_sales')) * 100
                : 0,
            'product_count' => $productStats->count(),
            'profitable_count' => $productStats->where('profit', '>', 0)->count(),
            'loss_count' => $productStats->where('profit', '<', 0)->count(),
        ];

        // Top profitable products
        $topProfitable = $productStats->take(10);

        // Products with loss
        $lossProducts = $productStats->where('profit', '<', 0)->sortBy('profit')->take(10);

        // Group by category for pie chart
        $categoryProfit = $productStats->groupBy('category_name')->map(function ($items, $category) {
            return [
                'category' => $category ?: __('reports.uncategorized'),
                'total_sales' => $items->sum('total_sales'),
                'total_profit' => $items->sum('profit'),
                'product_count' => $items->count(),
            ];
        })->sortByDesc('total_profit')->values();

        return view('reports.product-profit', compact(
            'productStats',
            'summary',
            'topProfitable',
            'lossProducts',
            'categoryProfit',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Loyal customers report - Uses total_amount instead of total
     */
    public function loyalCustomers(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $minVisits = $request->get('min_visits', 3);

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get loyal customers (customers with multiple visits) - Using total_amount
        $loyalCustomers = Customer::select(
            'customers.*',
            DB::raw('COUNT(orders.id) as visit_count'),
            DB::raw('SUM(orders.total_amount) as total_spent'),
            DB::raw('AVG(orders.total_amount) as avg_order'),
            DB::raw('MAX(orders.created_at) as last_visit'),
            DB::raw('MIN(orders.created_at) as first_visit')
        )
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('customers.id')
            ->having('visit_count', '>=', $minVisits)
            ->orderByDesc('total_spent')
            ->get()
            ->map(function ($customer) use ($start, $end) {
                // Calculate retention days (days between first and last visit)
                $firstVisit = Carbon::parse($customer->first_visit);
                $lastVisit = Carbon::parse($customer->last_visit);
                $customer->retention_days = $firstVisit->diffInDays($lastVisit);

                // Calculate days since last visit
                $customer->days_since_last = Carbon::parse($customer->last_visit)->diffInDays(Carbon::now());

                // Get most purchased category for this customer
                $topCategory = OrderItem::select('categories.name', DB::raw('SUM(order_items.quantity) as qty'))
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->where('orders.customer_id', $customer->id)
                    ->where('orders.status', 'completed')
                    ->groupBy('categories.name')
                    ->orderByDesc('qty')
                    ->first();

                $customer->favorite_category = $topCategory ? $topCategory->name : '-';

                return $customer;
            });

        // Summary
        $totalCustomers = Customer::count();
        $customersInPeriod = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count('customer_id');

        $summary = [
            'total_customers' => $totalCustomers,
            'active_customers' => $customersInPeriod,
            'loyal_customers' => $loyalCustomers->count(),
            'loyalty_rate' => $customersInPeriod > 0
                ? ($loyalCustomers->count() / $customersInPeriod) * 100
                : 0,
            'total_revenue_loyal' => $loyalCustomers->sum('total_spent'),
            'avg_lifetime_value' => $loyalCustomers->avg('total_spent') ?? 0,
        ];

        // Customer tiers
        $tiers = [
            'vip' => $loyalCustomers->where('total_spent', '>=', 10000)->count(),
            'gold' => $loyalCustomers->where('total_spent', '>=', 5000)->where('total_spent', '<', 10000)->count(),
            'silver' => $loyalCustomers->where('total_spent', '>=', 2000)->where('total_spent', '<', 5000)->count(),
            'bronze' => $loyalCustomers->where('total_spent', '<', 2000)->count(),
        ];

        // At-risk customers (loyal but haven't visited in 30+ days)
        $atRiskCustomers = $loyalCustomers->where('days_since_last', '>=', 30)->sortByDesc('days_since_last')->take(10);

        // Monthly visit trends
        $monthlyVisits = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(DISTINCT customer_id) as unique_customers'),
            DB::raw('COUNT(*) as total_visits')
        )
            ->where('status', 'completed')
            ->whereNotNull('customer_id')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.loyal-customers', compact(
            'loyalCustomers',
            'summary',
            'tiers',
            'atRiskCustomers',
            'monthlyVisits',
            'startDate',
            'endDate',
            'minVisits'
        ));
    }
}
