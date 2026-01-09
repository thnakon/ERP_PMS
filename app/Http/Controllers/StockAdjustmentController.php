<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\ProductLot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['product', 'lot', 'user']);

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['increase', 'decrease', 'set'])) {
            $query->where('type', $request->type);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest('adjusted_at');
        } else {
            $query->latest('adjusted_at');
        }

        $adjustments = $query->paginate(12)->withQueryString();

        return view('stock_adjustments.index', compact('adjustments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_lot_id' => 'nullable|exists:product_lots,id',
            'type' => 'required|in:increase,decrease,set',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $lot = $request->product_lot_id ? ProductLot::findOrFail($request->product_lot_id) : null;

            $beforeQty = $lot ? $lot->quantity : $product->stock_qty;
            $adjQty = $request->quantity;
            $afterQty = 0;

            if ($request->type === 'increase') {
                $afterQty = $beforeQty + $adjQty;
            } elseif ($request->type === 'decrease') {
                $afterQty = $beforeQty - $adjQty;
            } else { // set
                $afterQty = $adjQty;
                $adjQty = $afterQty - $beforeQty; // Calculate the effective adjustment
            }

            // Create Adjustment Record
            $adjustment = StockAdjustment::create([
                'adjustment_number' => $this->generateAdjNumber(),
                'product_id' => $product->id,
                'product_lot_id' => $lot?->id,
                'user_id' => Auth::id(),
                'type' => $request->type,
                'quantity' => $adjQty,
                'before_quantity' => $beforeQty,
                'after_quantity' => $afterQty,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'adjusted_at' => now(),
            ]);

            // Update Stock
            if ($lot) {
                // If specific lot is selected, update both lot and main product stock
                $diff = $afterQty - $beforeQty;
                $lot->quantity = $afterQty;
                $lot->save();

                $product->stock_qty += $diff;
                $product->save();
            } else {
                // No specific lot selected, update main product stock only
                $product->stock_qty = $afterQty;
                $product->save();
            }

            DB::commit();

            return redirect()->back()->with('success', __('stock_adjusted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function generateAdjNumber()
    {
        $prefix = 'ADJ-' . date('Ymd') . '-';
        $last = StockAdjustment::where('adjustment_number', 'like', $prefix . '%')
            ->orderBy('adjustment_number', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->adjustment_number, -4);
            $nextNum = str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }

    public function show(StockAdjustment $stock_adjustment)
    {
        $stock_adjustment->load(['product', 'lot', 'user']);

        return view('stock_adjustments.show', [
            'adjustment' => $stock_adjustment
        ]);
    }
}
