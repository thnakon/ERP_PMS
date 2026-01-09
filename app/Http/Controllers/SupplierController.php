<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'name');
        if ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->orderBy('name');
        }

        $suppliers = $query->withCount('purchaseOrders')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Supplier::count(),
            'active' => Supplier::active()->count(),
        ];

        return view('suppliers.index', compact('suppliers', 'stats'));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'line_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'credit_term' => 'nullable|integer|min:0',
            'lead_time' => 'nullable|integer|min:0',
            'min_order_qty' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Supplier::create($validated);

        return redirect()->back()->with('success', __('suppliers.created'));
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => function ($q) {
            $q->latest()->take(10);
        }]);

        $recentPOs = $supplier->purchaseOrders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('suppliers.show', compact('supplier', 'recentPOs'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'line_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'credit_term' => 'nullable|integer|min:0',
            'lead_time' => 'nullable|integer|min:0',
            'min_order_qty' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $supplier->update($validated);

        return redirect()->back()->with('success', __('suppliers.updated'));
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        // Check if supplier has POs
        if ($supplier->purchaseOrders()->exists()) {
            return redirect()->back()->with('error', __('suppliers.cannot_delete_has_pos'));
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', __('suppliers.deleted'));
    }
}
