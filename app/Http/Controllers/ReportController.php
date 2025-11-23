<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $sales = Sale::whereDate('created_at', $date)
            ->with('items.product')
            ->get();

        $total = $sales->sum('total_amount');

        return response()->json([
            'date' => $date,
            'total_sales' => $total,
            'transaction_count' => $sales->count(),
            'transactions' => $sales
        ]);
    }

    public function lowStock()
    {
        // Logic to find products where total batch quantity < min_stock_level
        // This is a bit complex with Eloquent, using a raw query for performance or a collection filter
        // Simplified approach:

        $products = \App\Models\Product::with('batches')->get()->filter(function ($product) {
            return $product->total_stock <= $product->min_stock_level;
        });

        return response()->json($products->values());
    }
}
