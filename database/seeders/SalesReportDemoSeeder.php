<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesReportDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('is_active', true)->take(10)->get();
        $customers = Customer::take(5)->get();
        $users = User::take(3)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $paymentMethods = ['cash', 'card', 'transfer'];

        // Create 20 orders spread over the last 30 days
        for ($i = 0; $i < 20; $i++) {
            // Random date within last 30 days
            $daysAgo = rand(0, 30);
            $hoursAgo = rand(8, 20); // Business hours
            $orderDate = Carbon::now()->subDays($daysAgo)->setHour($hoursAgo)->setMinute(rand(0, 59));

            // Random user (cashier)
            $user = $users->random();

            // Random customer (50% chance of being a member)
            $customer = rand(0, 1) && $customers->isNotEmpty() ? $customers->random() : null;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer?->id,
                'user_id' => $user->id,
                'subtotal' => 0,
                'discount' => rand(0, 1) ? rand(10, 100) : 0,
                'tax' => 0,
                'total_amount' => 0,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'amount_paid' => 0,
                'change_amount' => 0,
                'status' => 'completed',
                'paid_at' => $orderDate,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Add 1-5 random items to order
            $itemCount = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->unit_price ?? rand(50, 500);
                $itemSubtotal = $unitPrice * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount' => 0,
                    'subtotal' => $itemSubtotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                $subtotal += $itemSubtotal;
            }

            // Update order totals
            $totalAmount = $subtotal - $order->discount;
            $amountPaid = ceil($totalAmount / 100) * 100; // Round up to nearest 100

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'change_amount' => $amountPaid - $totalAmount,
            ]);
        }

        $this->command->info('Created 20 demo orders for Sales Report!');
    }
}
