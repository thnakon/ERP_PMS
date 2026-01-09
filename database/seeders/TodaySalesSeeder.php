<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TodaySalesSeeder extends Seeder
{
    /**
     * Run the database seeds - creates orders for TODAY spread across business hours.
     */
    public function run(): void
    {
        $products = Product::where('is_active', true)->take(10)->get();
        $customers = Customer::take(5)->get();
        $users = User::take(3)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users found.');
            return;
        }

        $paymentMethods = ['cash', 'card', 'transfer'];
        $today = Carbon::today();

        // Create orders spread across business hours (8:00 - 20:00)
        $hours = [8, 9, 10, 10, 11, 11, 12, 12, 12, 13, 14, 14, 15, 16, 17, 17, 18, 18, 19, 20];

        foreach ($hours as $hour) {
            $orderDate = $today->copy()->setHour($hour)->setMinute(rand(0, 59))->setSecond(rand(0, 59));

            $user = $users->random();
            $customer = rand(0, 1) && $customers->isNotEmpty() ? $customers->random() : null;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer?->id,
                'user_id' => $user->id,
                'subtotal' => 0,
                'discount' => rand(0, 1) ? rand(5, 50) : 0,
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

            // Add 1-4 random items
            $itemCount = rand(1, 4);
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

            $totalAmount = $subtotal - $order->discount;
            $amountPaid = ceil($totalAmount / 100) * 100;

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'change_amount' => $amountPaid - $totalAmount,
            ]);
        }

        $this->command->info('Created 20 orders for TODAY spread across business hours!');
    }
}
