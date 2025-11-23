<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit', 'supplier']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('generic_name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        }

        $products = $query->paginate(10);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'min_stock_level' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'unit', 'supplier', 'batches']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'category_id' => 'exists:categories,id',
            'unit_id' => 'exists:units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'min_stock_level' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
