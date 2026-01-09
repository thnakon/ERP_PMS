<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\OrderItem;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportController extends Controller
{
    /**
     * Display the inventory report page.
     */
    public function index(Request $request)
    {
        // Date range filter - default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Stock Valuation
        $valuation = $this->getStockValuation();

        // Risk Analysis
        $riskAnalysis = $this->getRiskAnalysis();

        // Efficiency Metrics
        $efficiency = $this->getEfficiencyMetrics($start, $end);

        // Category Breakdown
        $categoryBreakdown = $this->getCategoryBreakdown();

        // Low Stock Products
        $lowStockProducts = $this->getLowStockProducts();

        // Stock Movements
        $stockMovements = $this->getStockMovements($start, $end);

        // Top Moving Products
        $topMovingProducts = $this->getTopMovingProducts($start, $end);

        // Slow Moving Products
        $slowMovingProducts = $this->getSlowMovingProducts($start, $end);

        return view('reports.inventory', compact(
            'startDate',
            'endDate',
            'valuation',
            'riskAnalysis',
            'efficiency',
            'categoryBreakdown',
            'lowStockProducts',
            'stockMovements',
            'topMovingProducts',
            'slowMovingProducts'
        ));
    }

    /**
     * Export inventory report.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->input('format', 'excel');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $valuation = $this->getStockValuation();
        $riskAnalysis = $this->getRiskAnalysis();
        $efficiency = $this->getEfficiencyMetrics($start, $end);
        $categoryBreakdown = $this->getCategoryBreakdown();

        $filename = 'inventory_report_' . $startDate . '_to_' . $endDate;

        if ($format === 'pdf') {
            return response()->view('reports.inventory-pdf', compact(
                'startDate',
                'endDate',
                'valuation',
                'riskAnalysis',
                'efficiency',
                'categoryBreakdown'
            ))->header('Content-Type', 'text/html');
        }

        // Excel/CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($valuation, $riskAnalysis, $efficiency, $categoryBreakdown, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Inventory Report']);
            fputcsv($file, ['Period:', $startDate, 'to', $endDate]);
            fputcsv($file, []);

            fputcsv($file, ['STOCK VALUATION']);
            fputcsv($file, ['Total Cost Value', number_format($valuation['total_cost_value'], 2)]);
            fputcsv($file, ['Total Retail Value', number_format($valuation['total_retail_value'], 2)]);
            fputcsv($file, ['Total Products', $valuation['total_products']]);
            fputcsv($file, ['Total SKUs', $valuation['total_skus']]);
            fputcsv($file, []);

            fputcsv($file, ['RISK ANALYSIS']);
            fputcsv($file, ['Expired Stock Value', number_format($riskAnalysis['expired_value'], 2)]);
            fputcsv($file, ['Near Expiry (3 months)', number_format($riskAnalysis['near_expiry_3m_value'], 2)]);
            fputcsv($file, ['Near Expiry (6 months)', number_format($riskAnalysis['near_expiry_6m_value'], 2)]);
            fputcsv($file, ['Adjustment Value', number_format($riskAnalysis['adjustment_value'], 2)]);
            fputcsv($file, []);

            fputcsv($file, ['EFFICIENCY METRICS']);
            fputcsv($file, ['Inventory Turnover Rate', number_format($efficiency['turnover_rate'], 2)]);
            fputcsv($file, ['Days Sales of Inventory', number_format($efficiency['dsi'], 1) . ' days']);
            fputcsv($file, []);

            fputcsv($file, ['STOCK BY CATEGORY']);
            fputcsv($file, ['Category', 'Products', 'Stock Qty', 'Cost Value', 'Retail Value']);
            foreach ($categoryBreakdown as $cat) {
                fputcsv($file, [
                    $cat['name'],
                    $cat['product_count'],
                    $cat['total_stock'],
                    number_format($cat['cost_value'], 2),
                    number_format($cat['retail_value'], 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get stock valuation metrics.
     */
    private function getStockValuation(): array
    {
        $products = Product::where('is_active', true)->get();

        $totalCostValue = $products->sum(fn($p) => $p->stock_qty * $p->cost_price);
        $totalRetailValue = $products->sum(fn($p) => $p->stock_qty * $p->unit_price);
        $totalProducts = $products->sum('stock_qty');
        $totalSkus = $products->count();

        return [
            'total_cost_value' => $totalCostValue,
            'total_retail_value' => $totalRetailValue,
            'potential_profit' => $totalRetailValue - $totalCostValue,
            'profit_margin' => $totalRetailValue > 0 ? (($totalRetailValue - $totalCostValue) / $totalRetailValue) * 100 : 0,
            'total_products' => $totalProducts,
            'total_skus' => $totalSkus,
        ];
    }

    /**
     * Get risk analysis metrics.
     */
    private function getRiskAnalysis(): array
    {
        // Expired stock
        $expiredLots = ProductLot::with('product')
            ->where('expiry_date', '<', now())
            ->where('quantity', '>', 0)
            ->get();

        $expiredValue = $expiredLots->sum(fn($lot) => $lot->quantity * ($lot->cost_price ?? $lot->product->cost_price));
        $expiredCount = $expiredLots->count();

        // Near expiry (3 months)
        $nearExpiry3m = ProductLot::with('product')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addMonths(3))
            ->where('quantity', '>', 0)
            ->get();

        $nearExpiry3mValue = $nearExpiry3m->sum(fn($lot) => $lot->quantity * ($lot->cost_price ?? $lot->product->cost_price));
        $nearExpiry3mCount = $nearExpiry3m->count();

        // Near expiry (6 months)
        $nearExpiry6m = ProductLot::with('product')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addMonths(6))
            ->where('quantity', '>', 0)
            ->get();

        $nearExpiry6mValue = $nearExpiry6m->sum(fn($lot) => $lot->quantity * ($lot->cost_price ?? $lot->product->cost_price));
        $nearExpiry6mCount = $nearExpiry6m->count();

        // Adjustment value (losses)
        $adjustmentValue = StockAdjustment::where('type', 'loss')
            ->sum(DB::raw('ABS(quantity) * COALESCE((SELECT cost_price FROM products WHERE id = stock_adjustments.product_id), 0)'));

        return [
            'expired_value' => $expiredValue,
            'expired_count' => $expiredCount,
            'near_expiry_3m_value' => $nearExpiry3mValue,
            'near_expiry_3m_count' => $nearExpiry3mCount,
            'near_expiry_6m_value' => $nearExpiry6mValue,
            'near_expiry_6m_count' => $nearExpiry6mCount,
            'adjustment_value' => abs($adjustmentValue),
        ];
    }

    /**
     * Get efficiency metrics.
     */
    private function getEfficiencyMetrics(Carbon $start, Carbon $end): array
    {
        $periodDays = $start->diffInDays($end) + 1;

        // COGS for the period
        $cogs = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * products.cost_price'));

        // Average inventory value
        $avgInventory = Product::where('is_active', true)
            ->sum(DB::raw('stock_qty * cost_price'));

        // Inventory Turnover Rate = COGS / Average Inventory
        $turnoverRate = $avgInventory > 0 ? $cogs / $avgInventory : 0;

        // Days Sales of Inventory = Average Inventory / (COGS / Days)
        $dailyCogs = $periodDays > 0 ? $cogs / $periodDays : 0;
        $dsi = $dailyCogs > 0 ? $avgInventory / $dailyCogs : 0;

        return [
            'turnover_rate' => $turnoverRate,
            'dsi' => $dsi,
            'cogs' => $cogs,
            'avg_inventory' => $avgInventory,
        ];
    }

    /**
     * Get stock breakdown by category.
     */
    private function getCategoryBreakdown(): array
    {
        return Product::where('products.is_active', true)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(products.stock_qty) as total_stock'),
                DB::raw('SUM(products.stock_qty * products.cost_price) as cost_value'),
                DB::raw('SUM(products.stock_qty * products.unit_price) as retail_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('cost_value')
            ->get()
            ->toArray();
    }

    /**
     * Get low stock products.
     */
    private function getLowStockProducts(): array
    {
        return Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->select('id', 'name', 'sku', 'stock_qty', 'min_stock', 'reorder_point', 'cost_price', 'unit_price')
            ->orderBy('stock_qty')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get stock movements summary.
     */
    private function getStockMovements(Carbon $start, Carbon $end): array
    {
        // Stock received (from adjustments with type 'addition' or 'received')
        $stockIn = StockAdjustment::whereBetween('adjusted_at', [$start, $end])
            ->whereIn('type', ['addition', 'received', 'return'])
            ->sum('quantity');

        // Stock out (from adjustments with type 'deduction' or 'loss')
        $stockOut = StockAdjustment::whereBetween('adjusted_at', [$start, $end])
            ->whereIn('type', ['deduction', 'loss', 'sold', 'damaged', 'expired'])
            ->sum(DB::raw('ABS(quantity)'));

        // Sold through orders
        $soldQty = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed');
        })->sum('quantity');

        return [
            'stock_in' => $stockIn,
            'stock_out' => $stockOut,
            'sold' => $soldQty,
            'net_change' => $stockIn - $stockOut - $soldQty,
        ];
    }

    /**
     * Get top moving products.
     */
    private function getTopMovingProducts(Carbon $start, Carbon $end, int $limit = 10): array
    {
        return OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('orders.created_at', [$start, $end])
                ->where('orders.status', 'completed');
        })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.stock_qty',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock_qty')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get slow moving products.
     */
    private function getSlowMovingProducts(Carbon $start, Carbon $end, int $limit = 10): array
    {
        $soldProductIds = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('orders.created_at', [$start, $end])
                ->where('orders.status', 'completed');
        })->distinct()->pluck('product_id');

        return Product::where('is_active', true)
            ->where('stock_qty', '>', 0)
            ->whereNotIn('id', $soldProductIds)
            ->select('id', 'name', 'sku', 'stock_qty', 'cost_price')
            ->orderByDesc(DB::raw('stock_qty * cost_price'))
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->stock_value = $product->stock_qty * $product->cost_price;
                return $product;
            })
            ->toArray();
    }

    /**
     * Get stock card for a specific product.
     */
    public function stockCard(Request $request, $productId)
    {
        $product = Product::with('category')->findOrFail($productId);

        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get adjustments
        $adjustments = StockAdjustment::where('product_id', $productId)
            ->whereBetween('adjusted_at', [$start, $end])
            ->orderBy('adjusted_at', 'desc')
            ->get();

        // Get order items
        $orderItems = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->with('order')
            ->get();

        return view('reports.stock-card', compact('product', 'adjustments', 'orderItems', 'startDate', 'endDate'));
    }
}
