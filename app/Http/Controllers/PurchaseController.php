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

        $suppliers = $query->withCount('purchases')->paginate(10); // Assuming 'purchases' relationship exists

        return view('purchasing.suppliers', compact('suppliers'));
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        \App\Models\Supplier::create($request->all());

        return redirect()->back()->with('success', 'Supplier created successfully!')->with('suppress_global_toast', true);
    }

    public function updateSupplier(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $supplier = \App\Models\Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->back()->with('success', 'Supplier updated successfully!')->with('suppress_global_toast', true);
    }

    public function destroySupplier($id)
    {
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            \App\Models\Supplier::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('success', 'Supplier deleted successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }

    public function bulkDestroySupplier(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            \App\Models\Supplier::whereIn('id', $request->ids)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => true, 'message' => 'Selected suppliers deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
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
                    // Sort by Supplier Name
                    $query->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                        ->orderBy('suppliers.name', 'asc')
                        ->select('purchases.*'); // Avoid column conflicts
                    break;
                case 'name_desc':
                    // Sort by Supplier Name
                    $query->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                        ->orderBy('suppliers.name', 'desc')
                        ->select('purchases.*');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $purchaseOrders = $query->paginate(10);
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
            'items' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        $totalAmount += ($item['quantity'] * $item['cost_price']);
                    }
                }

                $po = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'reference_number' => 'PO-' . date('Ymd') . '-' . rand(1000, 9999),
                    'purchase_date' => $request->purchase_date,
                    'status' => $request->status,
                    'total_amount' => $totalAmount
                ]);

                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        $po->items()->create([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'cost_price' => $item['cost_price'],
                            'expiry_date' => null
                        ]);
                    }
                }
            });
            return redirect()->back()->with('success', 'Purchase Order created successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating PO: ' . $e->getMessage());
        }
    }

    public function updatePurchaseOrder(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'status' => 'required|in:draft,ordered,completed,cancelled',
            'items' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $po = Purchase::findOrFail($id);

                $totalAmount = 0;
                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        $totalAmount += ($item['quantity'] * $item['cost_price']);
                    }
                }

                $po->update([
                    'supplier_id' => $request->supplier_id,
                    'purchase_date' => $request->purchase_date,
                    'status' => $request->status,
                    'total_amount' => $totalAmount
                ]);

                $po->items()->delete();

                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        $po->items()->create([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'cost_price' => $item['cost_price'],
                        ]);
                    }
                }
            });
            return redirect()->back()->with('success', 'Purchase Order updated successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating PO: ' . $e->getMessage());
        }
    }

    public function destroyPurchaseOrder($id)
    {
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Purchase::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('success', 'Purchase Order deleted successfully!')->with('suppress_global_toast', true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Error deleting PO: ' . $e->getMessage());
        }
    }

    public function bulkDestroyPurchaseOrder(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Purchase::whereIn('id', $request->ids)->delete();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => true, 'message' => 'Selected orders deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function goodsReceived(Request $request)
    {
        $query = Purchase::query()->with('supplier');

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

        // Awaiting: Status 'ordered'
        $awaitingPos = (clone $query)->where('status', 'ordered')->latest()->get();

        // Received: Status 'completed'
        $receivedQuery = (clone $query)->where('status', 'completed');

        // Sort Filter for Received History
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $receivedQuery->orderBy('updated_at', 'desc'); // Use updated_at for received date approx
                    break;
                case 'oldest':
                    $receivedQuery->orderBy('updated_at', 'asc');
                    break;
                case 'name_asc':
                    // Sort by Supplier Name
                    $receivedQuery->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                        ->orderBy('suppliers.name', 'asc')
                        ->select('purchases.*');
                    break;
                case 'name_desc':
                    // Sort by Supplier Name
                    $receivedQuery->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                        ->orderBy('suppliers.name', 'desc')
                        ->select('purchases.*');
                    break;
                default:
                    $receivedQuery->latest('updated_at');
                    break;
            }
        } else {
            $receivedQuery->latest('updated_at');
        }

        $receivedPos = $receivedQuery->paginate(10);

        return view('purchasing.goods-received', compact('awaitingPos', 'receivedPos'));
    }

    public function getPoDetails($id)
    {
        $po = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        return response()->json($po);
    }

    public function storeGoodsReceipt(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchases,id',
            'received_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.batch_number' => 'required|string',
            'items.*.expiry_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $po = Purchase::findOrFail($request->po_id);

                // Update PO
                $po->update([
                    'status' => 'completed',
                    // 'received_date' => $request->received_date // If we had this column
                ]);

                // Create Batches
                foreach ($request->items as $item) {
                    // Find original cost from PO Item or Product? 
                    // Better to take from PO Item to ensure accuracy, but frontend sends it back?
                    // Let's look up the PO Item to get the cost.
                    $poItem = $po->items()->where('product_id', $item['product_id'])->first();
                    $costPrice = $poItem ? $poItem->cost_price : 0;

                    // We also need selling price.
                    $product = \App\Models\Product::find($item['product_id']);
                    // Use selling_price from product, or default to 0 if null/missing
                    $sellingPrice = $product ? ($product->selling_price ?? 0) : 0;

                    Batch::create([
                        'product_id' => $item['product_id'],
                        'batch_number' => $item['batch_number'],
                        'expiry_date' => $item['expiry_date'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Goods received and stock updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
