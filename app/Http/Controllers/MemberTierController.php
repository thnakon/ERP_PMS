<?php

namespace App\Http\Controllers;

use App\Models\MemberTier;
use App\Models\Customer;
use Illuminate\Http\Request;

class MemberTierController extends Controller
{
    /**
     * Display tier listing
     */
    public function index()
    {
        $tiers = MemberTier::withCount('customers')
            ->orderBy('sort_order')
            ->get();

        return view('member-tiers.index', compact('tiers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('member-tiers.create');
    }

    /**
     * Store new tier
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'min_spending' => 'required|numeric|min:0',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'points_multiplier' => 'integer|min:1|max:10',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'benefits' => 'nullable|array',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        MemberTier::create($validated);

        return redirect()->route('member-tiers.index')
            ->with('success', __('tiers.created'));
    }

    /**
     * Show edit form
     */
    public function edit(MemberTier $memberTier)
    {
        return view('member-tiers.edit', compact('memberTier'));
    }

    /**
     * Update tier
     */
    public function update(Request $request, MemberTier $memberTier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'min_spending' => 'required|numeric|min:0',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'points_multiplier' => 'integer|min:1|max:10',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'benefits' => 'nullable|array',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $memberTier->update($validated);

        return redirect()->route('member-tiers.index')
            ->with('success', __('tiers.updated'));
    }

    /**
     * Delete tier
     */
    public function destroy(MemberTier $memberTier)
    {
        // Move customers to default tier
        Customer::where('member_tier_id', $memberTier->id)
            ->update(['member_tier_id' => null]);

        $memberTier->delete();

        return redirect()->route('member-tiers.index')
            ->with('success', __('tiers.deleted'));
    }

    /**
     * Recalculate all customer tiers based on spending
     */
    public function recalculateAll()
    {
        $customers = Customer::all();
        $updated = 0;

        foreach ($customers as $customer) {
            $newTier = MemberTier::getTierForSpending($customer->total_spent);

            if ($customer->member_tier_id !== ($newTier?->id)) {
                $customer->member_tier_id = $newTier?->id;
                $customer->save();
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'message' => __('tiers.recalculated', ['count' => $updated]),
        ]);
    }

    /**
     * Get tier summary statistics
     */
    public function statistics()
    {
        $tiers = MemberTier::withCount('customers')
            ->with(['customers' => function ($q) {
                $q->select('id', 'member_tier_id', 'total_spent');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($tier) {
                return [
                    'id' => $tier->id,
                    'name' => $tier->display_name,
                    'color' => $tier->color,
                    'customers_count' => $tier->customers_count,
                    'total_spending' => $tier->customers->sum('total_spent'),
                    'avg_spending' => $tier->customers_count > 0
                        ? $tier->customers->sum('total_spent') / $tier->customers_count
                        : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'tiers' => $tiers,
        ]);
    }
}
