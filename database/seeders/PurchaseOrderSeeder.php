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

        // 1. Create 'ordered' POs (Pending Reception)
        for ($i = 0; $i < 5; $i++) {
            $supplier = $suppliers->random();
            $purchaseDate = Carbon::now()->subDays(rand(1, 7)); // Recent orders

            $po = Purchase::create([
                'supplier_id' => $supplier->id,
                'reference_number' => 'PO-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'purchase_date' => $purchaseDate,
                'status' => 'ordered',
                'total_amount' => 0,
            ]);

            $this->createPoItems($po, $products);
        }

        // 2. Create 'completed' POs (History)
        for ($i = 0; $i < 10; $i++) {
            $supplier = $suppliers->random();
            $purchaseDate = Carbon::now()->subDays(rand(10, 60)); // Older orders

            $po = Purchase::create([
                'supplier_id' => $supplier->id,
                'reference_number' => 'PO-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'purchase_date' => $purchaseDate,
                'status' => 'completed',
                'total_amount' => 0,
            ]);

            $this->createPoItems($po, $products);
        }
    }

    private function createPoItems($po, $products)
    {
        $totalAmount = 0;
        $itemCount = rand(2, 6);

        for ($j = 0; $j < $itemCount; $j++) {
            $product = $products->random();
            $quantity = rand(10, 100);
            $costPrice = $product->price * 0.7;

            PurchaseItem::create([
                'purchase_id' => $po->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'cost_price' => $costPrice,
            ]);

            $totalAmount += $quantity * $costPrice;
        }

        $po->update(['total_amount' => $totalAmount]);
    }
}
