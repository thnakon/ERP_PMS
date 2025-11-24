<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have suppliers and products
        if (Supplier::count() == 0) {
            $this->call(SupplierSeeder::class);
        }
        if (Product::count() == 0) {
            $this->call(ProductSeeder::class);
        }

        $suppliers = Supplier::all();
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $statuses = ['draft', 'ordered', 'completed', 'cancelled'];

        foreach ($statuses as $index => $status) {
            for ($i = 0; $i < 3; $i++) {
                $supplier = $suppliers->random();
                $purchaseDate = Carbon::now()->subDays(rand(1, 30));

                $po = Purchase::create([
                    'supplier_id' => $supplier->id,
                    'reference_number' => 'PO-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'purchase_date' => $purchaseDate,
                    'status' => $status,
                    'total_amount' => 0, // Will calculate below
                ]);

                $totalAmount = 0;
                $itemCount = rand(1, 5);

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(10, 100);
                    $costPrice = $product->price * 0.7; // Assume cost is 70% of price

                    PurchaseItem::create([
                        'purchase_id' => $po->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'cost_price' => $costPrice,
                        // 'expiry_date' => $purchaseDate->addMonths(rand(6, 24)), // Optional for PO
                    ]);

                    $totalAmount += $quantity * $costPrice;
                }

                $po->update(['total_amount' => $totalAmount]);
            }
        }
    }
}
