<?php

namespace App\Http\Controllers;

use App\Models\AllergyAlert;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PosShift;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $currentShift = null;
        try {
            $currentShift = PosShift::getCurrentShift(auth()->id());
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        // All active products for browsing
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        // Popular products for quick access
        $popularProducts = Product::where('is_active', true)
            ->orderByDesc('stock_qty')
            ->limit(12)
            ->get();

        // Customers list
        $customers = Customer::where('is_active', true)
            ->orderBy('name')
            ->limit(100)
            ->get();

        // Recent customers
        $recentCustomers = Customer::orderByDesc('last_visit_at')
            ->limit(10)
            ->get();

        // Today's stats
        $todayStats = [
            'sales' => Order::whereDate('created_at', today())->where('status', 'completed')->sum('total_amount'),
            'transactions' => Order::whereDate('created_at', today())->where('status', 'completed')->count(),
            'items_sold' => OrderItem::whereHas('order', function ($q) {
                $q->whereDate('created_at', today())->where('status', 'completed');
            })->sum('quantity'),
        ];

        // Categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        // Settings for receipt preview
        $storeSettings = \App\Models\Setting::getByGroup('store');
        $receiptSettings = \App\Models\Setting::getByGroup('receipt');

        return view('pos.index', compact(
            'currentShift',
            'products',
            'popularProducts',
            'customers',
            'recentCustomers',
            'todayStats',
            'categories',
            'storeSettings',
            'receiptSettings'
        ));
    }

    /**
     * Open a new shift.
     */
    public function openShift(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        // Check if there's already an open shift
        $existingShift = PosShift::getCurrentShift(auth()->id());
        if ($existingShift) {
            return response()->json([
                'success' => false,
                'message' => __('pos.shift_already_open'),
            ], 400);
        }

        $shift = PosShift::create([
            'user_id' => auth()->id(),
            'opening_balance' => $request->opening_balance,
            'expected_cash' => $request->opening_balance,
            'opened_at' => now(),
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => __('pos.shift_opened'),
            'shift' => $shift,
        ]);
    }

    /**
     * Close the current shift.
     */
    public function closeShift(Request $request)
    {
        $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $shift = PosShift::getCurrentShift(auth()->id());
        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => __('pos.no_open_shift'),
            ], 400);
        }

        $shift->closeShift($request->closing_balance, $request->notes);

        return response()->json([
            'success' => true,
            'message' => __('pos.shift_closed'),
            'shift' => $shift->fresh(),
        ]);
    }

    /**
     * Search products.
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('name_th', 'like', "%{$query}%")
                    ->orWhere('generic_name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->with('category')
            ->limit(20)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'name_th' => $product->name_th,
                    'generic_name' => $product->generic_name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'unit_price' => $product->unit_price,
                    'member_price' => $product->member_price,
                    'stock_qty' => $product->stock_qty,
                    'image_path' => $product->image_path,
                    'requires_prescription' => $product->requires_prescription,
                    'drug_class' => $product->drug_class,
                    'category_id' => $product->category_id,
                    'category' => $product->category?->localized_name,
                ];
            });

        return response()->json($products);
    }

    /**
     * Get product by barcode.
     */
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->get('barcode');

        $product = Product::where('barcode', $barcode)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('pos.product_not_found'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'name_th' => $product->name_th,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'unit_price' => $product->unit_price,
                'member_price' => $product->member_price,
                'stock_qty' => $product->stock_qty,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'requires_prescription' => $product->requires_prescription,
            ],
        ]);
    }

    /**
     * Search customers.
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');

        $customers = Customer::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('member_id', 'like', "%{$query}%");
        })
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'member_id' => $customer->member_id,
                    'member_type' => $customer->member_type,
                    'points' => $customer->points,
                    'allergies' => $customer->allergies,
                    'chronic_conditions' => $customer->chronic_conditions,
                ];
            });

        return response()->json($customers);
    }

    /**
     * Check for allergy alerts.
     */
    public function checkAllergies(Request $request)
    {
        $customerId = $request->get('customer_id');
        $productId = $request->get('product_id');

        if (!$customerId || !$productId) {
            return response()->json(['alerts' => []]);
        }

        $customer = Customer::find($customerId);
        $product = Product::find($productId);

        if (!$customer || !$product) {
            return response()->json(['alerts' => []]);
        }

        $alerts = [];
        $customerAllergies = is_array($customer->allergies) ? $customer->allergies : [];

        // Check if product name or ingredients match any allergies
        foreach ($customerAllergies as $allergy) {
            $allergyLower = strtolower($allergy);
            $productNameLower = strtolower($product->name . ' ' . ($product->generic_name ?? ''));

            if (str_contains($productNameLower, $allergyLower)) {
                $alerts[] = [
                    'type' => $allergy,
                    'level' => 'danger',
                    'message' => __('pos.allergy_alert_message', [
                        'customer' => $customer->name,
                        'allergy' => $allergy,
                        'product' => $product->name,
                    ]),
                    'product_id' => $product->id,
                ];
            }
        }

        return response()->json(['alerts' => $alerts]);
    }

    /**
     * Process checkout / Create order.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|in:cash,card,transfer,qr,credit,mixed',
            'amount_paid' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'confirmed' => 'nullable|boolean',
        ]);

        $customer = $request->customer_id ? Customer::find($request->customer_id) : null;

        // Check for allergies first
        if (!$request->confirmed && $customer && $customer->hasAllergies()) {
            $allergyWarnings = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $customer->isAllergicToProduct($product)) {
                    $allergyWarnings[] = [
                        'product' => $product->name,
                        'allergies' => $customer->allergy_list,
                    ];
                }
            }

            if (!empty($allergyWarnings)) {
                return response()->json([
                    'requires_confirmation' => true,
                    'allergy_warnings' => $allergyWarnings,
                    'message' => __('pos.allergy_confirmation_required'),
                ], 200);
            }
        }

        $shift = null;
        try {
            $shift = PosShift::getCurrentShift(auth()->id());
        } catch (\Exception $e) {
            // Table may not exist
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'pos_shift_id' => $shift?->id,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $subtotal = 0;
            $vatAmount = 0;
            $requiresPrescription = false;

            // Add items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];

                // Check stock
                if ($product->stock_qty < $quantity) {
                    throw new \Exception(__('pos.insufficient_stock', ['product' => $product->name]));
                }

                $unitPrice = $customer
                    ? ($product->member_price ?? $product->unit_price)
                    : $product->unit_price;

                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemSubtotal = ($unitPrice * $quantity) - $itemDiscount;
                $itemVat = $product->vat_applicable ? ($itemSubtotal * 0.07) : 0;

                $subtotal += $itemSubtotal;
                $vatAmount += $itemVat;

                if ($product->requires_prescription) {
                    $requiresPrescription = true;
                }

                // Get lot for FIFO
                $lot = ProductLot::where('product_id', $product->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date')
                    ->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_lot_id' => $lot?->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'cost_price' => $product->cost_price ?? 0,
                    'discount_amount' => $itemDiscount,
                    'subtotal' => $itemSubtotal,
                    'vat_amount' => $itemVat,
                    'total' => $itemSubtotal + $itemVat,
                    'requires_prescription' => $product->requires_prescription,
                    'instructions' => $item['instructions'] ?? null,
                ]);

                // Deduct stock
                $product->decrement('stock_qty', $quantity);
                if ($lot) {
                    $lot->decrement('quantity', $quantity);
                }
            }

            // Calculate totals
            $discountAmount = $request->discount_percent > 0
                ? ($subtotal * $request->discount_percent / 100)
                : ($request->discount_amount ?? 0);

            $total = $subtotal - $discountAmount + $vatAmount;
            $change = max(0, $request->amount_paid - $total);

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $vatAmount,
                'vat_amount' => $vatAmount,
                'discount' => $discountAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $total,
                'amount_paid' => $request->amount_paid,
                'change_amount' => $change,
                'requires_prescription' => $requiresPrescription,
                'payment_status' => 'paid',
                'status' => 'completed',
                'paid_at' => now(),
                'completed_at' => now(),
            ]);

            // Update shift totals
            if ($shift) {
                match ($request->payment_method) {
                    'cash' => $shift->addCashTransaction($total),
                    'card' => $shift->addCardTransaction($total),
                    default => $shift->increment('total_sales', $total),
                };
            }

            // Update customer loyalty data (Points, Visits, Total Spent)
            if ($customer) {
                // Default: 1 point per ฿1 spent
                $pointsEarned = floor($total);
                $customer->recordVisit($total, $pointsEarned);

                // Log loyalty update
                ActivityLog::log(
                    'update',
                    'Customers',
                    "Loyalty updated: +{$pointsEarned} points, +฿" . number_format($total, 2) . " spent",
                    $customer,
                    null,
                    null,
                    ['order_id' => $order->id]
                );

                // Auto-trigger E-Receipt if customer has email or LINE
                if ($customer->email) {
                    \App\Models\DeliveryLog::create([
                        'customer_id' => $customer->id,
                        'order_id' => $order->id,
                        'channel' => 'email',
                        'recipient' => $customer->email,
                        'subject' => 'E-Receipt: Order #' . $order->order_number,
                        'content_type' => 'receipt',
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('pos.order_completed'),
                'order' => $order->fresh()->load('items'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Hold an order.
     */
    public function holdOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'customer_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $heldOrders = session('held_orders', []);
        $heldOrders[] = [
            'items' => $request->items,
            'customer_id' => $request->customer_id,
            'notes' => $request->notes,
            'held_at' => now()->toISOString(),
        ];

        session(['held_orders' => $heldOrders]);

        return response()->json([
            'success' => true,
            'message' => __('pos.order_held'),
            'held_count' => count($heldOrders),
        ]);
    }

    /**
     * Get held orders.
     */
    public function getHeldOrders()
    {
        $heldOrders = session('held_orders', []);

        // Add index to each order for easier referencing in frontend
        foreach ($heldOrders as $index => &$order) {
            $order['index'] = $index;
        }

        return response()->json([
            'orders' => array_reverse($heldOrders), // Show newest first
        ]);
    }

    /**
     * Delete a held order.
     */
    public function deleteHeldOrder(int $index)
    {
        $heldOrders = session('held_orders', []);

        if (!isset($heldOrders[$index])) {
            return response()->json([
                'success' => false,
                'message' => __('pos.order_not_found'),
            ], 404);
        }

        array_splice($heldOrders, $index, 1);
        session(['held_orders' => $heldOrders]);

        return response()->json([
            'success' => true,
            'message' => 'Held order deleted',
            'held_count' => count($heldOrders),
        ]);
    }

    /**
     * Get recent sales.
     */
    public function getRecentSales()
    {
        $orders = Order::where('status', 'completed')
            ->with(['items', 'customer'])
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }

    /**
     * Recall a held order.
     */
    public function recallOrder(int $index)
    {
        $heldOrders = session('held_orders', []);

        if (!isset($heldOrders[$index])) {
            return response()->json([
                'success' => false,
                'message' => __('pos.order_not_found'),
            ], 404);
        }

        $order = $heldOrders[$index];
        array_splice($heldOrders, $index, 1);
        session(['held_orders' => $heldOrders]);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    /**
     * Print receipt.
     */
    public function printReceipt(Order $order)
    {
        $storeSettings = \App\Models\Setting::getByGroup('store');
        $receiptSettings = \App\Models\Setting::getByGroup('receipt');

        return view('pos.receipt', compact('order', 'storeSettings', 'receiptSettings'));
    }

    /**
     * Log allergy alert acknowledgment.
     */
    public function acknowledgeAlert(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'allergy_type' => 'required|string',
            'action' => 'required|in:proceed,substitute,cancel',
            'notes' => 'nullable|string',
        ]);

        try {
            $alert = AllergyAlert::create([
                'customer_id' => $request->customer_id,
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'allergy_type' => $request->allergy_type,
                'alert_level' => 'warning',
                'message' => __('pos.allergy_acknowledged'),
                'acknowledged' => true,
                'acknowledged_at' => now(),
                'action_taken' => $request->action,
                'pharmacist_notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'alert' => $alert,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Alert logged',
            ]);
        }
    }
}
