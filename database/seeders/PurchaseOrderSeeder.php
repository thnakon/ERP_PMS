<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $user = User::first();

        if ($suppliers->isEmpty() || $products->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 25; $i++) {
            $supplier = $suppliers->random();
            $orderDate = Carbon::now()->subDays(rand(1, 60));
            $status = collect(['draft', 'sent', 'partial', 'completed'])->random();

            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'user_id' => $user->id,
                'order_date' => $orderDate,
                'expected_date' => (clone $orderDate)->addDays($supplier->lead_time ?? 7),
                'status' => $status,
                'notes' => 'Seeded purchase order ' . $i,
                'discount_amount' => rand(0, 500),
                'sent_at' => $status !== 'draft' ? $orderDate : null,
                'completed_at' => $status === 'completed' ? (clone $orderDate)->addDays(rand(2, 5)) : null,
            ]);

            // Add 2-5 items per PO
            $itemCount = rand(2, 5);
            $selectedProducts = $products->random($itemCount);

            foreach ($selectedProducts as $product) {
                $qty = rand(10, 100);
                $cost = $product->cost_price > 0 ? $product->cost_price : rand(50, 500);
                $received = 0;

                if ($status === 'completed') {
                    $received = $qty;
                } elseif ($status === 'partial') {
                    $received = rand(1, $qty - 1);
                }

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'ordered_qty' => $qty,
                    'received_qty' => $received,
                    'unit_cost' => $cost,
                    'line_total' => $qty * $cost,
                ]);
            }

            $po->calculateTotals();
        }

        $this->command->info('Created 25 Purchase Orders!');
    }
}
