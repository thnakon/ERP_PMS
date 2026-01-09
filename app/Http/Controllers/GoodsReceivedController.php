<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceived;
use App\Models\GoodsReceivedItem;
use App\Models\PurchaseOrder;
use App\Models\ProductLot;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsReceivedController extends Controller
{
    /**
     * Display a listing of goods received.
     */
    public function index(Request $request)
    {
        $query = GoodsReceived::with(['supplier', 'purchaseOrder', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('gr_number', 'like', "%{$search}%")
                    ->orWhere('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('purchaseOrder', function ($sq) use ($search) {
                        $sq->where('po_number', 'like', "%{$search}%");
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

        $receipts = $query->paginate(12)->withQueryString();

        // Fetch pending POs that need receiving
        $pendingPOs = PurchaseOrder::with('supplier', 'user')
            ->whereIn('status', ['sent', 'partial'])
            ->latest()
            ->get();

        $stats = [
            'total' => GoodsReceived::count(),
            'pending' => PurchaseOrder::whereIn('status', ['sent', 'partial'])->count(),
            'completed' => GoodsReceived::where('status', 'completed')->count(),
        ];

        return view('goods-received.index', compact('receipts', 'stats', 'pendingPOs'));
    }

    /**
     * Show form to create goods received from a PO.
     */
    public function createFromPo(PurchaseOrder $purchase_order)
    {
        if (!in_array($purchase_order->status, ['sent', 'partial'])) {
            return redirect()->back()->with('error', __('gr.po_not_ready'));
        }

        $purchase_order->load(['supplier', 'items.product']);
        $grNumber = GoodsReceived::generateGrNumber();

        return view('goods-received.create', [
            'order' => $purchase_order,
            'grNumber' => $grNumber,
        ]);
    }

    /**
     * Show form to create goods received without PO.
     */
    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $grNumber = GoodsReceived::generateGrNumber();

        return view('goods-received.create-direct', compact('suppliers', 'products', 'grNumber'));
    }

    /**
     * Store a newly created goods received.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'nullable|string|max:100',
            'received_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.purchase_order_item_id' => 'nullable|exists:purchase_order_items,id',
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.rejected_qty' => 'nullable|numeric|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.lot_number' => 'nullable|string|max:100',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.manufactured_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $gr = GoodsReceived::create([
                'gr_number' => GoodsReceived::generateGrNumber(),
                'purchase_order_id' => $validated['purchase_order_id'] ?? null,
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'invoice_no' => $validated['invoice_no'] ?? null,
                'received_date' => $validated['received_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            foreach ($validated['items'] as $item) {
                if ($item['received_qty'] <= 0) continue;

                $lineTotal = $item['received_qty'] * $item['unit_cost'];

                $grItem = GoodsReceivedItem::create([
                    'goods_received_id' => $gr->id,
                    'product_id' => $item['product_id'],
                    'purchase_order_item_id' => $item['purchase_order_item_id'] ?? null,
                    'ordered_qty' => $item['ordered_qty'] ?? 0,
                    'received_qty' => $item['received_qty'],
                    'rejected_qty' => $item['rejected_qty'] ?? 0,
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $lineTotal,
                    'lot_number' => $item['lot_number'] ?? null,
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'manufactured_date' => $item['manufactured_date'] ?? null,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $actualReceived = $item['received_qty'] - ($item['rejected_qty'] ?? 0);
                $product->increment('stock_qty', $actualReceived);

                // Create product lot if lot_number is provided
                if (!empty($item['lot_number'])) {
                    ProductLot::create([
                        'product_id' => $item['product_id'],
                        'supplier_id' => $validated['supplier_id'],
                        'lot_number' => $item['lot_number'],
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'manufactured_date' => $item['manufactured_date'] ?? null,
                        'quantity' => $actualReceived,
                        'initial_quantity' => $actualReceived,
                        'cost_price' => $item['unit_cost'],
                        'gr_reference' => $gr->gr_number,
                        'received_at' => $validated['received_date'],
                    ]);
                }

                // Update PO item received qty if from PO
                if (!empty($item['purchase_order_item_id'])) {
                    $poItem = \App\Models\PurchaseOrderItem::find($item['purchase_order_item_id']);
                    if ($poItem) {
                        $poItem->increment('received_qty', $item['received_qty']);
                    }
                }
            }

            $gr->calculateTotal();

            // Update PO status if from PO
            if (!empty($validated['purchase_order_id'])) {
                $po = PurchaseOrder::find($validated['purchase_order_id']);
                $po->load('items');

                $allReceived = $po->items->every(fn($item) => $item->received_qty >= $item->ordered_qty);
                $anyReceived = $po->items->some(fn($item) => $item->received_qty > 0);

                if ($allReceived) {
                    $po->update(['status' => 'completed', 'completed_at' => now()]);
                } elseif ($anyReceived) {
                    $po->update(['status' => 'partial']);
                }
            }

            DB::commit();

            return redirect()->route('goods-received.show', $gr)
                ->with('success', __('gr.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified goods received.
     */
    public function show(GoodsReceived $goods_received)
    {
        $goods_received->load(['supplier', 'purchaseOrder', 'user', 'items.product']);

        return view('goods-received.show', [
            'receipt' => $goods_received
        ]);
    }

    /**
     * Remove the specified goods received (only if no stock impact or admin).
     */
    public function destroy(GoodsReceived $goods_received)
    {
        // In production, you might want to reverse stock changes
        // For now, we just soft delete
        $goods_received->delete();

        return redirect()->route('goods-received.index')
            ->with('success', __('gr.deleted'));
    }
}
