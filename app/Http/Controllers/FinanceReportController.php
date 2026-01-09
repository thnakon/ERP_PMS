<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceReportController extends Controller
{
    /**
     * Display the finance report page.
     */
    public function index(Request $request)
    {
        // Date range filter - default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // P&L Summary
        $pnl = $this->getProfitAndLoss($start, $end);

        // Tax Report
        $taxReport = $this->getTaxReport($start, $end);

        // Payment Methods Breakdown
        $paymentMethods = $this->getPaymentMethodsBreakdown($start, $end);

        // Daily Revenue Trend
        $dailyRevenue = $this->getDailyRevenue($start, $end);

        // Monthly Comparison
        $monthlyComparison = $this->getMonthlyComparison();

        return view('reports.finance', compact(
            'startDate',
            'endDate',
            'pnl',
            'taxReport',
            'paymentMethods',
            'dailyRevenue',
            'monthlyComparison'
        ));
    }

    /**
     * Export finance report.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->input('format', 'excel');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $pnl = $this->getProfitAndLoss($start, $end);
        $taxReport = $this->getTaxReport($start, $end);
        $paymentMethods = $this->getPaymentMethodsBreakdown($start, $end);

        $filename = 'finance_report_' . $startDate . '_to_' . $endDate;

        if ($format === 'pdf') {
            return response()->view('reports.finance-pdf', compact(
                'startDate',
                'endDate',
                'pnl',
                'taxReport',
                'paymentMethods'
            ))->header('Content-Type', 'text/html');
        }

        // Excel/CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($pnl, $taxReport, $paymentMethods, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Finance Report']);
            fputcsv($file, ['Period:', $startDate, 'to', $endDate]);
            fputcsv($file, []);

            // P&L
            fputcsv($file, ['PROFIT & LOSS STATEMENT']);
            fputcsv($file, ['Gross Revenue', number_format($pnl['gross_revenue'], 2)]);
            fputcsv($file, ['Discounts', number_format($pnl['total_discount'], 2)]);
            fputcsv($file, ['Net Revenue', number_format($pnl['net_revenue'], 2)]);
            fputcsv($file, ['Cost of Goods Sold', number_format($pnl['cogs'], 2)]);
            fputcsv($file, ['Gross Profit', number_format($pnl['gross_profit'], 2)]);
            fputcsv($file, ['Gross Margin %', number_format($pnl['gross_margin'], 1) . '%']);
            fputcsv($file, []);

            // Tax Report
            fputcsv($file, ['TAX REPORT']);
            fputcsv($file, ['Taxable Sales (7%)', number_format($taxReport['taxable_sales'], 2)]);
            fputcsv($file, ['VAT Amount (7%)', number_format($taxReport['vat_amount'], 2)]);
            fputcsv($file, ['Zero-rated Sales (0%)', number_format($taxReport['zero_rated_sales'], 2)]);
            fputcsv($file, ['Exempt Sales', number_format($taxReport['exempt_sales'], 2)]);
            fputcsv($file, ['Total Output VAT', number_format($taxReport['total_output_vat'], 2)]);
            fputcsv($file, []);

            // Payment Methods
            fputcsv($file, ['PAYMENT METHODS BREAKDOWN']);
            fputcsv($file, ['Method', 'Transactions', 'Amount', 'Percentage']);
            foreach ($paymentMethods as $method) {
                fputcsv($file, [
                    $method['method'],
                    $method['count'],
                    number_format($method['amount'], 2),
                    number_format($method['percentage'], 1) . '%'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get Profit & Loss summary.
     */
    private function getProfitAndLoss(Carbon $start, Carbon $end): array
    {
        $orders = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed');

        // Gross Revenue (before discounts)
        $grossRevenue = (clone $orders)->sum('subtotal');

        // Total Discounts
        $totalDiscount = (clone $orders)->sum('discount');

        // Net Revenue
        $netRevenue = (clone $orders)->sum('total_amount');

        // Transaction Count
        $transactionCount = (clone $orders)->count();

        // COGS (Cost of Goods Sold)
        $cogs = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->whereBetween('orders.created_at', [$start, $end])
                ->where('orders.status', 'completed');
        })->join('products', 'order_items.product_id', '=', 'products.id')
            ->sum(DB::raw('order_items.quantity * products.cost_price'));

        // Gross Profit
        $grossProfit = $netRevenue - $cogs;
        $grossMargin = $netRevenue > 0 ? ($grossProfit / $netRevenue) * 100 : 0;

        // Average Transaction Value
        $avgTransaction = $transactionCount > 0 ? $netRevenue / $transactionCount : 0;

        return [
            'gross_revenue' => $grossRevenue,
            'total_discount' => $totalDiscount,
            'net_revenue' => $netRevenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'gross_margin' => $grossMargin,
            'transaction_count' => $transactionCount,
            'avg_transaction' => $avgTransaction,
        ];
    }

    /**
     * Get Tax Report.
     */
    private function getTaxReport(Carbon $start, Carbon $end): array
    {
        // Get all completed orders in period
        $orderIds = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->pluck('id');

        // Taxable sales (products with vat_applicable = true, 7% VAT)
        $taxableSales = OrderItem::whereIn('order_id', $orderIds)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vat_applicable', true)
            ->sum('order_items.subtotal');

        // VAT Amount (7% of taxable sales / 1.07 * 0.07)
        $vatAmount = $taxableSales / 1.07 * 0.07;

        // Zero-rated sales (for exports, typically 0 for retail pharmacy)
        $zeroRatedSales = 0;

        // Exempt sales (products with vat_applicable = false)
        $exemptSales = OrderItem::whereIn('order_id', $orderIds)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vat_applicable', false)
            ->sum('order_items.subtotal');

        // Total sales
        $totalSales = $taxableSales + $zeroRatedSales + $exemptSales;

        // Taxable percentage
        $taxablePercentage = $totalSales > 0 ? ($taxableSales / $totalSales) * 100 : 0;
        $exemptPercentage = $totalSales > 0 ? ($exemptSales / $totalSales) * 100 : 0;

        return [
            'taxable_sales' => $taxableSales,
            'vat_amount' => $vatAmount,
            'zero_rated_sales' => $zeroRatedSales,
            'exempt_sales' => $exemptSales,
            'total_output_vat' => $vatAmount,
            'total_sales' => $totalSales,
            'taxable_percentage' => $taxablePercentage,
            'exempt_percentage' => $exemptPercentage,
        ];
    }

    /**
     * Get Payment Methods Breakdown.
     */
    private function getPaymentMethodsBreakdown(Carbon $start, Carbon $end): array
    {
        $paymentData = Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as amount')
            )
            ->groupBy('payment_method')
            ->get();

        $totalAmount = $paymentData->sum('amount');

        $methodLabels = [
            'cash' => 'เงินสด',
            'card' => 'บัตรเครดิต/เดบิต',
            'transfer' => 'โอนเงิน/QR',
            'credit' => 'เครดิต (ลูกหนี้)',
        ];

        $methodIcons = [
            'cash' => 'ph-money',
            'card' => 'ph-credit-card',
            'transfer' => 'ph-qr-code',
            'credit' => 'ph-wallet',
        ];

        $methodColors = [
            'cash' => '#22C55E',
            'card' => '#3B82F6',
            'transfer' => '#8B5CF6',
            'credit' => '#F59E0B',
        ];

        return $paymentData->map(function ($item) use ($totalAmount, $methodLabels, $methodIcons, $methodColors) {
            return [
                'method' => $item->payment_method,
                'label' => $methodLabels[$item->payment_method] ?? ucfirst($item->payment_method),
                'icon' => $methodIcons[$item->payment_method] ?? 'ph-coins',
                'color' => $methodColors[$item->payment_method] ?? '#6B7280',
                'count' => $item->count,
                'amount' => $item->amount,
                'percentage' => $totalAmount > 0 ? ($item->amount / $totalAmount) * 100 : 0,
            ];
        })->sortByDesc('amount')->values()->toArray();
    }

    /**
     * Get Daily Revenue Trend.
     */
    private function getDailyRevenue(Carbon $start, Carbon $end): array
    {
        return Order::whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => $item->revenue,
                    'transactions' => $item->transactions,
                ];
            })
            ->toArray();
    }

    /**
     * Get Monthly Comparison (current vs previous month).
     */
    private function getMonthlyComparison(): array
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $prevMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $prevMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Current month
        $currentRevenue = Order::whereBetween('orders.created_at', [$currentMonthStart, $currentMonthEnd])
            ->where('orders.status', 'completed')
            ->sum('total_amount');

        $currentTransactions = Order::whereBetween('orders.created_at', [$currentMonthStart, $currentMonthEnd])
            ->where('orders.status', 'completed')
            ->count();

        // Previous month
        $prevRevenue = Order::whereBetween('orders.created_at', [$prevMonthStart, $prevMonthEnd])
            ->where('orders.status', 'completed')
            ->sum('total_amount');

        $prevTransactions = Order::whereBetween('orders.created_at', [$prevMonthStart, $prevMonthEnd])
            ->where('orders.status', 'completed')
            ->count();

        // Growth percentages
        $revenueGrowth = $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $transactionGrowth = $prevTransactions > 0 ? (($currentTransactions - $prevTransactions) / $prevTransactions) * 100 : 0;

        return [
            'current_month' => [
                'name' => Carbon::now()->format('F Y'),
                'revenue' => $currentRevenue,
                'transactions' => $currentTransactions,
            ],
            'previous_month' => [
                'name' => Carbon::now()->subMonth()->format('F Y'),
                'revenue' => $prevRevenue,
                'transactions' => $prevTransactions,
            ],
            'revenue_growth' => $revenueGrowth,
            'transaction_growth' => $transactionGrowth,
        ];
    }
}
