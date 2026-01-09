<?php

namespace Database\Seeders;

use App\Models\PromotionUsage;
use App\Models\Promotion;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromotionUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = Promotion::all();
        $orders = Order::where('status', 'completed')->get();
        $customers = Customer::all();

        if ($promotions->isEmpty() || $orders->isEmpty()) {
            $this->command->warn('⚠️ Need promotions and completed orders to create usage history.');
            return;
        }

        $usagesCreated = 0;

        foreach ($promotions as $promotion) {
            // Create usage records based on usage_count
            $usageCount = min($promotion->usage_count, $orders->count());

            if ($usageCount <= 0) continue;

            $selectedOrders = $orders->random(min($usageCount, $orders->count()));

            foreach ($selectedOrders as $order) {
                // Calculate realistic discount amount
                $discountAmount = match ($promotion->type) {
                    'percentage' => min(
                        $order->total_amount * ($promotion->discount_value / 100),
                        $promotion->max_discount ?? PHP_FLOAT_MAX
                    ),
                    'fixed_amount' => $promotion->discount_value,
                    'buy_x_get_y' => rand(50, 300),
                    default => rand(20, 200),
                };

                PromotionUsage::updateOrCreate(
                    [
                        'promotion_id' => $promotion->id,
                        'order_id' => $order->id,
                    ],
                    [
                        'customer_id' => $order->customer_id ?? ($customers->isNotEmpty() ? $customers->random()->id : null),
                        'discount_amount' => round($discountAmount, 2),
                        'created_at' => $order->created_at ?? Carbon::now()->subDays(rand(1, 30)),
                    ]
                );
                $usagesCreated++;
            }
        }

        $this->command->info("✅ Created {$usagesCreated} promotion usage records");
    }
}
