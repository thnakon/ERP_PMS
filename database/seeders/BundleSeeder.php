<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $products = Product::where('is_active', true)->get();

        if ($products->count() < 6) {
            $this->command->warn('⚠️ Not enough products to create bundles. Need at least 6 active products.');
            return;
        }

        // Helper to get random products
        $getProducts = fn($count) => $products->random(min($count, $products->count()));

        // 1. ชุดสุขภาพครบวงจร
        $bundle1 = Bundle::updateOrCreate(
            ['name' => 'Complete Health Pack'],
            [
                'name_th' => 'ชุดสุขภาพครบวงจร',
                'description' => 'Essential vitamins and supplements for daily health maintenance.',
                'bundle_price' => 599,
                'original_price' => 850,
                'savings' => 251,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now->copy()->addDays(60),
                'stock_limit' => 100,
                'sold_count' => 47,
                'is_active' => true,
            ]
        );
        $bundle1->products()->sync($this->mapProductsWithQty($getProducts(3), [1, 1, 1]));

        // 2. ชุดเสริมภูมิคุ้มกัน
        $bundle2 = Bundle::updateOrCreate(
            ['name' => 'Immunity Booster Set'],
            [
                'name_th' => 'ชุดเสริมภูมิคุ้มกัน',
                'description' => 'Boost your immune system with this powerful combination.',
                'bundle_price' => 449,
                'original_price' => 620,
                'savings' => 171,
                'start_date' => $now->copy()->subDays(14),
                'end_date' => $now->copy()->addDays(45),
                'stock_limit' => 50,
                'sold_count' => 28,
                'is_active' => true,
            ]
        );
        $bundle2->products()->sync($this->mapProductsWithQty($getProducts(2), [2, 1]));

        // 3. ชุดดูแลผิวพรรณ
        $bundle3 = Bundle::updateOrCreate(
            ['name' => 'Skincare Essentials'],
            [
                'name_th' => 'ชุดดูแลผิวพรรณ',
                'description' => 'Complete skincare routine for radiant, healthy skin.',
                'bundle_price' => 799,
                'original_price' => 1100,
                'savings' => 301,
                'start_date' => $now->copy()->subDays(7),
                'end_date' => $now->copy()->addDays(30),
                'stock_limit' => 30,
                'sold_count' => 12,
                'is_active' => true,
            ]
        );
        $bundle3->products()->sync($this->mapProductsWithQty($getProducts(4), [1, 1, 1, 1]));

        // 4. ชุดปฐมพยาบาล
        $bundle4 = Bundle::updateOrCreate(
            ['name' => 'First Aid Essentials'],
            [
                'name_th' => 'ชุดปฐมพยาบาลเบื้องต้น',
                'description' => 'Everything you need for basic first aid at home.',
                'bundle_price' => 299,
                'original_price' => 420,
                'savings' => 121,
                'start_date' => $now->copy()->subDays(60),
                'end_date' => null, // No end date
                'stock_limit' => null, // Unlimited
                'sold_count' => 156,
                'is_active' => true,
            ]
        );
        $bundle4->products()->sync($this->mapProductsWithQty($getProducts(5), [1, 2, 1, 1, 1]));

        // 5. ชุดดูแลทารก
        $bundle5 = Bundle::updateOrCreate(
            ['name' => 'Baby Care Bundle'],
            [
                'name_th' => 'ชุดดูแลเด็กทารก',
                'description' => 'Gentle and safe products for your little one.',
                'bundle_price' => 399,
                'original_price' => 550,
                'savings' => 151,
                'start_date' => $now->copy()->subDays(21),
                'end_date' => $now->copy()->addDays(60),
                'stock_limit' => 40,
                'sold_count' => 19,
                'is_active' => true,
            ]
        );
        $bundle5->products()->sync($this->mapProductsWithQty($getProducts(3), [1, 1, 2]));

        // 6. ชุดดูแลผู้สูงอายุ
        $bundle6 = Bundle::updateOrCreate(
            ['name' => 'Senior Health Pack'],
            [
                'name_th' => 'ชุดดูแลสุขภาพผู้สูงวัย',
                'description' => 'Essential supplements and medications for seniors.',
                'bundle_price' => 899,
                'original_price' => 1250,
                'savings' => 351,
                'start_date' => $now->copy()->subDays(45),
                'end_date' => $now->copy()->addDays(90),
                'stock_limit' => 60,
                'sold_count' => 34,
                'is_active' => true,
            ]
        );
        $bundle6->products()->sync($this->mapProductsWithQty($getProducts(4), [1, 1, 1, 2]));

        // 7. ชุดออฟฟิศ (หมดสต็อก)
        $bundle7 = Bundle::updateOrCreate(
            ['name' => 'Office Wellness Kit'],
            [
                'name_th' => 'ชุดสุขภาพคนทำงาน',
                'description' => 'Stay healthy at work with this office-friendly bundle.',
                'bundle_price' => 349,
                'original_price' => 480,
                'savings' => 131,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now->copy()->addDays(30),
                'stock_limit' => 25,
                'sold_count' => 25, // Sold out!
                'is_active' => true,
            ]
        );
        $bundle7->products()->sync($this->mapProductsWithQty($getProducts(3), [1, 1, 1]));

        // 8. ชุดหน้าหนาว (Seasonal)
        $bundle8 = Bundle::updateOrCreate(
            ['name' => 'Winter Wellness'],
            [
                'name_th' => 'ชุดรับมือหน้าหนาว',
                'description' => 'Stay warm and healthy during the cold season.',
                'bundle_price' => 549,
                'original_price' => 750,
                'savings' => 201,
                'start_date' => $now->copy()->subDays(14),
                'end_date' => $now->copy()->addDays(45),
                'stock_limit' => 80,
                'sold_count' => 41,
                'is_active' => true,
            ]
        );
        $bundle8->products()->sync($this->mapProductsWithQty($getProducts(3), [2, 1, 1]));

        // 9. ชุดนักกีฬา (Inactive)
        $bundle9 = Bundle::updateOrCreate(
            ['name' => 'Athlete Recovery Pack'],
            [
                'name_th' => 'ชุดฟื้นฟูนักกีฬา',
                'description' => 'Recovery essentials for athletes and fitness enthusiasts.',
                'bundle_price' => 699,
                'original_price' => 950,
                'savings' => 251,
                'start_date' => $now->copy()->subDays(90),
                'end_date' => $now->copy()->subDays(30), // Expired
                'stock_limit' => 50,
                'sold_count' => 38,
                'is_active' => false,
            ]
        );
        $bundle9->products()->sync($this->mapProductsWithQty($getProducts(4), [1, 1, 1, 1]));

        // 10. ชุดตรวจสุขภาพ
        $bundle10 = Bundle::updateOrCreate(
            ['name' => 'Home Health Check'],
            [
                'name_th' => 'ชุดตรวจสุขภาพที่บ้าน',
                'description' => 'Monitor your health from the comfort of home.',
                'bundle_price' => 1299,
                'original_price' => 1800,
                'savings' => 501,
                'start_date' => $now->copy()->subDays(7),
                'end_date' => $now->copy()->addDays(60),
                'stock_limit' => 20,
                'sold_count' => 8,
                'is_active' => true,
            ]
        );
        $bundle10->products()->sync($this->mapProductsWithQty($getProducts(3), [1, 1, 1]));

        $this->command->info('✅ Created 10 sample bundles with realistic sales data');
    }

    /**
     * Map products to quantities for bundle items
     */
    private function mapProductsWithQty($products, array $quantities): array
    {
        $result = [];
        $i = 0;
        foreach ($products as $product) {
            $result[$product->id] = ['quantity' => $quantities[$i] ?? 1];
            $i++;
        }
        return $result;
    }
}
