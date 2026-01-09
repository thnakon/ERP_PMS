<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->has('in_stock')) {
            $query->where('stock_qty', '>', 0);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category', 'lots'));
    }

    public function lots(Product $product)
    {
        return response()->json($product->lots()->orderBy('expiry_date')->get());
    }
}
