<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GoodsReceived;
use App\Models\GoodsReceivedItem;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductLot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GoodsReceivedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $user = User::first();
        $pos = PurchaseOrder::whereIn('status', ['partial', 'completed'])->get();

        if ($suppliers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // 1. Create GRs from POs
        $i = 1;
        foreach ($pos as $po) {
            $grDate = $po->sent_at ? (clone $po->sent_at)->addDays(rand(1, 4)) : Carbon::now()->subDays(rand(1, 10));

            $gr = GoodsReceived::create([
                'gr_number' => 'GR-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'user_id' => $user->id,
                'received_date' => $grDate,
                'invoice_no' => 'INV-' . rand(10000, 99999),
                'status' => 'completed',
                'notes' => 'Seeded from PO ' . $po->po_number,
            ]);

            foreach ($po->items as $item) {
                if ($item->received_qty <= 0) continue;

                $grItem = GoodsReceivedItem::create([
                    'goods_received_id' => $gr->id,
                    'product_id' => $item->product_id,
                    'purchase_order_item_id' => $item->id,
                    'ordered_qty' => $item->ordered_qty,
                    'received_qty' => $item->received_qty,
                    'unit_cost' => $item->unit_cost,
                    'line_total' => $item->received_qty * $item->unit_cost,
                    'lot_number' => 'LOT-' . rand(1000, 9999),
                    'expiry_date' => Carbon::now()->addMonths(rand(6, 24)),
                ]);

                // Also create a lot for this
                ProductLot::create([
                    'product_id' => $item->product_id,
                    'supplier_id' => $po->supplier_id,
                    'lot_number' => $grItem->lot_number,
                    'expiry_date' => $grItem->expiry_date,
                    'quantity' => $grItem->received_qty,
                    'initial_quantity' => $grItem->received_qty,
                    'cost_price' => $grItem->unit_cost,
                    'gr_reference' => $gr->gr_number,
                    'received_at' => $grDate,
                ]);
            }

            $gr->calculateTotal();
            $i++;
        }

        // 2. Create some Direct GRs (without PO) to reach 20+
        for ($j = 1; $j <= 10; $j++) {
            $supplier = $suppliers->random();
            $grDate = Carbon::now()->subDays(rand(1, 30));

            $gr = GoodsReceived::create([
                'gr_number' => 'GR-DIR-' . date('Ymd') . '-' . str_pad($j, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'user_id' => $user->id,
                'received_date' => $grDate,
                'invoice_no' => 'INV-DIR-' . rand(10000, 99999),
                'status' => 'completed',
                'notes' => 'Direct GR seeded ' . $j,
            ]);

            $itemCount = rand(1, 3);
            $selectedProducts = $products->random($itemCount);

            foreach ($selectedProducts as $product) {
                $qty = rand(5, 50);
                $cost = $product->cost_price > 0 ? $product->cost_price : rand(50, 500);

                $grItem = GoodsReceivedItem::create([
                    'goods_received_id' => $gr->id,
                    'product_id' => $product->id,
                    'ordered_qty' => 0,
                    'received_qty' => $qty,
                    'unit_cost' => $cost,
                    'line_total' => $qty * $cost,
                    'lot_number' => 'LOT-D-' . rand(1000, 9999),
                    'expiry_date' => Carbon::now()->addMonths(rand(12, 36)),
                ]);

                ProductLot::create([
                    'product_id' => $product->id,
                    'supplier_id' => $supplier->id,
                    'lot_number' => $grItem->lot_number,
                    'expiry_date' => $grItem->expiry_date,
                    'quantity' => $grItem->received_qty,
                    'initial_quantity' => $grItem->received_qty,
                    'cost_price' => $grItem->unit_cost,
                    'gr_reference' => $gr->gr_number,
                    'received_at' => $grDate,
                ]);
            }

            $gr->calculateTotal();
        }

        $this->command->info('Created Goods Received entries!');
    }
}
