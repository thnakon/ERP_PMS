<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    /**
     * Display bundle listing
     */
    public function index(Request $request)
    {
        $query = Bundle::with('products');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_th', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->available();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        $bundles = $query->orderByDesc('created_at')->paginate(15);

        return view('bundles.index', compact('bundles'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('stock_qty', '>', 0)
            ->orderBy('name')
            ->get();

        return view('bundles.create', compact('products'));
    }

    /**
     * Store new bundle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'stock_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:2',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle = Bundle::create($validated);

        // Attach products with quantities
        foreach ($request->products as $productData) {
            $bundle->products()->attach($productData['id'], [
                'quantity' => $productData['quantity'],
            ]);
        }

        // Calculate and save prices
        $bundle->original_price = $bundle->calculateOriginalPrice();
        $bundle->savings = $bundle->calculateSavings();
        $bundle->save();

        return redirect()->route('bundles.index')
            ->with('success', __('bundles.created'));
    }

    /**
     * Show bundle details
     */
    public function show(Bundle $bundle)
    {
        $bundle->load('products');

        return view('bundles.show', compact('bundle'));
    }

    /**
     * Show edit form
     */
    public function edit(Bundle $bundle)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $bundle->load('products');

        return view('bundles.edit', compact('bundle', 'products'));
    }

    /**
     * Update bundle
     */
    public function update(Request $request, Bundle $bundle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'stock_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:2',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($bundle->image_path) {
                Storage::disk('public')->delete($bundle->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle->update($validated);

        // Sync products with quantities
        $syncData = [];
        foreach ($request->products as $productData) {
            $syncData[$productData['id']] = ['quantity' => $productData['quantity']];
        }
        $bundle->products()->sync($syncData);

        // Recalculate prices
        $bundle->original_price = $bundle->calculateOriginalPrice();
        $bundle->savings = $bundle->calculateSavings();
        $bundle->save();

        return redirect()->route('bundles.index')
            ->with('success', __('bundles.updated'));
    }

    /**
     * Delete bundle
     */
    public function destroy(Bundle $bundle)
    {
        if ($bundle->image_path) {
            Storage::disk('public')->delete($bundle->image_path);
        }

        $bundle->delete();

        return redirect()->route('bundles.index')
            ->with('success', __('bundles.deleted'));
    }

    /**
     * Toggle bundle status
     */
    public function toggle(Bundle $bundle)
    {
        $bundle->update(['is_active' => !$bundle->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $bundle->is_active,
            'message' => $bundle->is_active
                ? __('bundles.activated')
                : __('bundles.deactivated'),
        ]);
    }

    /**
     * Get available bundles for POS
     */
    public function getAvailableBundles()
    {
        $bundles = Bundle::available()
            ->with('products')
            ->get()
            ->filter(fn($b) => $b->isAvailable())
            ->values()
            ->map(function ($bundle) {
                return [
                    'id' => $bundle->id,
                    'name' => $bundle->display_name,
                    'bundle_price' => $bundle->bundle_price,
                    'original_price' => $bundle->original_price,
                    'savings' => $bundle->savings,
                    'savings_percent' => $bundle->savings_percent,
                    'remaining_stock' => $bundle->remaining_stock,
                    'image_url' => $bundle->image_path ? asset('storage/' . $bundle->image_path) : null,
                    'products' => $bundle->products->map(fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'quantity' => $p->pivot->quantity,
                    ]),
                ];
            });

        return response()->json([
            'success' => true,
            'bundles' => $bundles,
        ]);
    }

    /**
     * Add bundle to cart (returns products to add)
     */
    public function addToCart(Bundle $bundle)
    {
        if (!$bundle->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => __('bundles.not_available'),
            ]);
        }

        $items = $bundle->products->map(function ($product) use ($bundle) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'price' => $bundle->bundle_price / $bundle->products->sum('pivot.quantity'), // Distribute price
                'original_price' => $product->unit_price,
                'bundle_id' => $bundle->id,
            ];
        });

        return response()->json([
            'success' => true,
            'bundle' => [
                'id' => $bundle->id,
                'name' => $bundle->display_name,
                'bundle_price' => $bundle->bundle_price,
                'savings' => $bundle->savings,
            ],
            'items' => $items,
        ]);
    }
}
