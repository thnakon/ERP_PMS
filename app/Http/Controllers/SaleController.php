<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $saleItemsData = [];

                // 1. Calculate total and prepare items
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Simple pricing for now (can be fetched from batch or product)
                    // Assuming price comes from the latest batch or a standard selling price on product (if added)
                    // For this logic, let's assume we take the price from the first available batch or request
                    // Ideally, price should be managed. Let's assume the frontend sends the price or we pick the highest batch price.
                    // Let's look for available batches to determine price and stock.

                    $quantityNeeded = $item['quantity'];

                    // FIFO: Get batches with stock, ordered by expiry
                    $batches = Batch::where('product_id', $product->id)
                        ->where('quantity', '>', 0)
                        ->orderBy('expiry_date', 'asc')
                        ->get();

                    if ($batches->sum('quantity') < $quantityNeeded) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    $quantityToFulfill = $quantityNeeded;

                    foreach ($batches as $batch) {
                        if ($quantityToFulfill <= 0) break;

                        $deduct = min($batch->quantity, $quantityToFulfill);

                        // Deduct stock
                        $batch->decrement('quantity', $deduct);

                        // Create Item Data (splitting by batch if necessary for precise tracking)
                        $saleItemsData[] = [
                            'product_id' => $product->id,
                            'batch_id' => $batch->id,
                            'quantity' => $deduct,
                            'price' => $batch->selling_price, // Use batch selling price
                            'total_price' => $batch->selling_price * $deduct
                        ];

                        $totalAmount += ($batch->selling_price * $deduct);
                        $quantityToFulfill -= $deduct;
                    }
                }

                // 2. Create Sale Header
                $sale = Sale::create([
                    'invoice_number' => 'INV-' . time(), // Simple generator
                    'user_id' => Auth::id() ?? 1, // Fallback to 1 for testing if not auth
                    'patient_id' => $request->patient_id,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $request->paid_amount,
                    'change_amount' => $request->paid_amount - $totalAmount,
                    'payment_method' => $request->payment_method
                ]);

                // 3. Create Sale Items
                foreach ($saleItemsData as $data) {
                    $sale->items()->create($data);
                }

                return response()->json($sale->load('items'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index()
    {
        $sales = Sale::with(['user', 'patient', 'items.product'])->latest()->paginate(20);
        return response()->json($sales);
    }

    public function show(Sale $sale)
    {
        return response()->json($sale->load(['user', 'patient', 'items.product', 'items.batch']));
    }
}
