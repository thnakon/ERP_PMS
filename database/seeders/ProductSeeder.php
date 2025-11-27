<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have a default unit
        $unit = Unit::firstOrCreate(['name' => 'Unit'], ['abbreviation' => 'u']);

        $products = [
            // 1. Medications (Pharmaceuticals)
            [
                'group' => 'Medications (Pharmaceuticals)',
                'items' => [
                    ['name' => 'Sara (Paracetamol)', 'generic_name' => 'Paracetamol', 'strength' => '500 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 10/48', 'cost_price' => 10, 'selling_price' => 15, 'primary_indication' => 'Pain/Fever', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147102_ Sara (Paracetamol).jpeg'],
                    ['name' => 'Tiffy Dey', 'generic_name' => 'Paracetamol + Chlorpheniramine + Phenylephrine', 'strength' => '500 mg', 'dosage_form' => 'Tablet', 'registration_number' => '2A 3/99', 'cost_price' => 8, 'selling_price' => 12, 'primary_indication' => 'Cold/Flu', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147588_Tiffy Dey.webp'],
                    ['name' => 'Decolgen', 'generic_name' => 'Paracetamol + Chlorpheniramine + Phenylephrine', 'strength' => '500 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 50/50', 'cost_price' => 8, 'selling_price' => 12, 'primary_indication' => 'Cold/Flu', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147567_Decolgen.webp'],
                    ['name' => 'Bactroban Ointment', 'generic_name' => 'Mupirocin', 'strength' => '2%', 'dosage_form' => 'Ointment', 'registration_number' => '1C 15/49', 'cost_price' => 120, 'selling_price' => 180, 'primary_indication' => 'Skin Infection', 'regulatory_class' => 'Prescription (Rx)', 'image_path' => '/images/products/1764147492_Bactroban Ointment.png'],
                    ['name' => 'Flemex Syrup', 'generic_name' => 'Carbocisteine', 'strength' => '250 mg/5ml', 'dosage_form' => 'Syrup', 'registration_number' => '2A 7/05', 'cost_price' => 45, 'selling_price' => 75, 'primary_indication' => 'Cough/Phlegm', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147450_ Flemex Syrup.jpg'],
                    ['name' => 'Air-X', 'generic_name' => 'Simethicone', 'strength' => '80 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 80/60', 'cost_price' => 25, 'selling_price' => 40, 'primary_indication' => 'Gas Relief', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147431_ Air-X.jpeg'],
                    ['name' => 'Gaviscon', 'generic_name' => 'Sodium Alginate', 'strength' => '10 ml', 'dosage_form' => 'Sachet', 'registration_number' => '2C 10/55', 'cost_price' => 20, 'selling_price' => 35, 'primary_indication' => 'Acid Reflux', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147404_Gaviscon.png'],
                    ['name' => 'Nurofen', 'generic_name' => 'Ibuprofen', 'strength' => '400 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 30/70', 'cost_price' => 80, 'selling_price' => 120, 'primary_indication' => 'Pain/Inflammation', 'regulatory_class' => 'Prescription (Rx)', 'image_path' => '/images/products/1764147371_Nurofen.webp'],
                    ['name' => 'Zyrtec', 'generic_name' => 'Cetirizine', 'strength' => '10 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1C 20/45', 'cost_price' => 150, 'selling_price' => 220, 'primary_indication' => 'Allergy', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764147351_Zyrtec.jpg'],
                    ['name' => 'Ventolin Inhaler', 'generic_name' => 'Salbutamol', 'strength' => '100 mcg/puff', 'dosage_form' => 'Inhaler', 'registration_number' => '1C 5/50', 'cost_price' => 280, 'selling_price' => 390, 'primary_indication' => 'Asthma', 'regulatory_class' => 'Prescription (Rx)', 'image_path' => '/images/products/1764147325_9729-800x931.webp'],
                ]
            ],
            // 2. Health Supplements & Wellness
            [
                'group' => 'Health Supplements & Wellness',
                'items' => [
                    ['name' => 'Brand\'s Essence of Chicken', 'generic_name' => 'Chicken Essence', 'strength' => '42 ml', 'dosage_form' => 'Liquid', 'registration_number' => '10-1-xxx', 'cost_price' => 35, 'selling_price' => 45, 'primary_indication' => 'Energy/Brain', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764147276_bec.webp'],
                    ['name' => 'Vistra Gluta Complex', 'generic_name' => 'Glutathione', 'strength' => '800 mg', 'dosage_form' => 'Tablet', 'registration_number' => '13-1-xxx', 'cost_price' => 350, 'selling_price' => 550, 'primary_indication' => 'Skin Whitening', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764147256_Vistra Gluta Complex.webp'],
                    ['name' => 'Blackmores Bio C', 'generic_name' => 'Vitamin C', 'strength' => '1000 mg', 'dosage_form' => 'Tablet', 'registration_number' => '10-3-xxx', 'cost_price' => 400, 'selling_price' => 620, 'primary_indication' => 'Immune Support', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764147201_ Blackmores Bio C.jpg'],
                    ['name' => 'Mega We Care Fish Oil', 'generic_name' => 'Fish Oil', 'strength' => '1000 mg', 'dosage_form' => 'Capsule', 'registration_number' => '11-1-xxx', 'cost_price' => 300, 'selling_price' => 480, 'primary_indication' => 'Heart Health', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764147172_Mega We Care Fish Oil.jpg'],
                    ['name' => 'Handy Herb G-Night', 'generic_name' => 'Chamomile + Gaba', 'strength' => 'N/A', 'dosage_form' => 'Capsule', 'registration_number' => '12-1-xxx', 'cost_price' => 20, 'selling_price' => 25, 'primary_indication' => 'Sleep Aid', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764147128_Handy Herb G-Night.webp'],
                    ['name' => 'C-Vitt Lemon', 'generic_name' => 'Vitamin C Drink', 'strength' => '140 ml', 'dosage_form' => 'Liquid', 'registration_number' => '10-1-yyy', 'cost_price' => 12, 'selling_price' => 16, 'primary_indication' => 'Refreshment', 'regulatory_class' => 'Supplement', 'image_path' => '/images/products/1764146845_16944911064956.jpg'],
                ]
            ],
            // 3. First Aid & Wound Care
            [
                'group' => 'First Aid & Wound Care',
                'items' => [
                    ['name' => 'Tiger Balm Red', 'generic_name' => 'Camphor + Menthol', 'strength' => '19.4 g', 'dosage_form' => 'Ointment', 'registration_number' => 'G 1/25', 'cost_price' => 50, 'selling_price' => 75, 'primary_indication' => 'Muscle Pain', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764146551_TBO-Red.jpg'],
                    ['name' => 'Siang Pure Oil', 'generic_name' => 'Menthol + Peppermint', 'strength' => '3 cc', 'dosage_form' => 'Oil', 'registration_number' => 'G 2/30', 'cost_price' => 15, 'selling_price' => 25, 'primary_indication' => 'Dizziness', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764146570_a529bbc61a0b83cdbd43e7f715e14d66_530x@2x.webp'],
                    ['name' => 'Betadine Solution', 'generic_name' => 'Povidone-Iodine', 'strength' => '15 ml', 'dosage_form' => 'Solution', 'registration_number' => '1A 1/88', 'cost_price' => 30, 'selling_price' => 45, 'primary_indication' => 'Wound Care', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764146596_images.jpeg'],
                    ['name' => 'Tensoplast', 'generic_name' => 'Fabric Plaster', 'strength' => 'N/A', 'dosage_form' => 'Strip', 'registration_number' => '64-1-xxx', 'cost_price' => 5, 'selling_price' => 10, 'primary_indication' => 'Wound Cover', 'regulatory_class' => 'Medical Device', 'image_path' => '/images/products/1764146617_Tensoplast.jpg'],
                    ['name' => 'Counterpain Cool', 'generic_name' => 'Menthol', 'strength' => '60 g', 'dosage_form' => 'Gel', 'registration_number' => '1A 15/50', 'cost_price' => 90, 'selling_price' => 130, 'primary_indication' => 'Muscle Pain', 'regulatory_class' => 'OTC', 'image_path' => '/images/products/1764146657_Counterpain Cool .jpeg'],
                ]
            ],
            // 4. Personal Care & Hygiene
            [
                'group' => 'Personal Care & Hygiene',
                'items' => [
                    ['name' => 'Dentiste Toothpaste', 'generic_name' => 'Nighttime Herb', 'strength' => '100 g', 'dosage_form' => 'Paste', 'registration_number' => '10-1-xxx', 'cost_price' => 120, 'selling_price' => 185, 'primary_indication' => 'Oral Hygiene', 'regulatory_class' => 'Cosmetic', 'image_path' => '/images/products/1764146525_8992772065177_1-20241219233438-.webp'],
                    ['name' => 'Smooth E Cream', 'generic_name' => 'Vitamin E', 'strength' => '40 g', 'dosage_form' => 'Cream', 'registration_number' => '10-1-yyy', 'cost_price' => 180, 'selling_price' => 290, 'primary_indication' => 'Scar Care', 'regulatory_class' => 'Cosmetic', 'image_path' => '/images/products/1764146493_16276157534568_600x600.jpg'],
                    ['name' => 'Soffel Mosquito Repellent', 'generic_name' => 'DEET', 'strength' => '13%', 'dosage_form' => 'Spray', 'registration_number' => 'วอส.1/2560', 'cost_price' => 35, 'selling_price' => 55, 'primary_indication' => 'Insect Repellent', 'regulatory_class' => 'Cosmetic', 'image_path' => '/images/products/1764145287_81FXCR6CZhL._AC_UF894,1000_QL80_.jpg'],
                    ['name' => 'Snake Brand Prickly Heat', 'generic_name' => 'Cooling Powder', 'strength' => '140 g', 'dosage_form' => 'Powder', 'registration_number' => '10-1-zzz', 'cost_price' => 25, 'selling_price' => 35, 'primary_indication' => 'Skin Cooling', 'regulatory_class' => 'Cosmetic', 'image_path' => null],
                    ['name' => 'Hada Labo Lotion', 'generic_name' => 'Hyaluronic Acid', 'strength' => '170 ml', 'dosage_form' => 'Liquid', 'registration_number' => '10-2-xxx', 'cost_price' => 350, 'selling_price' => 520, 'primary_indication' => 'Moisturizer', 'regulatory_class' => 'Cosmetic', 'image_path' => null],
                    ['name' => 'Biore UV Aqua Rich', 'generic_name' => 'Sunscreen', 'strength' => '50 g', 'dosage_form' => 'Cream', 'registration_number' => '10-2-yyy', 'cost_price' => 280, 'selling_price' => 420, 'primary_indication' => 'Sun Protection', 'regulatory_class' => 'Cosmetic', 'image_path' => null],
                ]
            ],
            // 5. Medical Devices & Aids
            [
                'group' => 'Medical Devices & Aids',
                'items' => [
                    ['name' => 'Omron BP Monitor', 'generic_name' => 'Digital Monitor', 'strength' => 'HEM-7120', 'dosage_form' => 'Device', 'registration_number' => '65-2-xxx', 'cost_price' => 1100, 'selling_price' => 1650, 'primary_indication' => 'Blood Pressure', 'regulatory_class' => 'Medical Device', 'image_path' => null],
                    ['name' => 'Accu-Chek Instant', 'generic_name' => 'Glucose Meter', 'strength' => 'N/A', 'dosage_form' => 'Device', 'registration_number' => '65-2-yyy', 'cost_price' => 900, 'selling_price' => 1400, 'primary_indication' => 'Blood Sugar', 'regulatory_class' => 'Medical Device', 'image_path' => '/images/products/1764146930_Accu-Chek Instant.webp'],
                    ['name' => '3M Nexcare Mask', 'generic_name' => 'Carbon Mask', 'strength' => 'N/A', 'dosage_form' => 'Mask', 'registration_number' => 'N/A', 'cost_price' => 15, 'selling_price' => 30, 'primary_indication' => 'Dust Protection', 'regulatory_class' => 'Medical Device', 'image_path' => '/images/products/1764146867_ 3M Nexcare Mask.jpg'],
                    ['name' => 'Terumo Thermometer', 'generic_name' => 'Digital Thermometer', 'strength' => 'N/A', 'dosage_form' => 'Device', 'registration_number' => '64-2-zzz', 'cost_price' => 180, 'selling_price' => 280, 'primary_indication' => 'Fever Check', 'regulatory_class' => 'Medical Device', 'image_path' => null],
                ]
            ]
        ];

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($products as $groupData) {
            // Find a category that matches this group
            $category = Category::where('group', $groupData['group'])->first();

            // If no category found for this group, just grab any category or create one
            if (!$category) {
                $category = Category::firstOrCreate(
                    ['name' => $groupData['group']],
                    ['group' => $groupData['group'], 'description' => 'General category for ' . $groupData['group'], 'status' => 'Active']
                );
            }

            foreach ($groupData['items'] as $item) {
                Product::create([
                    'name' => $item['name'],
                    'generic_name' => $item['generic_name'],
                    'strength' => $item['strength'],
                    'dosage_form' => $item['dosage_form'],
                    'registration_number' => $item['registration_number'],
                    'cost_price' => $item['cost_price'],
                    'selling_price' => $item['selling_price'],
                    'primary_indication' => $item['primary_indication'],
                    'regulatory_class' => $item['regulatory_class'],
                    'category_id' => $category->id,
                    'unit_id' => $unit->id,
                    'min_stock_level' => 10,
                    'is_active' => true,
                    'description' => $item['primary_indication'], // Use indication as description for now
                    'barcode' => rand(100000000000, 999999999999), // Random barcode
                    'image_path' => $item['image_path'] ?? null
                ]);
            }
        }
    }
}
