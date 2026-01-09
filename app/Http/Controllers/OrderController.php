<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductLot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method', 'all');

        // Stats
        $stats = [
            'total' => Order::whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()])->count(),
            'completed' => Order::where('status', 'completed')->whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()])->count(),
            'refunded' => Order::where('status', 'refunded')->whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()])->count(),
            'voided' => Order::where('status', 'void')->whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()])->count(),
            'total_sales' => Order::where('status', 'completed')->whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()])->sum('total_amount'),
            'today_sales' => Order::where('status', 'completed')->whereDate('created_at', today())->sum('total_amount'),
        ];

        // Query
        $query = Order::with(['customer', 'user', 'items'])
            ->whereBetween('created_at', [$dateFrom, Carbon::parse($dateTo)->endOfDay()]);

        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Payment method filter
        if ($paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('orders.index', compact('orders', 'stats', 'search', 'status', 'dateFrom', 'dateTo', 'paymentMethod'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'refunds']);

        return view('orders.show', compact('order'));
    }

    /**
     * Generate receipt for an order.
     */
    public function receipt(Order $order)
    {
        $order->load(['customer', 'items.product', 'user']);

        return view('orders.receipt', compact('order'));
    }

    /**
     * Process a refund for the order.
     */
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'amount' => 'nullable|numeric|min:0',
        ]);

        if (!$order->canBeRefunded()) {
            return response()->json([
                'success' => false,
                'message' => __('orders.cannot_refund'),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $refund = $order->processRefund(
                $request->reason,
                auth()->id()
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'refund' => $refund,
                'message' => __('orders.refund_processed'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
