<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Create orders for past 3 months and future 3 months (scheduled)
     */
    public function run(): void
    {
        $products = Product::all();
        $customers = Customer::all();
        $staffUsers = User::whereIn('role', ['staff', 'pharmacist', 'admin'])->where('status', 'active')->get();

        if ($products->isEmpty() || $staffUsers->isEmpty()) {
            $this->command->warn('Please seed products and users first!');
            return;
        }

        $orderCount = 0;
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now();

        // Generate orders for each day in the past 3 months
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip some Sundays (less traffic)
            if ($date->isSunday() && rand(1, 10) <= 3) {
                continue;
            }

            // Random orders per day (15-60 orders for a busy pharmacy)
            $ordersToday = rand(15, 60);

            // More orders on weekends
            if ($date->isSaturday()) {
                $ordersToday = rand(25, 70);
            }

            // Less orders on holidays (Thai holidays simulation)
            $thaiHolidays = [
                '01-01',
                '04-06',
                '05-01',
                '05-04',
                '05-06',
                '06-03',
                '07-28',
                '08-12',
                '10-13',
                '10-23',
                '12-05',
                '12-10',
                '12-31'
            ];
            if (in_array($date->format('m-d'), $thaiHolidays)) {
                $ordersToday = rand(5, 20);
            }

            for ($i = 0; $i < $ordersToday; $i++) {
                $staff = $staffUsers->random();
                $customer = rand(1, 100) <= 40 ? $customers->random() : null;

                // Peak hours: 9-11 AM and 5-8 PM
                $hour = $this->getRandomHour();
                $orderTime = $date->copy()->setTime($hour, rand(0, 59), rand(0, 59));

                $order = Order::create([
                    'order_number' => 'INV-' . $orderTime->format('Ymd') . '-' . str_pad(++$orderCount, 5, '0', STR_PAD_LEFT),
                    'customer_id' => $customer?->id,
                    'user_id' => $staff->id,
                    'pharmacist_id' => User::where('role', 'pharmacist')->where('status', 'active')->inRandomOrder()->first()?->id,
                    'status' => $this->getRandomStatus(),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'payment_status' => 'paid',
                    'subtotal' => 0,
                    'discount' => 0,
                    'discount_amount' => 0,
                    'vat_amount' => 0,
                    'total_amount' => 0,
                    'amount_paid' => 0,
                    'change_amount' => 0,
                    'notes' => null,
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime,
                ]);

                // Add 1-8 items per order
                $itemCount = rand(1, 6);
                $orderProducts = $products->random(min($itemCount, $products->count()));
                $subtotal = 0;

                foreach ($orderProducts as $product) {
                    $qty = rand(1, 4);
                    $price = $product->unit_price;
                    $itemTotal = $qty * $price;
                    $subtotal += $itemTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'discount' => 0,
                        'subtotal' => $itemTotal,
                    ]);
                }

                // Apply discount for members (10-15%)
                $discount = 0;
                if ($customer && rand(1, 100) <= 60) {
                    $discount = rand(10, 15) / 100 * $subtotal;
                }

                $vat = ($subtotal - $discount) * 0.07;
                $total = $subtotal - $discount + $vat;
                $amountPaid = $this->getRoundedAmount($total);

                $order->update([
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'discount_amount' => $discount,
                    'vat_amount' => $vat,
                    'total_amount' => $total,
                    'amount_paid' => $amountPaid,
                    'change_amount' => $amountPaid - $total,
                ]);
            }
        }

        $this->command->info("Created {$orderCount} orders for the past 3 months!");
    }

    private function getRandomHour(): int
    {
        $hours = [8 => 5, 9 => 15, 10 => 20, 11 => 15, 12 => 10, 13 => 8, 14 => 8, 15 => 10, 16 => 12, 17 => 20, 18 => 25, 19 => 18, 20 => 10, 21 => 5];
        $total = array_sum($hours);
        $random = rand(1, $total);
        $cumulative = 0;

        foreach ($hours as $hour => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $hour;
            }
        }
        return 12;
    }

    private function getRandomStatus(): string
    {
        $rand = rand(1, 100);
        return $rand <= 92 ? 'completed' : ($rand <= 97 ? 'pending' : 'refunded');
    }

    private function getRandomPaymentMethod(): string
    {
        $rand = rand(1, 100);
        return $rand <= 55 ? 'cash' : ($rand <= 80 ? 'card' : ($rand <= 95 ? 'transfer' : 'credit'));
    }

    private function getRoundedAmount(float $total): float
    {
        return max(ceil($total / 20) * 20, $total);
    }
}
