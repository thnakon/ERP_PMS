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

    public function index()
    {
        return response()->json(Purchase::with(['supplier', 'items.product'])->paginate(20));
    }
}
