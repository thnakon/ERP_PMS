<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product; // Added this line

class InventoryController extends Controller
{
    public function manageProducts(Request $request)
    {
        $query = Product::with('category');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(10);
        $categories = Category::all(); // For the filter dropdown and modal

        return view('inventorys.manage-products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->status === 'Active';

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image_path'] = 'images/products/' . $imageName;
        }

        // Assign default unit if not present (Fix for 1364 error)
        if (!isset($data['unit_id'])) {
            $defaultUnit = \App\Models\Unit::firstOrCreate(['name' => 'Unit'], ['abbreviation' => 'u']);
            $data['unit_id'] = $defaultUnit->id;
        }

        Product::create($data);

        return redirect()->back()->with('success', 'Product created successfully!')->with('suppress_global_toast', true);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->status === 'Active';

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                @unlink(public_path($product->image_path));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image_path'] = 'images/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Product updated successfully!')->with('suppress_global_toast', true);
    }

    public function destroyProduct($id)
    {
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Product::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('success', 'Product deleted successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function bulkDestroyProducts(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Product::whereIn('id', $request->ids)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => true, 'message' => 'Selected products deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function categories(Request $request)
    {
        $query = Category::withCount('products');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Group Filter
        if ($request->filled('group') && $request->group !== 'all') {
            $query->where('group', $request->group);
        }

        $categories = $query->paginate(10);

        return view('inventorys.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        Category::create($request->all());

        return redirect()->back()->with('success', 'Category created successfully!')->with('suppress_global_toast', true);
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->back()->with('success', 'Category updated successfully!')->with('suppress_global_toast', true);
    }

    public function destroyCategory($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Category deleted successfully!')->with('suppress_global_toast', true);
    }

    public function bulkDestroyCategories(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Category::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected categories deleted successfully!']);
    }

    public function expiryManagement()
    {
        return view('inventorys.expiry-management');
    }

    public function stockAdjustments()
    {
        return view('inventorys.stock-adjustments');
    }
}
