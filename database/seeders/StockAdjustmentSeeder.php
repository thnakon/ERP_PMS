<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class StockAdjustmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $products = Product::all();
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->command->info('No admin users found. Please seed users first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please seed products first.');
            return;
        }

        foreach ($products as $product) {
            // Create 1-3 adjustments per product
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                StockAdjustment::create([
                    'product_id' => $product->id,
                    'user_id' => $admins->random()->id,
                    'type' => rand(0, 1) ? 'addition' : 'subtraction',
                    'quantity' => rand(1, 10),
                    'reason' => ['Damaged', 'Theft/Loss', 'Inventory Count', 'Internal Use'][rand(0, 3)],
                    'note' => 'Auto-generated adjustment log.',
                    'created_at' => Carbon::now()->subDays(rand(0, 30))
                ]);
            }
        }
    }
}
