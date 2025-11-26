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

        // Sort Filter
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);
        $categories = Category::all(); // For the filter dropdown and modal

        return view('inventorys.manage-products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:Active,Inactive'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->status === 'Active';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image_path'] = '/images/products/' . $filename;
        }

        Product::create($data);

        return redirect()->back()->with('success', 'Product created successfully!')->with('suppress_global_toast', true);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:Active,Inactive'
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->status === 'Active';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image_path'] = '/images/products/' . $filename;
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

        // Sort Filter
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
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
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Category::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('success', 'Category deleted successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    public function bulkDestroyCategories(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Category::whereIn('id', $request->ids)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => true, 'message' => 'Selected categories deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function expiryManagement(Request $request)
    {
        $query = \App\Models\Batch::with('product');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $today = now()->format('Y-m-d');
            $nearExpiryDate = now()->addMonths(3)->format('Y-m-d');

            if ($request->status === 'expired') {
                $query->where('expiry_date', '<', $today);
            } elseif ($request->status === 'near_expiry') {
                $query->whereBetween('expiry_date', [$today, $nearExpiryDate]);
            } elseif ($request->status === 'good') {
                $query->where('expiry_date', '>', $nearExpiryDate);
            }
        }

        // Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'exp_asc':
                    $query->orderBy('expiry_date', 'asc');
                    break;
                case 'exp_desc':
                    $query->orderBy('expiry_date', 'desc');
                    break;
                default:
                    $query->orderBy('expiry_date', 'asc'); // FEFO default
                    break;
            }
        } else {
            $query->orderBy('expiry_date', 'asc'); // FEFO default
        }

        $batches = $query->paginate(10);

        return view('inventorys.expiry-management', compact('batches'));
    }

    public function updateBatch(Request $request, $id)
    {
        $request->validate([
            'expiry_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $batch = \App\Models\Batch::findOrFail($id);
        $batch->update($request->all());

        return redirect()->back()->with('success', 'Batch updated successfully!')->with('suppress_global_toast', true);
    }

    public function destroyBatch($id)
    {
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            \App\Models\Batch::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('success', 'Batch deleted successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Error deleting batch: ' . $e->getMessage());
        }
    }

    public function bulkDestroyBatches(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            \App\Models\Batch::whereIn('id', $request->ids)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => true, 'message' => 'Selected batches deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function stockAdjustments()
    {
        return view('inventorys.stock-adjustments');
    }
}
