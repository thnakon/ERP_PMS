<?php

namespace Database\Seeders;

use App\Models\MemberTier;
use Illuminate\Database\Seeder;

class MemberTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Bronze',
                'name_th' => 'บรอนซ์',
                'min_spending' => 0,
                'discount_percent' => 0,
                'points_multiplier' => 1,
                'color' => '#CD7F32',
                'icon' => 'ph-medal',
                'benefits' => ['สะสมแต้มทุกการซื้อ', 'รับข่าวสารโปรโมชั่นพิเศษ'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Silver',
                'name_th' => 'ซิลเวอร์',
                'min_spending' => 3000,
                'discount_percent' => 3,
                'points_multiplier' => 1,
                'color' => '#C0C0C0',
                'icon' => 'ph-medal',
                'benefits' => ['ส่วนลด 3% ทุกการซื้อ', 'สะสมแต้ม 1.5 เท่า', 'รับสิทธิ์ก่อนใคร'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Gold',
                'name_th' => 'โกลด์',
                'min_spending' => 10000,
                'discount_percent' => 5,
                'points_multiplier' => 2,
                'color' => '#FFD700',
                'icon' => 'ph-crown',
                'benefits' => ['ส่วนลด 5% ทุกการซื้อ', 'สะสมแต้ม 2 เท่า', 'จัดส่งฟรี', 'ของขวัญวันเกิด'],
                'sort_order' => 3,
            ],
            [
                'name' => 'Platinum',
                'name_th' => 'แพลทินัม',
                'min_spending' => 30000,
                'discount_percent' => 10,
                'points_multiplier' => 3,
                'color' => '#4D4D4D',
                'icon' => 'ph-crown-simple',
                'benefits' => ['ส่วนลด 10% ทุกการซื้อ', 'สะสมแต้ม 3 เท่า', 'จัดส่งฟรี', 'ของขวัญวันเกิดพิเศษ', 'สิทธิ์ใช้บริการล่วงหน้า'],
                'sort_order' => 4,
            ],
            [
                'name' => 'VIP',
                'name_th' => 'วีไอพี',
                'min_spending' => 100000,
                'discount_percent' => 15,
                'points_multiplier' => 5,
                'color' => '#8B0000',
                'icon' => 'ph-star-four',
                'benefits' => ['ส่วนลด 15% ทุกการซื้อ', 'สะสมแต้ม 5 เท่า', 'จัดส่งฟรีด่วนพิเศษ', 'ของขวัญวันเกิดพรีเมียม', 'ช่องทางบริการ VIP', 'ส่วนลดเพิ่มในวันพิเศษ'],
                'sort_order' => 5,
            ],
        ];

        foreach ($tiers as $tier) {
            MemberTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }
    }
}
