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
                    ['name' => 'Amoxil 500 mg', 'generic_name' => 'Amoxicillin', 'strength' => '500 mg', 'dosage_form' => 'Capsule', 'registration_number' => '1A 10/48', 'cost_price' => 150, 'selling_price' => 200, 'primary_indication' => 'Bacterial Infection', 'regulatory_class' => 'Prescription (Rx)'],
                    ['name' => 'Paracetamol Tabs', 'generic_name' => 'Paracetamol', 'strength' => '500 mg', 'dosage_form' => 'Tablet', 'registration_number' => '2A 3/99', 'cost_price' => 8, 'selling_price' => 15, 'primary_indication' => 'Pain/Fever', 'regulatory_class' => 'OTC'],
                    ['name' => 'Amlopress 5 mg', 'generic_name' => 'Amlodipine', 'strength' => '5 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 50/50', 'cost_price' => 120, 'selling_price' => 180, 'primary_indication' => 'Hypertension', 'regulatory_class' => 'Prescription (Rx)'],
                    ['name' => 'Cough Syrup-DM', 'generic_name' => 'Dextromethorphan', 'strength' => '15 mg/5ml', 'dosage_form' => 'Solution', 'registration_number' => '2A 7/05', 'cost_price' => 45, 'selling_price' => 75, 'primary_indication' => 'Cough Suppressant', 'regulatory_class' => 'OTC'],
                    ['name' => 'Diprobase Cream', 'generic_name' => 'Liquid Paraffin', 'strength' => '15%', 'dosage_form' => 'Cream', 'registration_number' => '1A 80/60', 'cost_price' => 250, 'selling_price' => 350, 'primary_indication' => 'Eczema/Dry Skin', 'regulatory_class' => 'OTC'],
                    ['name' => 'Omeprazole 20mg', 'generic_name' => 'Omeprazole', 'strength' => '20 mg', 'dosage_form' => 'Capsule', 'registration_number' => '1A 15/49', 'cost_price' => 90, 'selling_price' => 140, 'primary_indication' => 'Acid Reflux', 'regulatory_class' => 'Prescription (Rx)'],
                    ['name' => 'Atorva 10 mg', 'generic_name' => 'Atorvastatin', 'strength' => '10 mg', 'dosage_form' => 'Tablet', 'registration_number' => '1A 60/65', 'cost_price' => 300, 'selling_price' => 450, 'primary_indication' => 'Hypercholesterolemia', 'regulatory_class' => 'Prescription (Rx)'],
                    ['name' => 'Lorazepam 1 mg', 'generic_name' => 'Lorazepam', 'strength' => '1 mg', 'dosage_form' => 'Tablet', 'registration_number' => '4A 1/90', 'cost_price' => 180, 'selling_price' => 280, 'primary_indication' => 'Anxiety/Insomnia', 'regulatory_class' => 'Controlled'],
                    ['name' => 'Betadine Solution', 'generic_name' => 'Povidone-Iodine', 'strength' => '10%', 'dosage_form' => 'Solution', 'registration_number' => '2A 1/88', 'cost_price' => 30, 'selling_price' => 55, 'primary_indication' => 'Antiseptic/Wound', 'regulatory_class' => 'OTC'],
                    ['name' => 'Ventolin Inhaler', 'generic_name' => 'Salbutamol', 'strength' => '100 mcg/puff', 'dosage_form' => 'Inhaler', 'registration_number' => '1A 30/70', 'cost_price' => 280, 'selling_price' => 390, 'primary_indication' => 'Asthma', 'regulatory_class' => 'Prescription (Rx)'],
                ]
            ],
            // 2. Health Supplements & Wellness
            [
                'group' => 'Health Supplements & Wellness',
                'items' => [
                    ['name' => 'Vitamin C 1000 mg', 'generic_name' => 'Ascorbic Acid', 'strength' => '1000 mg', 'dosage_form' => 'Tablet', 'registration_number' => '10-1-xxx', 'cost_price' => 150, 'selling_price' => 250, 'primary_indication' => 'Immune Support', 'regulatory_class' => 'Supplement'],
                    ['name' => 'Fish Oil Forte', 'generic_name' => 'Omega-3 (EPA/DHA)', 'strength' => '1000 mg', 'dosage_form' => 'Softgel', 'registration_number' => '13-2-xxx', 'cost_price' => 350, 'selling_price' => 550, 'primary_indication' => 'Heart/Brain Health', 'regulatory_class' => 'Supplement'],
                    ['name' => 'Cal-D Plus', 'generic_name' => 'Calcium + Vit D', 'strength' => '600 mg', 'dosage_form' => 'Tablet', 'registration_number' => '12-1-xxx', 'cost_price' => 200, 'selling_price' => 320, 'primary_indication' => 'Bone Health', 'regulatory_class' => 'Supplement'],
                    ['name' => 'Whey Protein Powder', 'generic_name' => 'Whey Protein Isolate', 'strength' => '25g/scoop', 'dosage_form' => 'Powder', 'registration_number' => '15-0-xxx', 'cost_price' => 800, 'selling_price' => 1200, 'primary_indication' => 'Muscle Building', 'regulatory_class' => 'Supplement'],
                    ['name' => 'Ginseng Extract', 'generic_name' => 'Panax Ginseng', 'strength' => '50 mg', 'dosage_form' => 'Capsule', 'registration_number' => '11-4-xxx', 'cost_price' => 400, 'selling_price' => 650, 'primary_indication' => 'Energy/Vitality', 'regulatory_class' => 'Supplement'],
                    ['name' => 'Probiotic Daily', 'generic_name' => 'Lactobacillus', 'strength' => '10 Billion CFU', 'dosage_form' => 'Capsule', 'registration_number' => '10-3-xxx', 'cost_price' => 450, 'selling_price' => 700, 'primary_indication' => 'Digestive Health', 'regulatory_class' => 'Supplement'],
                ]
            ],
            // 3. First Aid & Wound Care
            [
                'group' => 'First Aid & Wound Care',
                'items' => [
                    ['name' => 'Sterile Gauze Pads', 'generic_name' => 'Cotton/Viscose', 'strength' => 'N/A', 'dosage_form' => 'Pad', 'registration_number' => 'ฆพ.1/2565', 'cost_price' => 50, 'selling_price' => 80, 'primary_indication' => 'Wound Dressing', 'regulatory_class' => 'Medical Device'],
                    ['name' => 'Adhesive Bandages', 'generic_name' => 'Plastic/Fabric', 'strength' => 'N/A', 'dosage_form' => 'Strip', 'registration_number' => 'ฆพ.2/2565', 'cost_price' => 20, 'selling_price' => 35, 'primary_indication' => 'Minor Cut Protection', 'regulatory_class' => 'Medical Device'],
                    ['name' => 'Alcohol Swabs', 'generic_name' => 'Isopropyl Alcohol', 'strength' => '70%', 'dosage_form' => 'Wipe', 'registration_number' => 'ฆพ.3/2565', 'cost_price' => 15, 'selling_price' => 25, 'primary_indication' => 'Skin Disinfection', 'regulatory_class' => 'OTC'],
                    ['name' => 'Elastic Bandage', 'generic_name' => 'Elastic Fiber', 'strength' => 'N/A', 'dosage_form' => 'Roll', 'registration_number' => 'ฆพ.4/2565', 'cost_price' => 90, 'selling_price' => 150, 'primary_indication' => 'Support/Compression', 'regulatory_class' => 'Medical Device'],
                ]
            ],
            // 4. Personal Care & Hygiene
            [
                'group' => 'Personal Care & Hygiene',
                'items' => [
                    ['name' => 'Sensitive Toothpaste', 'generic_name' => 'Fluoride 0.14%', 'strength' => 'N/A', 'dosage_form' => 'Paste', 'registration_number' => '10-1-xxxx', 'cost_price' => 70, 'selling_price' => 110, 'primary_indication' => 'Sensitive Teeth', 'regulatory_class' => 'Cosmetic'],
                    ['name' => 'Dermatology Cleanser', 'generic_name' => 'Salicylic Acid 2%', 'strength' => 'N/A', 'dosage_form' => 'Liquid', 'registration_number' => '10-1-yyyy', 'cost_price' => 250, 'selling_price' => 380, 'primary_indication' => 'Acne/Skin Cleansing', 'regulatory_class' => 'Cosmetic'],
                    ['name' => 'Baby Diapers (L)', 'generic_name' => 'Super Absorbent', 'strength' => 'N/A', 'dosage_form' => 'Diaper', 'registration_number' => 'N/A', 'cost_price' => 350, 'selling_price' => 490, 'primary_indication' => 'Baby Hygiene', 'regulatory_class' => 'Non-Regulated'],
                    ['name' => 'Feminine Pads (Maxi)', 'generic_name' => 'Cotton/Absorbent', 'strength' => 'N/A', 'dosage_form' => 'Pad', 'registration_number' => 'N/A', 'cost_price' => 80, 'selling_price' => 120, 'primary_indication' => 'Feminine Hygiene', 'regulatory_class' => 'Non-Regulated'],
                    ['name' => 'Hand Sanitizer Gel', 'generic_name' => 'Ethyl Alcohol', 'strength' => '70%', 'dosage_form' => 'Gel', 'registration_number' => '10-1-zzzz', 'cost_price' => 45, 'selling_price' => 70, 'primary_indication' => 'Hand Disinfection', 'regulatory_class' => 'Cosmetic'],
                    ['name' => 'Nursing Pads', 'generic_name' => 'Absorbent Material', 'strength' => 'N/A', 'dosage_form' => 'Pad', 'registration_number' => 'N/A', 'cost_price' => 150, 'selling_price' => 230, 'primary_indication' => 'Motherly', 'regulatory_class' => 'Non-Regulated'],
                ]
            ],
            // 5. Medical Devices & Aids
            [
                'group' => 'Medical Devices & Aids',
                'items' => [
                    ['name' => 'Digital BP Monitor', 'generic_name' => 'Electronic Sensor', 'strength' => 'N/A', 'dosage_form' => 'Device', 'registration_number' => 'ผ.1/2560', 'cost_price' => 1200, 'selling_price' => 1800, 'primary_indication' => 'Blood Pressure Monitoring', 'regulatory_class' => 'Medical Device'],
                    ['name' => 'Blood Glucose Test Strips', 'generic_name' => 'Glucose Oxidase', 'strength' => 'N/A', 'dosage_form' => 'Strip', 'registration_number' => 'ผ.2/2560', 'cost_price' => 450, 'selling_price' => 650, 'primary_indication' => 'Blood Sugar Testing', 'regulatory_class' => 'Medical Device'],
                    ['name' => 'Walking Cane', 'generic_name' => 'Aluminium', 'strength' => 'N/A', 'dosage_form' => 'Device', 'registration_number' => 'N/A', 'cost_price' => 300, 'selling_price' => 450, 'primary_indication' => 'Walking Support', 'regulatory_class' => 'Medical Device'],
                    ['name' => 'Infrared Thermometer', 'generic_name' => 'Infrared Sensor', 'strength' => 'N/A', 'dosage_form' => 'Device', 'registration_number' => 'ผ.3/2560', 'cost_price' => 550, 'selling_price' => 850, 'primary_indication' => 'Temperature Measurement', 'regulatory_class' => 'Medical Device'],
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
                    'barcode' => rand(100000000000, 999999999999) // Random barcode
                ]);
            }
        }
    }
}
