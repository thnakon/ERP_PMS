<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Product;
use Carbon\Carbon;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please seed products first.');
            return;
        }

        foreach ($products as $product) {
            // 1. Create an EXPIRED batch (6 months ago)
            Batch::create([
                'product_id' => $product->id,
                'batch_number' => 'LOT-' . strtoupper(uniqid()) . '-EXP',
                'expiry_date' => Carbon::now()->subMonths(6),
                'quantity' => rand(10, 50),
                'cost_price' => $product->cost_price ?? 100,
                'selling_price' => $product->selling_price ?? 150,
            ]);

            // 2. Create a NEAR EXPIRY batch (expires in 1 month)
            Batch::create([
                'product_id' => $product->id,
                'batch_number' => 'LOT-' . strtoupper(uniqid()) . '-NEAR',
                'expiry_date' => Carbon::now()->addMonth(),
                'quantity' => rand(20, 100),
                'cost_price' => $product->cost_price ?? 100,
                'selling_price' => $product->selling_price ?? 150,
            ]);

            // 3. Create a GOOD batch (expires in 1 year)
            Batch::create([
                'product_id' => $product->id,
                'batch_number' => 'LOT-' . strtoupper(uniqid()) . '-GOOD',
                'expiry_date' => Carbon::now()->addYear(),
                'quantity' => rand(50, 200),
                'cost_price' => $product->cost_price ?? 100,
                'selling_price' => $product->selling_price ?? 150,
            ]);
        }
    }
}
