<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Main Categories (Parent = null)
        $mainCategories = [
            ['name' => 'Antibiotics', 'name_th' => 'ยาปฏิชีวนะ', 'color_code' => '#EF4444', 'description' => 'Medicines that fight bacterial infections.'],
            ['name' => 'Pain Relief', 'name_th' => 'ยาแก้ปวด', 'color_code' => '#F97316', 'description' => 'Analgesics and anti-inflammatory medicines.'],
            ['name' => 'Vitamins & Supplements', 'name_th' => 'วิตามินและอาหารเสริม', 'color_code' => '#22C55E', 'description' => 'Nutritional supplements for health.'],
            ['name' => 'Cold & Flu', 'name_th' => 'ยาแก้หวัด', 'color_code' => '#3B82F6', 'description' => 'Medications for cold and flu symptoms.'],
            ['name' => 'Diabetes Care', 'name_th' => 'ยาเบาหวาน', 'color_code' => '#8B5CF6', 'description' => 'Diabetes management medications.'],
            ['name' => 'Skin Care', 'name_th' => 'ยาผิวหนัง', 'color_code' => '#EC4899', 'description' => 'Dermatological products and creams.'],
            ['name' => 'Cardiovascular', 'name_th' => 'ยาโรคหัวใจ', 'color_code' => '#DC2626', 'description' => 'Heart and blood pressure medications.'],
            ['name' => 'Gastrointestinal', 'name_th' => 'ยาระบบทางเดินอาหาร', 'color_code' => '#F59E0B', 'description' => 'Digestive system medications.'],
            ['name' => 'Respiratory', 'name_th' => 'ยาระบบทางเดินหายใจ', 'color_code' => '#06B6D4', 'description' => 'Respiratory system medications.'],
            ['name' => 'Eye & Ear Care', 'name_th' => 'ยาตาและหู', 'color_code' => '#6366F1', 'description' => 'Ophthalmic and otic preparations.'],
            ['name' => 'First Aid', 'name_th' => 'ปฐมพยาบาล', 'color_code' => '#10B981', 'description' => 'First aid and wound care supplies.'],
            ['name' => 'Medical Devices', 'name_th' => 'เครื่องมือแพทย์', 'color_code' => '#64748B', 'description' => 'Medical equipment and devices.'],
            ['name' => 'Personal Care', 'name_th' => 'ของใช้ส่วนตัว', 'color_code' => '#A855F7', 'description' => 'Personal hygiene and care products.'],
            ['name' => 'Baby Care', 'name_th' => 'สินค้าเด็ก', 'color_code' => '#F472B6', 'description' => 'Products for baby care and nutrition.'],
            ['name' => 'Herbal Medicine', 'name_th' => 'ยาสมุนไพร', 'color_code' => '#84CC16', 'description' => 'Traditional herbal remedies.'],
        ];

        $createdMain = [];
        foreach ($mainCategories as $i => $category) {
            $createdMain[$category['name']] = Category::create([
                'name' => $category['name'],
                'name_th' => $category['name_th'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color_code' => $category['color_code'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        // Sub-Categories
        $subCategories = [
            ['name' => 'Oral Antibiotics', 'name_th' => 'ยาปฏิชีวนะชนิดรับประทาน', 'parent' => 'Antibiotics', 'color_code' => '#FCA5A5'],
            ['name' => 'Topical Antibiotics', 'name_th' => 'ยาปฏิชีวนะชนิดทา', 'parent' => 'Antibiotics', 'color_code' => '#FECACA'],
            ['name' => 'NSAIDs', 'name_th' => 'ยาต้านอักเสบ', 'parent' => 'Pain Relief', 'color_code' => '#FDBA74'],
            ['name' => 'Muscle Relaxants', 'name_th' => 'ยาคลายกล้ามเนื้อ', 'parent' => 'Pain Relief', 'color_code' => '#FED7AA'],
            ['name' => 'Antacids', 'name_th' => 'ยาลดกรด', 'parent' => 'Gastrointestinal', 'color_code' => '#FDE68A'],
        ];

        foreach ($subCategories as $i => $category) {
            Category::create([
                'name' => $category['name'],
                'name_th' => $category['name_th'],
                'slug' => Str::slug($category['name']),
                'parent_id' => $createdMain[$category['parent']]->id,
                'color_code' => $category['color_code'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('Created ' . (count($mainCategories) + count($subCategories)) . ' categories!');
    }
}
