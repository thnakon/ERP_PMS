<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockAdjustmentSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        $lots = ProductLot::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $adjustmentTypes = ['increase', 'decrease', 'set'];
        $reasons = [
            'increase' => ['รับสินค้าเพิ่ม', 'นับสต็อกพบส่วนเกิน', 'สินค้าคืนจากลูกค้า', 'โอนจากสาขาอื่น', 'แก้ไขการขายผิดพลาด'],
            'decrease' => ['สินค้าเสียหาย', 'นับสต็อกพบขาด', 'โอนไปสาขาอื่น', 'ตัวอย่างสินค้า', 'สินค้าหมดอายุ', 'ต้องทำลายตามกฎหมาย', 'สินค้าแตกเสียหาย', 'บรรจุภัณฑ์ชำรุด'],
            'set' => ['นับสต็อกประจำเดือน', 'ตรวจนับประจำปี', 'ปรับปรุงให้ตรงระบบ', 'แก้ไขยอดคงเหลือ'],
        ];

        $adjustmentCount = 0;

        // Create 50-100 stock adjustments
        $numAdjustments = rand(50, 100);

        for ($i = 0; $i < $numAdjustments; $i++) {
            $product = $products->random();
            $type = $adjustmentTypes[array_rand($adjustmentTypes)];
            $user = $users->random();

            // Try to get a lot for this product
            $productLots = $lots->where('product_id', $product->id);
            $lot = $productLots->isNotEmpty() ? $productLots->random() : null;

            // Determine quantity based on type
            $quantity = rand(1, 50);
            $beforeQty = $product->stock_qty;

            switch ($type) {
                case 'increase':
                    $afterQty = $beforeQty + $quantity;
                    break;
                case 'decrease':
                    $afterQty = max(0, $beforeQty - $quantity);
                    break;
                case 'set':
                    $afterQty = rand(0, $beforeQty + 100);
                    $quantity = abs($afterQty - $beforeQty);
                    break;
            }

            StockAdjustment::create([
                'adjustment_number' => 'ADJ-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'product_id' => $product->id,
                'product_lot_id' => $lot?->id,
                'user_id' => $user->id,
                'type' => $type,
                'quantity' => $quantity,
                'before_quantity' => $beforeQty,
                'after_quantity' => $afterQty,
                'reason' => $reasons[$type][array_rand($reasons[$type])],
                'notes' => rand(0, 3) === 0 ? 'หมายเหตุเพิ่มเติม: ตรวจสอบแล้วถูกต้อง' : null,
                'adjusted_at' => Carbon::now()->subDays(rand(0, 60))->subHours(rand(0, 23)),
            ]);

            $adjustmentCount++;
        }

        $this->command->info("Created {$adjustmentCount} stock adjustments!");
    }
}
