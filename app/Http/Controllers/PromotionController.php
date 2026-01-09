<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Models\MemberTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Display promotion listing
     */
    public function index(Request $request)
    {
        $query = Promotion::with(['memberTier', 'products', 'categories']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_th', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            } elseif ($request->status === 'scheduled') {
                $query->where('start_date', '>', now());
            }
        }

        $promotions = $query->orderByDesc('created_at')->paginate(15);
        $types = $this->getPromotionTypes();
        $tiers = MemberTier::active()->orderBy('sort_order')->get();

        return view('promotions.index', compact('promotions', 'types', 'tiers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $types = $this->getPromotionTypes();
        $tiers = MemberTier::active()->orderBy('sort_order')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('promotions.create', compact('types', 'tiers', 'products', 'categories'));
    }

    /**
     * Store new promotion
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50|unique:promotions,code',
            'description' => 'nullable|string',
            'description_th' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y,bundle,free_item,tier_discount',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'active_days' => 'nullable|array',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'nullable|integer|min:1',
            'member_tier_id' => 'nullable|exists:member_tiers,id',
            'new_customers_only' => 'boolean',
            'stackable' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion = Promotion::create($validated);

        // Attach products
        if ($request->filled('products')) {
            $promotion->products()->attach($request->products, ['type' => 'included']);
        }

        // Attach categories
        if ($request->filled('categories')) {
            $promotion->categories()->attach($request->categories, ['type' => 'included']);
        }

        return redirect()->route('promotions.index')
            ->with('success', __('promotions.created'));
    }

    /**
     * Show promotion details
     */
    public function show(Promotion $promotion)
    {
        $promotion->load(['memberTier', 'products', 'categories', 'usages']);

        return view('promotions.show', compact('promotion'));
    }

    /**
     * Show edit form
     */
    public function edit(Promotion $promotion)
    {
        $types = $this->getPromotionTypes();
        $tiers = MemberTier::active()->orderBy('sort_order')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $promotion->load(['products', 'categories']);

        return view('promotions.edit', compact('promotion', 'types', 'tiers', 'products', 'categories'));
    }

    /**
     * Update promotion
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'description_th' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y,bundle,free_item,tier_discount',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'active_days' => 'nullable|array',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'nullable|integer|min:1',
            'member_tier_id' => 'nullable|exists:member_tiers,id',
            'new_customers_only' => 'boolean',
            'stackable' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($promotion->image_path) {
                Storage::disk('public')->delete($promotion->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($validated);

        // Sync products
        $productIds = $request->filled('products') ? $request->products : [];
        $promotion->products()->sync(
            collect($productIds)->mapWithKeys(fn($id) => [$id => ['type' => 'included']])->all()
        );

        // Sync categories
        $categoryIds = $request->filled('categories') ? $request->categories : [];
        $promotion->categories()->sync(
            collect($categoryIds)->mapWithKeys(fn($id) => [$id => ['type' => 'included']])->all()
        );

        return redirect()->route('promotions.index')
            ->with('success', __('promotions.updated'));
    }

    /**
     * Delete promotion
     */
    public function destroy(Promotion $promotion)
    {
        if ($promotion->image_path) {
            Storage::disk('public')->delete($promotion->image_path);
        }

        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', __('promotions.deleted'));
    }

    /**
     * Toggle promotion status
     */
    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $promotion->is_active,
            'message' => $promotion->is_active
                ? __('promotions.activated')
                : __('promotions.deactivated'),
        ]);
    }

    /**
     * Get active promotions for POS
     */
    public function getActivePromotions(Request $request)
    {
        $customerId = $request->get('customer_id');
        $customer = $customerId ? \App\Models\Customer::find($customerId) : null;

        $promotions = Promotion::active()
            ->with(['products', 'categories', 'memberTier'])
            ->get()
            ->filter(fn($p) => $p->canCustomerUse($customer))
            ->values();

        return response()->json([
            'success' => true,
            'promotions' => $promotions,
        ]);
    }

    /**
     * Apply promotion code
     */
    public function applyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart' => 'required|array',
            'subtotal' => 'required|numeric',
            'customer_id' => 'nullable|integer',
        ]);

        $promotion = Promotion::where('code', $request->code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => __('promotions.code_not_found'),
            ]);
        }

        $customer = $request->customer_id
            ? \App\Models\Customer::find($request->customer_id)
            : null;

        if (!$promotion->canCustomerUse($customer)) {
            return response()->json([
                'success' => false,
                'message' => __('promotions.code_not_valid'),
            ]);
        }

        $result = $promotion->calculateDiscount($request->cart, $request->subtotal, $customer);

        if ($result['amount'] <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('promotions.min_purchase_not_met', ['amount' => $promotion->min_purchase]),
            ]);
        }

        return response()->json([
            'success' => true,
            'promotion' => [
                'id' => $promotion->id,
                'name' => $promotion->display_name,
                'type' => $promotion->type,
                'discount_amount' => $result['amount'],
            ],
            'message' => __('promotions.code_applied'),
        ]);
    }

    /**
     * Calculate applicable discounts for cart
     */
    public function calculateDiscounts(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'subtotal' => 'required|numeric',
            'customer_id' => 'nullable|integer',
        ]);

        $customer = $request->customer_id
            ? \App\Models\Customer::with('memberTier')->find($request->customer_id)
            : null;

        $cart = $request->cart;
        $subtotal = $request->subtotal;

        // Get all applicable promotions
        $promotions = Promotion::active()
            ->get()
            ->filter(fn($p) => $p->canCustomerUse($customer));

        $discounts = [];
        $totalDiscount = 0;

        // Apply member tier discount first
        if ($customer && $customer->memberTier && $customer->memberTier->discount_percent > 0) {
            $tierDiscount = $subtotal * ($customer->memberTier->discount_percent / 100);
            $discounts[] = [
                'type' => 'tier',
                'name' => __('promotions.member_discount', ['tier' => $customer->memberTier->display_name]),
                'amount' => round($tierDiscount, 2),
            ];
            $totalDiscount += $tierDiscount;
        }

        // Apply other promotions
        foreach ($promotions as $promotion) {
            if ($promotion->type === 'tier_discount') {
                continue; // Already handled above
            }

            $result = $promotion->calculateDiscount($cart, $subtotal, $customer);

            if ($result['amount'] > 0) {
                $discounts[] = [
                    'type' => $promotion->type,
                    'promotion_id' => $promotion->id,
                    'name' => $promotion->display_name,
                    'amount' => $result['amount'],
                ];

                if (!$promotion->stackable) {
                    // Only apply the best non-stackable promotion
                    if ($result['amount'] > $totalDiscount) {
                        $totalDiscount = $result['amount'];
                    }
                } else {
                    $totalDiscount += $result['amount'];
                }
            }
        }

        return response()->json([
            'success' => true,
            'discounts' => $discounts,
            'total_discount' => round($totalDiscount, 2),
        ]);
    }

    /**
     * Get promotion types
     */
    protected function getPromotionTypes(): array
    {
        return [
            'percentage' => __('promotions.type_percentage'),
            'fixed_amount' => __('promotions.type_fixed'),
            'buy_x_get_y' => __('promotions.type_buy_x_get_y'),
            'bundle' => __('promotions.type_bundle'),
            'free_item' => __('promotions.type_free_item'),
            'tier_discount' => __('promotions.type_tier'),
        ];
    }
}
