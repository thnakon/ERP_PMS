<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        $hasFilters = false;

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%");
            });
            $hasFilters = true;
        }

        // Category filter
        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereIn('category_id', $categoryIds);
            $hasFilters = true;
        }

        // Drug class filter
        if ($request->filled('drug_classes')) {
            $drugClasses = explode(',', $request->drug_classes);
            $query->whereIn('drug_class', $drugClasses);
            $hasFilters = true;
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('unit_price', '>=', $request->price_min);
            $hasFilters = true;
        }
        if ($request->filled('price_max')) {
            $query->where('unit_price', '<=', $request->price_max);
            $hasFilters = true;
        }

        // Stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->whereColumn('stock_qty', '>', 'min_stock');
                    break;
                case 'low_stock':
                    $query->whereColumn('stock_qty', '<=', 'min_stock')
                        ->where('stock_qty', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock_qty', '<=', 0);
                    break;
            }
            $hasFilters = true;
        }

        // Prescription filter
        if ($request->filled('prescription')) {
            if ($request->prescription === 'required') {
                $query->where('requires_prescription', true);
            } elseif ($request->prescription === 'not_required') {
                $query->where('requires_prescription', false);
            }
            $hasFilters = true;
        }

        // Sort order
        $sortOrder = $request->get('sort', 'name');
        if ($sortOrder === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortOrder === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        // Flash message for filter results
        if ($hasFilters && !$request->ajax()) {
            $count = $products->total();
            if ($count > 0) {
                session()->flash('success', __('filter_success', ['count' => $count]));
            } else {
                session()->flash('warning', __('filter_no_results'));
            }
        }

        if ($request->ajax()) {
            return view('products.partials.list', compact('products'))->render();
        }

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku',
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'unit_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'base_unit' => 'nullable|string',
            'location' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'drug_class' => 'nullable|string',
            'fda_registration_no' => 'nullable|string',
            'description' => 'nullable|string',
            'precautions' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'default_instructions' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'requires_prescription' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle checkboxes
        $validated['vat_applicable'] = $request->has('vat_applicable');
        $validated['requires_prescription'] = $request->has('requires_prescription');
        $validated['is_active'] = $request->has('is_active', true); // Default true for new items if not provided

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        // Remove image from validated array (it's not a column)
        unset($validated['image']);

        $product = Product::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'product' => $product->load('category')]);
        }

        return redirect()->route('products.index')
            ->with('success', __('products.created'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'lots']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'drug_class' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'cost_price' => 'nullable|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'base_unit' => 'nullable|string',
            'sell_unit' => 'nullable|string',
            'conversion_factor' => 'nullable|numeric',
            'stock_qty' => 'nullable|integer',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer',
            'reorder_point' => 'nullable|integer',
            'location' => 'nullable|string',
            'precautions' => 'nullable|string',
            'precautions_th' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'side_effects_th' => 'nullable|string',
            'default_instructions' => 'nullable|string',
            'default_instructions_th' => 'nullable|string',
            'fda_registration_no' => 'nullable|string',
            'description' => 'nullable|string',
            'description_th' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $validated['vat_applicable'] = $request->has('vat_applicable');
        $validated['requires_prescription'] = $request->has('requires_prescription');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $product->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product->load('category')
            ]);
        }

        return redirect()->route('products.show', $product)
            ->with('success', __('products.updated'));
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('products.index')
            ->with('success', __('products.deleted'));
    }

    /**
     * Bulk update category for selected products.
     */
    public function bulkUpdateCategory(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::whereIn('id', $request->ids)->update([
            'category_id' => $request->category_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Products updated successfully'
        ]);
    }

    /**
     * Bulk delete products.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Products deleted successfully'
        ]);
    }
}
