<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing categories to avoid duplicates/conflicts during re-seed
        Category::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            // 1. Medications (Pharmaceuticals)
            [
                'group' => 'Medications (Pharmaceuticals)',
                'name' => 'Prescription Medications (Rx)',
                'description' => 'Drugs requiring a prescription from a licensed physician for dispensing. Includes controlled substances and potent drugs like antibiotics, blood pressure medications, and diabetes treatments.',
                'status' => 'Active',
            ],
            [
                'group' => 'Medications (Pharmaceuticals)',
                'name' => 'Over-The-Counter (OTC) Drugs',
                'description' => 'Medications available for purchase without a prescription. Used for common ailments such as pain (analgesics), cold/flu symptoms, and minor digestive issues.',
                'status' => 'Active',
            ],
            [
                'group' => 'Medications (Pharmaceuticals)',
                'name' => 'External/Topical Preparations',
                'description' => 'Products applied to the skin, eyes, or ears. Includes creams, ointments, lotions, antiseptic solutions, and eye/ear drops.',
                'status' => 'Active',
            ],
            [
                'group' => 'Medications (Pharmaceuticals)',
                'name' => 'Controlled Substances/Narcotics',
                'description' => 'Medications with a high potential for abuse or dependence, subject to strict legal regulations regarding storage, dispensing, and record-keeping (e.g., certain pain relievers, sedatives).',
                'status' => 'Active',
            ],

            // 2. Health Supplements & Wellness
            [
                'group' => 'Health Supplements & Wellness',
                'name' => 'Vitamins and Minerals',
                'description' => 'Products intended to supplement the diet and provide essential micronutrients. Examples include Vitamin C, B-Complex, Calcium, and Iron supplements.',
                'status' => 'Active',
            ],
            [
                'group' => 'Health Supplements & Wellness',
                'name' => 'Herbal & Natural Extracts',
                'description' => 'Products derived from plants and natural sources for therapeutic or health-promoting purposes, such as fish oil (Omega-3), curcumin, or traditional herbal remedies.',
                'status' => 'Active',
            ],
            [
                'group' => 'Health Supplements & Wellness',
                'name' => 'Nutritional & Protein Supplements',
                'description' => 'Products like protein powders, meal replacement shakes, and specific formulations for athletes, the elderly, or individuals with dietary restrictions.',
                'status' => 'Active',
            ],

            // 3. First Aid & Wound Care
            [
                'group' => 'First Aid & Wound Care',
                'name' => 'Bandages and Dressings',
                'description' => 'Materials used to cover and protect wounds, including adhesive bandages, gauze, surgical tapes, and medical pads.',
                'status' => 'Active',
            ],
            [
                'group' => 'First Aid & Wound Care',
                'name' => 'Antiseptics and Disinfectants',
                'description' => 'Solutions and sprays used to clean skin, wounds, and surfaces to prevent infection (e.g., alcohol, hydrogen peroxide, povidone-iodine).',
                'status' => 'Active',
            ],
            [
                'group' => 'First Aid & Wound Care',
                'name' => 'First Aid Kits & Supplies',
                'description' => 'Pre-packaged kits and essential tools for immediate treatment of injuries, such as thermometers, splints, and emergency blankets.',
                'status' => 'Active',
            ],

            // 4. Personal Care & Hygiene
            [
                'group' => 'Personal Care & Hygiene',
                'name' => 'Oral Care',
                'description' => 'Products for dental hygiene and health, including toothbrushes, toothpaste, mouthwash, and dental floss.',
                'status' => 'Active',
            ],
            [
                'group' => 'Personal Care & Hygiene',
                'name' => 'Skin and Hair Care (Cosmeceuticals)',
                'description' => 'Items for cleansing and treating the skin and hair, often focusing on medical or sensitive skin conditions (e.g., dermatological soaps, specialized moisturizers, medicated shampoos).',
                'status' => 'Active',
            ],
            [
                'group' => 'Personal Care & Hygiene',
                'name' => 'Feminine Hygiene',
                'description' => 'Products related to women\'s personal care, such as sanitary pads, tampons, and specific feminine washes.',
                'status' => 'Active',
            ],
            [
                'group' => 'Personal Care & Hygiene',
                'name' => 'Baby and Mother Care',
                'description' => 'Items specifically designed for infants and new mothers, including formula, diapers, baby wipes, and nursing supplies.',
                'status' => 'Active',
            ],

            // 5. Medical Devices & Aids
            [
                'group' => 'Medical Devices & Aids',
                'name' => 'Monitoring Devices',
                'description' => 'Equipment used for tracking vital signs and health metrics, such as blood pressure monitors, blood glucose meters, and pulse oximeters.',
                'status' => 'Active',
            ],
            [
                'group' => 'Medical Devices & Aids',
                'name' => 'Mobility Aids',
                'description' => 'Tools to assist with movement and physical support, including canes, crutches, walkers, and support braces.',
                'status' => 'Active',
            ],
            [
                'group' => 'Medical Devices & Aids',
                'name' => 'Inhalation Devices',
                'description' => 'Equipment for respiratory care, such as nebulizers, inhalers, and related accessories.',
                'status' => 'Active',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
