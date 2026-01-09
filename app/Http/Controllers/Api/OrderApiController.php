<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->limit(100)->get()
        );
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['customer', 'items.product', 'refunds']));
    }

    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        if (!$order->canBeRefunded()) {
            return response()->json(['error' => 'Cannot refund this order'], 422);
        }

        $refund = $order->processRefund($request->reason, \Illuminate\Support\Facades\Auth::id());

        return response()->json([
            'success' => true,
            'refund' => $refund,
        ]);
    }
}
