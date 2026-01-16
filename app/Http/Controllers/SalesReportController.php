<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    /**
     * Display the sales report page.
     */
    public function index(Request $request)
    {
        // Date range filter - default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Key Metrics
        $metrics = $this->getKeyMetrics($start, $end);

        // Time-based analysis
        $hourlyData = $this->getHourlySales($start, $end);
        $dailyData = $this->getDailySales($start, $end);
        $monthlyData = $this->getMonthlySales($start, $end);

        // Product analysis
        $topProducts = $this->getTopProducts($start, $end, 10);
        $deadStock = $this->getDeadStock($start, $end, 10);

        // Category analysis
        $categoryData = $this->getCategorySales($start, $end);

        // Staff performance
        $staffSales = $this->getStaffSales($start, $end);

        // Customer analysis
        $customerAnalysis = $this->getCustomerAnalysis($start, $end);

        return view('reports.sales', compact(
            'startDate',
            'endDate',
            'metrics',
            'hourlyData',
            'dailyData',
            'monthlyData',
            'topProducts',
            'deadStock',
            'categoryData',
            'staffSales',
            'customerAnalysis'
        ));
    }

    /**
     * Export sales report.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->input('format', 'excel');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $metrics = $this->getKeyMetrics($start, $end);
        $topProducts = $this->getTopProducts($start, $end, 10);
        $categoryData = $this->getCategorySales($start, $end);
        $staffSales = $this->getStaffSales($start, $end);

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.sales-pdf', compact(
                'startDate',
                'endDate',
                'metrics',
                'topProducts',
                'categoryData',
                'staffSales'
            ));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download($filename . '.pdf');
        }

        // Excel/CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($metrics, $topProducts, $categoryData, $staffSales, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Report Header
            fputcsv($file, ['Sales Report']);
            fputcsv($file, ['Period:', $startDate, 'to', $endDate]);
            fputcsv($file, []);

            // Key Metrics
            fputcsv($file, ['KEY METRICS']);
            fputcsv($file, ['Net Sales', number_format($metrics['net_sales'], 2)]);
            fputcsv($file, ['Gross Profit', number_format($metrics['gross_profit'], 2)]);
            fputcsv($file, ['Profit Margin', number_format($metrics['profit_margin'], 1) . '%']);
            fputcsv($file, ['Transactions', $metrics['transaction_count']]);
            fputcsv($file, ['Average Basket', number_format($metrics['average_basket'], 2)]);
            fputcsv($file, ['Total Discount', number_format($metrics['total_discount'], 2)]);
            fputcsv($file, []);

            // Top Products
            fputcsv($file, ['TOP SELLING PRODUCTS']);
            fputcsv($file, ['Rank', 'Product', 'Quantity Sold', 'Revenue']);
            foreach ($topProducts as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product['product_name'],
                    $product['total_quantity'],
                    number_format($product['total_sales'], 2)
                ]);
            }
            fputcsv($file, []);

            // Category Sales
            fputcsv($file, ['SALES BY CATEGORY']);
            fputcsv($file, ['Category', 'Items Sold', 'Revenue']);
            foreach ($categoryData as $cat) {
                fputcsv($file, [
                    $cat['name'],
                    $cat['total_quantity'],
                    number_format($cat['total_sales'], 2)
                ]);
            }
            fputcsv($file, []);

            // Staff Performance
            fputcsv($file, ['STAFF PERFORMANCE']);
            fputcsv($file, ['Staff', 'Transactions', 'Total Sales', 'Average Sale']);
            foreach ($staffSales as $staff) {
                fputcsv($file, [
                    $staff['name'],
                    $staff['transaction_count'],
                    number_format($staff['total_sales'], 2),
                    number_format($staff['average_sale'], 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get key metrics for the period.
     */
    private function getKeyMetrics(Carbon $start, Carbon $end): array
    {
        $grossSales = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('subtotal');

        $totalDiscount = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('discount');

        $netSales = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total_amount');

        $transactionCount = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->count();

        $averageBasket = $transactionCount > 0 ? $netSales / $transactionCount : 0;

        // Calculate COGS (Cost of Goods Sold)
        $cogs = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * products.cost_price'));

        $grossProfit = $netSales - $cogs;
        $profitMargin = $netSales > 0 ? ($grossProfit / $netSales) * 100 : 0;

        // Compare with previous period
        $periodDays = $start->diffInDays($end) + 1;
        $prevStart = $start->copy()->subDays($periodDays);
        $prevEnd = $end->copy()->subDays($periodDays);

        $prevNetSales = Order::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', 'completed')
            ->sum('total_amount');

        $salesGrowth = $prevNetSales > 0 ? (($netSales - $prevNetSales) / $prevNetSales) * 100 : 0;

        return [
            'gross_sales' => $grossSales,
            'total_discount' => $totalDiscount,
            'net_sales' => $netSales,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'transaction_count' => $transactionCount,
            'average_basket' => $averageBasket,
            'sales_growth' => $salesGrowth,
        ];
    }

    /**
     * Get hourly sales data.
     */
    private function getHourlySales(Carbon $start, Carbon $end): array
    {
        $data = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(total_amount) as sales'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $result = [];
        for ($i = 0; $i < 24; $i++) {
            $result[] = [
                'hour' => sprintf('%02d:00', $i),
                'sales' => $data->get($i)->sales ?? 0,
                'transactions' => $data->get($i)->transactions ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Get daily sales data.
     */
    private function getDailySales(Carbon $start, Carbon $end): array
    {
        return Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as sales'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Get monthly sales data.
     */
    private function getMonthlySales(Carbon $start, Carbon $end): array
    {
        return Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as sales'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->label = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
                return $item;
            })
            ->toArray();
    }

    /**
     * Get top selling products.
     */
    private function getTopProducts(Carbon $start, Carbon $end, int $limit): array
    {
        return OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_sales')
            )
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get dead stock (products not sold in period).
     */
    private function getDeadStock(Carbon $start, Carbon $end, int $limit): array
    {
        $soldProductIds = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })->pluck('product_id')->unique();

        return Product::whereNotIn('id', $soldProductIds)
            ->where('stock_qty', '>', 0)
            ->select('id', 'name', 'sku', 'stock_qty', 'cost_price')
            ->orderByDesc('stock_qty')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->stock_value = $product->stock_qty * $product->cost_price;
                return $product;
            })
            ->toArray();
    }

    /**
     * Get sales by category.
     */
    private function getCategorySales(Carbon $start, Carbon $end): array
    {
        return OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.subtotal) as total_sales'),
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get()
            ->toArray();
    }

    /**
     * Get staff sales performance.
     */
    private function getStaffSales(Carbon $start, Carbon $end): array
    {
        return Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->whereNotNull('orders.user_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                DB::raw('SUM(orders.total_amount) as total_sales'),
                DB::raw('COUNT(orders.id) as transaction_count'),
                DB::raw('AVG(orders.total_amount) as average_sale')
            )
            ->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderByDesc('total_sales')
            ->get()
            ->toArray();
    }

    /**
     * Get customer analysis (member vs walk-in).
     */
    private function getCustomerAnalysis(Carbon $start, Carbon $end): array
    {
        $memberOrders = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->whereNotNull('orders.customer_id');

        $walkInOrders = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->whereNull('orders.customer_id');

        $memberSales = (clone $memberOrders)->sum('total_amount');
        $memberCount = (clone $memberOrders)->count();
        $walkInSales = (clone $walkInOrders)->sum('total_amount');
        $walkInCount = (clone $walkInOrders)->count();

        $totalSales = $memberSales + $walkInSales;
        $totalCount = $memberCount + $walkInCount;

        // Top customers
        $topCustomers = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->whereNotNull('orders.customer_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone',
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('COUNT(orders.id) as visit_count')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get()
            ->toArray();

        return [
            'member' => [
                'sales' => $memberSales,
                'count' => $memberCount,
                'percentage' => $totalSales > 0 ? ($memberSales / $totalSales) * 100 : 0,
            ],
            'walk_in' => [
                'sales' => $walkInSales,
                'count' => $walkInCount,
                'percentage' => $totalSales > 0 ? ($walkInSales / $totalSales) * 100 : 0,
            ],
            'top_customers' => $topCustomers,
        ];
    }
}
