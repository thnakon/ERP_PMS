<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Models\MemberTier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $products = Product::all();
        $categories = Category::all();
        $tiers = MemberTier::all();

        // 1. ส่วนลดสมาชิกใหม่ (New Customer Discount)
        $promo1 = Promotion::updateOrCreate(
            ['name' => 'Welcome New Member'],
            [
                'name_th' => 'ต้อนรับสมาชิกใหม่',
                'code' => 'WELCOME10',
                'description' => 'Get 10% off on your first purchase! Welcome to our pharmacy family.',
                'description_th' => 'รับส่วนลด 10% สำหรับการซื้อครั้งแรก! ยินดีต้อนรับสู่ครอบครัวเรา',
                'type' => 'percentage',
                'discount_value' => 10,
                'min_purchase' => 300,
                'max_discount' => 500,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now->copy()->addDays(60),
                'usage_limit' => 1000,
                'usage_count' => 127,
                'per_customer_limit' => 1,
                'new_customers_only' => true,
                'stackable' => false,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 2. ซื้อ 2 แถม 1 - วิตามินซี
        $promo2 = Promotion::updateOrCreate(
            ['name' => 'Vitamin C Buy 2 Get 1'],
            [
                'name_th' => 'วิตามินซี ซื้อ 2 แถม 1',
                'code' => null,
                'description' => 'Buy 2 bottles of Vitamin C and get 1 FREE!',
                'description_th' => 'ซื้อวิตามินซี 2 ขวด รับฟรีอีก 1 ขวด!',
                'type' => 'buy_x_get_y',
                'discount_value' => 0,
                'min_purchase' => 0,
                'buy_quantity' => 2,
                'get_quantity' => 1,
                'start_date' => $now->copy()->subDays(14),
                'end_date' => $now->copy()->addDays(30),
                'usage_limit' => 500,
                'usage_count' => 89,
                'stackable' => false,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 3. Happy Hour - ส่วนลดช่วงเย็น
        $promo3 = Promotion::updateOrCreate(
            ['name' => 'Happy Hour Evening Sale'],
            [
                'name_th' => 'Happy Hour ลดพิเศษช่วงเย็น',
                'code' => null,
                'description' => 'Every day 5PM-8PM, get 5% off on all purchases!',
                'description_th' => 'ทุกวัน 17:00-20:00 รับส่วนลด 5% ทุกรายการ!',
                'type' => 'percentage',
                'discount_value' => 5,
                'min_purchase' => 200,
                'max_discount' => 300,
                'start_date' => $now->copy()->subDays(60),
                'end_date' => $now->copy()->addDays(90),
                'start_time' => '17:00',
                'end_time' => '20:00',
                'usage_count' => 342,
                'stackable' => true,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        // 4. Weekend Special
        $promo4 = Promotion::updateOrCreate(
            ['name' => 'Weekend Special'],
            [
                'name_th' => 'โปรสุดสัปดาห์',
                'code' => 'WEEKEND15',
                'description' => 'Every Saturday & Sunday, get extra 15% off!',
                'description_th' => 'ทุกวันเสาร์-อาทิตย์ รับส่วนลดพิเศษ 15%!',
                'type' => 'percentage',
                'discount_value' => 15,
                'min_purchase' => 500,
                'max_discount' => 1000,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now->copy()->addDays(60),
                'active_days' => [0, 6], // Sunday and Saturday
                'usage_limit' => 2000,
                'usage_count' => 456,
                'per_customer_limit' => 4,
                'stackable' => false,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 5. ส่วนลดเฉพาะสมาชิก Gold+
        $goldTier = $tiers->where('name', 'Gold')->first();
        if ($goldTier) {
            $promo5 = Promotion::updateOrCreate(
                ['name' => 'Gold Member Exclusive'],
                [
                    'name_th' => 'สิทธิพิเศษสมาชิก Gold',
                    'code' => 'GOLD20',
                    'description' => 'Exclusive 20% off for Gold members and above!',
                    'description_th' => 'ส่วนลดพิเศษ 20% สำหรับสมาชิก Gold ขึ้นไป!',
                    'type' => 'percentage',
                    'discount_value' => 20,
                    'min_purchase' => 1000,
                    'max_discount' => 2000,
                    'start_date' => $now->copy()->subDays(7),
                    'end_date' => $now->copy()->addDays(21),
                    'member_tier_id' => $goldTier->id,
                    'usage_count' => 34,
                    'stackable' => false,
                    'is_active' => true,
                    'is_featured' => true,
                ]
            );
        }

        // 6. ส่วนลดคงที่ ฿50
        $promo6 = Promotion::updateOrCreate(
            ['name' => 'Flat ฿50 Off'],
            [
                'name_th' => 'ลดทันที ฿50',
                'code' => 'SAVE50',
                'description' => 'Get ฿50 off on orders above ฿500!',
                'description_th' => 'รับส่วนลด ฿50 เมื่อซื้อครบ ฿500!',
                'type' => 'fixed_amount',
                'discount_value' => 50,
                'min_purchase' => 500,
                'start_date' => $now->copy()->subDays(14),
                'end_date' => $now->copy()->addDays(45),
                'usage_limit' => 500,
                'usage_count' => 178,
                'stackable' => false,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        // 7. Flash Sale - หมดอายุแล้ว (for history)
        Promotion::updateOrCreate(
            ['name' => 'January Flash Sale'],
            [
                'name_th' => 'Flash Sale มกราคม',
                'code' => 'FLASH25',
                'description' => 'Limited time 25% off everything!',
                'description_th' => 'ลดทั้งร้าน 25% เวลาจำกัด!',
                'type' => 'percentage',
                'discount_value' => 25,
                'min_purchase' => 300,
                'max_discount' => 1500,
                'start_date' => $now->copy()->subDays(20),
                'end_date' => $now->copy()->subDays(5),
                'usage_limit' => 200,
                'usage_count' => 200, // Sold out
                'stackable' => false,
                'is_active' => false,
                'is_featured' => false,
            ]
        );

        // 8. กำลังจะมา (Scheduled)
        Promotion::updateOrCreate(
            ['name' => 'Valentine Special'],
            [
                'name_th' => 'โปรวาเลนไทน์',
                'code' => 'LOVE14',
                'description' => 'Special Valentine\'s Day promotion - 14% off!',
                'description_th' => 'โปรโมชั่นพิเศษวันวาเลนไทน์ ลด 14%!',
                'type' => 'percentage',
                'discount_value' => 14,
                'min_purchase' => 200,
                'max_discount' => 700,
                'start_date' => $now->copy()->addDays(30),
                'end_date' => $now->copy()->addDays(37),
                'usage_limit' => 500,
                'usage_count' => 0,
                'stackable' => false,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 9. Payday Special
        Promotion::updateOrCreate(
            ['name' => 'Payday Special'],
            [
                'name_th' => 'โปรวันเงินเดือนออก',
                'code' => 'PAYDAY',
                'description' => 'Get extra discount on the last week of month!',
                'description_th' => 'รับส่วนลดพิเศษสัปดาห์สุดท้ายของเดือน!',
                'type' => 'percentage',
                'discount_value' => 8,
                'min_purchase' => 800,
                'max_discount' => 500,
                'start_date' => $now->copy()->startOfMonth()->addDays(24),
                'end_date' => $now->copy()->endOfMonth(),
                'usage_count' => 67,
                'stackable' => true,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        // 10. Senior Citizen Discount (ทุกวันพุธ)
        Promotion::updateOrCreate(
            ['name' => 'Senior Wednesday'],
            [
                'name_th' => 'วันผู้สูงวัย',
                'code' => null,
                'description' => 'Every Wednesday, seniors 60+ get 10% off!',
                'description_th' => 'ทุกวันพุธ ผู้สูงอายุ 60 ปีขึ้นไป รับส่วนลด 10%!',
                'type' => 'percentage',
                'discount_value' => 10,
                'min_purchase' => 0,
                'max_discount' => 500,
                'active_days' => [3], // Wednesday
                'usage_count' => 234,
                'stackable' => true,
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        $this->command->info('✅ Created 10 sample promotions with realistic usage data');
    }
}
