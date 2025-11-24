<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0', // Needed for batch
            'items.*.expiry_date' => 'required|date',
            'purchase_date' => 'required|date',
            'status' => 'required|in:pending,received'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;

                // Calculate total
                foreach ($request->items as $item) {
                    $totalAmount += ($item['quantity'] * $item['cost_price']);
                }

                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'reference_number' => $request->reference_number,
                    'total_amount' => $totalAmount,
                    'status' => $request->status,
                    'purchase_date' => $request->purchase_date
                ]);

                foreach ($request->items as $item) {
                    // Create Purchase Item
                    $purchase->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                        'expiry_date' => $item['expiry_date']
                    ]);

                    // If status is received, add to stock (Create Batch)
                    if ($request->status === 'received') {
                        Batch::create([
                            'product_id' => $item['product_id'],
                            'batch_number' => 'BATCH-' . time() . '-' . $item['product_id'], // Auto-generate or take from input
                            'expiry_date' => $item['expiry_date'],
                            'quantity' => $item['quantity'],
                            'cost_price' => $item['cost_price'],
                            'selling_price' => $item['selling_price']
                        ]);
                    }
                }

                return response()->json($purchase->load('items'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // --- Supplier Management ---

    public function suppliers(Request $request)
    {
        $query = \App\Models\Supplier::query();

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('contact_person', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $suppliers = $query->withCount('purchases')->paginate(10); // Assuming 'purchases' relationship exists

        return view('purchasing.suppliers', compact('suppliers'));
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        \App\Models\Supplier::create($request->all());

        return redirect()->route('purchasing.suppliers')
            ->with('success', 'Supplier added successfully!')
            ->with('suppress_global_toast', true);
    }

    public function updateSupplier(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $supplier = \App\Models\Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('purchasing.suppliers')
            ->with('success', 'Supplier updated successfully!')
            ->with('suppress_global_toast', true);
    }

    public function destroySupplier($id)
    {
        $supplier = \App\Models\Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('purchasing.suppliers')
            ->with('success', 'Supplier deleted successfully!')
            ->with('suppress_global_toast', true);
    }

    public function bulkDestroySupplier(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:suppliers,id',
        ]);

        \App\Models\Supplier::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected suppliers deleted successfully!']);
    }

    // --- Page Views ---
    // --- Purchase Order Management ---

    public function purchaseOrders(Request $request)
    {
        $query = Purchase::query()->with('supplier', 'items.product');

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('supplier', function ($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter by Status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest()->paginate(10);
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all();

        return view('purchasing.purchase-orders', compact('purchaseOrders', 'suppliers', 'products'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'status' => 'required|in:draft,ordered,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += ($item['quantity'] * $item['cost_price']);
                }

                $po = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'reference_number' => 'PO-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT), // Simple auto-gen
                    'purchase_date' => $request->purchase_date,
                    'status' => $request->status,
                    'total_amount' => $totalAmount,
                ]);

                foreach ($request->items as $item) {
                    $po->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                        // expiry_date is optional for PO
                    ]);
                }
            });

            return redirect()->route('purchasing.purchaseOrders')
                ->with('success', 'Purchase Order created successfully!')
                ->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating PO: ' . $e->getMessage());
        }
    }

    public function updatePurchaseOrder(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'status' => 'required|in:draft,ordered,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $po = Purchase::findOrFail($id);

                // Recalculate total
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += ($item['quantity'] * $item['cost_price']);
                }

                $po->update([
                    'supplier_id' => $request->supplier_id,
                    'purchase_date' => $request->purchase_date,
                    'status' => $request->status,
                    'total_amount' => $totalAmount,
                ]);

                // Sync items (delete all and recreate for simplicity, or smart sync)
                $po->items()->delete();
                foreach ($request->items as $item) {
                    $po->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                    ]);
                }
            });

            return redirect()->route('purchasing.purchaseOrders')
                ->with('success', 'Purchase Order updated successfully!')
                ->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating PO: ' . $e->getMessage());
        }
    }

    public function destroyPurchaseOrder($id)
    {
        $po = Purchase::findOrFail($id);
        $po->items()->delete();
        $po->delete();

        return redirect()->route('purchasing.purchaseOrders')
            ->with('success', 'Purchase Order deleted successfully!')
            ->with('suppress_global_toast', true);
    }

    public function bulkDestroyPurchaseOrder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:purchases,id',
        ]);

        Purchase::whereIn('id', $request->ids)->delete();
        // Also delete items if cascading isn't set up in DB, but usually handled by DB or model events.
        // For safety:
        PurchaseItem::whereIn('purchase_id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected POs deleted successfully!']);
    }

    public function goodsReceived()
    {
        return view('purchasing.goods-received');
    }
}
