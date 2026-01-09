<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $orders = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'draft')->count(),
            'sent' => PurchaseOrder::where('status', 'sent')->count(),
            'partial' => PurchaseOrder::where('status', 'partial')->count(),
            'completed' => PurchaseOrder::where('status', 'completed')->count(),
        ];

        return view('purchase-orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $poNumber = PurchaseOrder::generatePoNumber();

        return view('purchase-orders.create', compact('suppliers', 'products', 'poNumber'));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.ordered_qty' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['ordered_qty'] * $item['unit_cost'];
                $discountPercent = $item['discount_percent'] ?? 0;
                $discountAmount = $lineSubtotal * ($discountPercent / 100);
                $lineTotal = $lineSubtotal - $discountAmount;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'ordered_qty' => $item['ordered_qty'],
                    'unit_cost' => $item['unit_cost'],
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'line_total' => $lineTotal,
                ]);
            }

            $po->calculateTotals();

            DB::commit();

            return redirect()->route('purchase-orders.show', $po)
                ->with('success', __('po.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchase_order)
    {
        $purchase_order->load(['supplier', 'user', 'items.product', 'goodsReceived']);

        return view('purchase-orders.show', [
            'order' => $purchase_order
        ]);
    }

    /**
     * Show the form for editing the purchase order.
     */
    public function edit(PurchaseOrder $purchase_order)
    {
        if ($purchase_order->status !== 'draft') {
            return redirect()->route('purchase-orders.show', $purchase_order)
                ->with('error', __('po.cannot_edit_non_draft'));
        }

        $purchase_order->load(['items.product']);
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchase-orders.edit', [
            'order' => $purchase_order,
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified purchase order.
     */
    public function update(Request $request, PurchaseOrder $purchase_order)
    {
        if ($purchase_order->status !== 'draft') {
            return redirect()->back()->with('error', __('po.cannot_edit_non_draft'));
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.ordered_qty' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $purchase_order->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete old items and re-create
            $purchase_order->items()->delete();

            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['ordered_qty'] * $item['unit_cost'];
                $discountPercent = $item['discount_percent'] ?? 0;
                $discountAmount = $lineSubtotal * ($discountPercent / 100);
                $lineTotal = $lineSubtotal - $discountAmount;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchase_order->id,
                    'product_id' => $item['product_id'],
                    'ordered_qty' => $item['ordered_qty'],
                    'unit_cost' => $item['unit_cost'],
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'line_total' => $lineTotal,
                ]);
            }

            $purchase_order->calculateTotals();

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchase_order)
                ->with('success', __('po.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Send the purchase order.
     */
    public function send(PurchaseOrder $purchase_order)
    {
        if ($purchase_order->status !== 'draft') {
            return redirect()->back()->with('error', __('po.already_sent'));
        }

        $purchase_order->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', __('po.sent'));
    }

    /**
     * Cancel the purchase order.
     */
    public function cancel(PurchaseOrder $purchase_order)
    {
        if (in_array($purchase_order->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', __('po.cannot_cancel'));
        }

        $purchase_order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', __('po.cancelled'));
    }

    /**
     * Remove the specified purchase order.
     */
    public function destroy(PurchaseOrder $purchase_order)
    {
        if ($purchase_order->status !== 'draft') {
            return redirect()->back()->with('error', __('po.cannot_delete_non_draft'));
        }

        $purchase_order->items()->delete();
        $purchase_order->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', __('po.deleted'));
    }
}
