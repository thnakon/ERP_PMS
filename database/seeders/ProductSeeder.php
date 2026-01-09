<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->pluck('id', 'name')->toArray();

        $products = [
            // Antibiotics (10 products)
            ['sku' => 'AMX500', 'name' => 'Amoxicillin 500mg', 'name_th' => 'อะม็อกซีซิลลิน 500 มก.', 'generic_name' => 'Amoxicillin', 'category' => 'Antibiotics', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GPO Thailand', 'unit_price' => 15.00, 'cost_price' => 8.50, 'stock_qty' => 500, 'min_stock' => 100, 'requires_prescription' => true],
            ['sku' => 'AZI250', 'name' => 'Azithromycin 250mg', 'name_th' => 'อะซิโทรมัยซิน 250 มก.', 'generic_name' => 'Azithromycin', 'category' => 'Antibiotics', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Siam Pharmaceutical', 'unit_price' => 45.00, 'cost_price' => 30.00, 'stock_qty' => 200, 'min_stock' => 50, 'requires_prescription' => true],
            ['sku' => 'CIP500', 'name' => 'Ciprofloxacin 500mg', 'name_th' => 'ไซโปรฟลอกซาซิน 500 มก.', 'generic_name' => 'Ciprofloxacin', 'category' => 'Antibiotics', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GPO Thailand', 'unit_price' => 25.00, 'cost_price' => 15.00, 'stock_qty' => 150, 'min_stock' => 30, 'requires_prescription' => true],
            ['sku' => 'DOX100', 'name' => 'Doxycycline 100mg', 'name_th' => 'ด็อกซีไซคลิน 100 มก.', 'generic_name' => 'Doxycycline', 'category' => 'Antibiotics', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Berlin Pharmaceutical', 'unit_price' => 12.00, 'cost_price' => 6.00, 'stock_qty' => 300, 'min_stock' => 50, 'requires_prescription' => true],
            ['sku' => 'ERY500', 'name' => 'Erythromycin 500mg', 'name_th' => 'อีริโทรมัยซิน 500 มก.', 'generic_name' => 'Erythromycin', 'category' => 'Antibiotics', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GPO Thailand', 'unit_price' => 18.00, 'cost_price' => 10.00, 'stock_qty' => 180, 'min_stock' => 40, 'requires_prescription' => true],

            // Pain Relief (10 products)
            ['sku' => 'PCM500', 'name' => 'Paracetamol 500mg', 'name_th' => 'พาราเซตามอล 500 มก.', 'generic_name' => 'Paracetamol', 'category' => 'Pain Relief', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'GPO Thailand', 'unit_price' => 2.00, 'cost_price' => 0.80, 'stock_qty' => 2000, 'min_stock' => 500, 'requires_prescription' => false],
            ['sku' => 'IBU400', 'name' => 'Ibuprofen 400mg', 'name_th' => 'ไอบูโพรเฟน 400 มก.', 'generic_name' => 'Ibuprofen', 'category' => 'Pain Relief', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Berlin Pharmaceutical', 'unit_price' => 8.00, 'cost_price' => 4.00, 'stock_qty' => 800, 'min_stock' => 200, 'requires_prescription' => false],
            ['sku' => 'DIC50', 'name' => 'Diclofenac 50mg', 'name_th' => 'ไดโคลฟีแนค 50 มก.', 'generic_name' => 'Diclofenac', 'category' => 'Pain Relief', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Novartis', 'unit_price' => 10.00, 'cost_price' => 5.00, 'stock_qty' => 400, 'min_stock' => 100, 'requires_prescription' => false],
            ['sku' => 'MEL7.5', 'name' => 'Meloxicam 7.5mg', 'name_th' => 'เมลอกซิแคม 7.5 มก.', 'generic_name' => 'Meloxicam', 'category' => 'Pain Relief', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Boehringer', 'unit_price' => 15.00, 'cost_price' => 8.00, 'stock_qty' => 250, 'min_stock' => 50, 'requires_prescription' => true],
            ['sku' => 'NAP500', 'name' => 'Naproxen 500mg', 'name_th' => 'นาพรอกเซน 500 มก.', 'generic_name' => 'Naproxen', 'category' => 'Pain Relief', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Bayer', 'unit_price' => 12.00, 'cost_price' => 6.50, 'stock_qty' => 300, 'min_stock' => 60, 'requires_prescription' => false],

            // Vitamins & Supplements (10 products)
            ['sku' => 'VITC1000', 'name' => 'Vitamin C 1000mg', 'name_th' => 'วิตามินซี 1000 มก.', 'generic_name' => 'Ascorbic Acid', 'category' => 'Vitamins & Supplements', 'drug_class' => 'อาหารเสริม', 'manufacturer' => 'Mega Lifesciences', 'unit_price' => 15.00, 'cost_price' => 8.00, 'stock_qty' => 300, 'min_stock' => 50, 'requires_prescription' => false],
            ['sku' => 'VITB', 'name' => 'Vitamin B Complex', 'name_th' => 'วิตามินบีรวม', 'generic_name' => 'Vitamin B1, B6, B12', 'category' => 'Vitamins & Supplements', 'drug_class' => 'อาหารเสริม', 'manufacturer' => 'Mega Lifesciences', 'unit_price' => 8.00, 'cost_price' => 3.50, 'stock_qty' => 400, 'min_stock' => 100, 'requires_prescription' => false],
            ['sku' => 'VITD3', 'name' => 'Vitamin D3 1000IU', 'name_th' => 'วิตามินดี3 1000IU', 'generic_name' => 'Cholecalciferol', 'category' => 'Vitamins & Supplements', 'drug_class' => 'อาหารเสริม', 'manufacturer' => 'Blackmores', 'unit_price' => 12.00, 'cost_price' => 6.00, 'stock_qty' => 250, 'min_stock' => 40, 'requires_prescription' => false],
            ['sku' => 'OMEGA3', 'name' => 'Omega-3 Fish Oil', 'name_th' => 'น้ำมันปลาโอเมก้า 3', 'generic_name' => 'Fish Oil', 'category' => 'Vitamins & Supplements', 'drug_class' => 'อาหารเสริม', 'manufacturer' => 'Blackmores', 'unit_price' => 25.00, 'cost_price' => 15.00, 'stock_qty' => 180, 'min_stock' => 30, 'requires_prescription' => false],
            ['sku' => 'CALC600', 'name' => 'Calcium 600mg + Vitamin D', 'name_th' => 'แคลเซียม 600 มก.', 'generic_name' => 'Calcium + Vit D', 'category' => 'Vitamins & Supplements', 'drug_class' => 'อาหารเสริม', 'manufacturer' => 'Centrum', 'unit_price' => 18.00, 'cost_price' => 10.00, 'stock_qty' => 200, 'min_stock' => 40, 'requires_prescription' => false],

            // Cold & Flu (5 products)
            ['sku' => 'TIFFY', 'name' => 'Tiffy (Paracetamol + CPM)', 'name_th' => 'ทิฟฟี่', 'generic_name' => 'Paracetamol + Chlorpheniramine', 'category' => 'Cold & Flu', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'Thai Nakorn Patana', 'unit_price' => 3.00, 'cost_price' => 1.20, 'stock_qty' => 1500, 'min_stock' => 300, 'requires_prescription' => false],
            ['sku' => 'DEXA', 'name' => 'Dextromethorphan Syrup', 'name_th' => 'ยาแก้ไอ เด็กซ์โทรเมทอร์แฟน', 'generic_name' => 'Dextromethorphan', 'category' => 'Cold & Flu', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'Osoth Inter', 'unit_price' => 45.00, 'cost_price' => 25.00, 'stock_qty' => 200, 'min_stock' => 40, 'requires_prescription' => false],
            ['sku' => 'PSEU60', 'name' => 'Pseudoephedrine 60mg', 'name_th' => 'ซูโดเอฟีดรีน 60 มก.', 'generic_name' => 'Pseudoephedrine', 'category' => 'Cold & Flu', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GPO Thailand', 'unit_price' => 5.00, 'cost_price' => 2.50, 'stock_qty' => 600, 'min_stock' => 100, 'requires_prescription' => false],
            ['sku' => 'LORAT', 'name' => 'Loratadine 10mg', 'name_th' => 'ลอราทาดีน 10 มก.', 'generic_name' => 'Loratadine', 'category' => 'Cold & Flu', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Schering-Plough', 'unit_price' => 8.00, 'cost_price' => 4.00, 'stock_qty' => 400, 'min_stock' => 80, 'requires_prescription' => false],
            ['sku' => 'CETIR', 'name' => 'Cetirizine 10mg', 'name_th' => 'เซทิริซีน 10 มก.', 'generic_name' => 'Cetirizine', 'category' => 'Cold & Flu', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'UCB Pharma', 'unit_price' => 6.00, 'cost_price' => 3.00, 'stock_qty' => 500, 'min_stock' => 100, 'requires_prescription' => false],

            // Diabetes Care (5 products)
            ['sku' => 'MET500', 'name' => 'Metformin 500mg', 'name_th' => 'เมทฟอร์มิน 500 มก.', 'generic_name' => 'Metformin HCl', 'category' => 'Diabetes Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GPO Thailand', 'unit_price' => 3.00, 'cost_price' => 1.00, 'stock_qty' => 1000, 'min_stock' => 200, 'requires_prescription' => true],
            ['sku' => 'GLIP5', 'name' => 'Glipizide 5mg', 'name_th' => 'กลิพิไซด์ 5 มก.', 'generic_name' => 'Glipizide', 'category' => 'Diabetes Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Pfizer', 'unit_price' => 8.00, 'cost_price' => 4.00, 'stock_qty' => 300, 'min_stock' => 50, 'requires_prescription' => true],
            ['sku' => 'GLIB5', 'name' => 'Glibenclamide 5mg', 'name_th' => 'ไกลเบนคลาไมด์ 5 มก.', 'generic_name' => 'Glibenclamide', 'category' => 'Diabetes Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Sanofi', 'unit_price' => 5.00, 'cost_price' => 2.50, 'stock_qty' => 400, 'min_stock' => 80, 'requires_prescription' => true],
            ['sku' => 'SITA100', 'name' => 'Sitagliptin 100mg', 'name_th' => 'ซิทาคลิปติน 100 มก.', 'generic_name' => 'Sitagliptin', 'category' => 'Diabetes Care', 'drug_class' => 'ยาควบคุมพิเศษ', 'manufacturer' => 'MSD', 'unit_price' => 85.00, 'cost_price' => 60.00, 'stock_qty' => 100, 'min_stock' => 20, 'requires_prescription' => true],
            ['sku' => 'EMPAG10', 'name' => 'Empagliflozin 10mg', 'name_th' => 'เอ็มพากลิโฟลซิน 10 มก.', 'generic_name' => 'Empagliflozin', 'category' => 'Diabetes Care', 'drug_class' => 'ยาควบคุมพิเศษ', 'manufacturer' => 'Boehringer', 'unit_price' => 120.00, 'cost_price' => 85.00, 'stock_qty' => 80, 'min_stock' => 15, 'requires_prescription' => true],

            // Skin Care (5 products)
            ['sku' => 'BETA01', 'name' => 'Betamethasone Cream 0.1%', 'name_th' => 'เบต้าเมทาโซน ครีม', 'generic_name' => 'Betamethasone Valerate', 'category' => 'Skin Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'T.O. Chemicals', 'unit_price' => 35.00, 'cost_price' => 18.00, 'stock_qty' => 150, 'min_stock' => 30, 'requires_prescription' => false],
            ['sku' => 'CLOT01', 'name' => 'Clotrimazole Cream 1%', 'name_th' => 'โคลไตรมาโซล ครีม', 'generic_name' => 'Clotrimazole', 'category' => 'Skin Care', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'T.O. Chemicals', 'unit_price' => 25.00, 'cost_price' => 12.00, 'stock_qty' => 100, 'min_stock' => 20, 'requires_prescription' => false],
            ['sku' => 'FUSI2', 'name' => 'Fusidic Acid Cream 2%', 'name_th' => 'ฟิวซิดิกแอซิด ครีม', 'generic_name' => 'Fusidic Acid', 'category' => 'Skin Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Leo Pharma', 'unit_price' => 120.00, 'cost_price' => 80.00, 'stock_qty' => 60, 'min_stock' => 15, 'requires_prescription' => false],
            ['sku' => 'MUPIR', 'name' => 'Mupirocin Ointment 2%', 'name_th' => 'มูพิโรซิน ออยเมนต์', 'generic_name' => 'Mupirocin', 'category' => 'Skin Care', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'GSK', 'unit_price' => 150.00, 'cost_price' => 100.00, 'stock_qty' => 40, 'min_stock' => 10, 'requires_prescription' => true],
            ['sku' => 'HYDRO1', 'name' => 'Hydrocortisone Cream 1%', 'name_th' => 'ไฮโดรคอร์ติโซน ครีม', 'generic_name' => 'Hydrocortisone', 'category' => 'Skin Care', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'T.O. Chemicals', 'unit_price' => 28.00, 'cost_price' => 14.00, 'stock_qty' => 120, 'min_stock' => 25, 'requires_prescription' => false],

            // Cardiovascular (5 products)
            ['sku' => 'AMLO5', 'name' => 'Amlodipine 5mg', 'name_th' => 'แอมโลดิปีน 5 มก.', 'generic_name' => 'Amlodipine', 'category' => 'Cardiovascular', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Pfizer', 'unit_price' => 6.00, 'cost_price' => 3.00, 'stock_qty' => 800, 'min_stock' => 150, 'requires_prescription' => true],
            ['sku' => 'ENAL10', 'name' => 'Enalapril 10mg', 'name_th' => 'เอนาลาพริล 10 มก.', 'generic_name' => 'Enalapril', 'category' => 'Cardiovascular', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'MSD', 'unit_price' => 5.00, 'cost_price' => 2.50, 'stock_qty' => 600, 'min_stock' => 100, 'requires_prescription' => true],
            ['sku' => 'LOSAR50', 'name' => 'Losartan 50mg', 'name_th' => 'โลซาร์แทน 50 มก.', 'generic_name' => 'Losartan', 'category' => 'Cardiovascular', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'MSD', 'unit_price' => 8.00, 'cost_price' => 4.00, 'stock_qty' => 500, 'min_stock' => 100, 'requires_prescription' => true],
            ['sku' => 'ATOR20', 'name' => 'Atorvastatin 20mg', 'name_th' => 'อะทอร์วาสแตติน 20 มก.', 'generic_name' => 'Atorvastatin', 'category' => 'Cardiovascular', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Pfizer', 'unit_price' => 12.00, 'cost_price' => 6.00, 'stock_qty' => 400, 'min_stock' => 80, 'requires_prescription' => true],
            ['sku' => 'SIMVA20', 'name' => 'Simvastatin 20mg', 'name_th' => 'ซิมวาสแตติน 20 มก.', 'generic_name' => 'Simvastatin', 'category' => 'Cardiovascular', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'MSD', 'unit_price' => 10.00, 'cost_price' => 5.00, 'stock_qty' => 450, 'min_stock' => 90, 'requires_prescription' => true],

            // Gastrointestinal (5 products)
            ['sku' => 'OMEP20', 'name' => 'Omeprazole 20mg', 'name_th' => 'โอเมพราโซล 20 มก.', 'generic_name' => 'Omeprazole', 'category' => 'Gastrointestinal', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'AstraZeneca', 'unit_price' => 8.00, 'cost_price' => 4.00, 'stock_qty' => 600, 'min_stock' => 120, 'requires_prescription' => false],
            ['sku' => 'LANS30', 'name' => 'Lansoprazole 30mg', 'name_th' => 'แลนโซพราโซล 30 มก.', 'generic_name' => 'Lansoprazole', 'category' => 'Gastrointestinal', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Takeda', 'unit_price' => 12.00, 'cost_price' => 6.00, 'stock_qty' => 400, 'min_stock' => 80, 'requires_prescription' => false],
            ['sku' => 'DOMP10', 'name' => 'Domperidone 10mg', 'name_th' => 'ดอมเพอริโดน 10 มก.', 'generic_name' => 'Domperidone', 'category' => 'Gastrointestinal', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Janssen', 'unit_price' => 5.00, 'cost_price' => 2.50, 'stock_qty' => 500, 'min_stock' => 100, 'requires_prescription' => false],
            ['sku' => 'METO10', 'name' => 'Metoclopramide 10mg', 'name_th' => 'เมโทโคลพราไมด์ 10 มก.', 'generic_name' => 'Metoclopramide', 'category' => 'Gastrointestinal', 'drug_class' => 'ยาอันตราย', 'manufacturer' => 'Sanofi', 'unit_price' => 4.00, 'cost_price' => 2.00, 'stock_qty' => 400, 'min_stock' => 80, 'requires_prescription' => false],
            ['sku' => 'LOPER', 'name' => 'Loperamide 2mg', 'name_th' => 'โลเพอราไมด์ 2 มก.', 'generic_name' => 'Loperamide', 'category' => 'Gastrointestinal', 'drug_class' => 'ยาสามัญประจำบ้าน', 'manufacturer' => 'Janssen', 'unit_price' => 6.00, 'cost_price' => 3.00, 'stock_qty' => 350, 'min_stock' => 70, 'requires_prescription' => false],
        ];

        foreach ($products as $productData) {
            $categoryId = $categories[$productData['category']] ?? null;
            unset($productData['category']);

            $productData['category_id'] = $categoryId;
            $productData['barcode'] = '885' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $productData['member_price'] = $productData['unit_price'] * 0.9;
            $productData['unit'] = 'tablet';
            $productData['base_unit'] = 'tablet';
            $productData['sell_unit'] = 'box';
            $productData['conversion_factor'] = rand(10, 100);
            $productData['reorder_point'] = $productData['min_stock'] * 1.5;
            $productData['max_stock'] = $productData['min_stock'] * 10;
            $productData['location'] = chr(65 + rand(0, 5)) . rand(1, 5) . '-' . str_pad(rand(1, 20), 2, '0', STR_PAD_LEFT);
            $productData['vat_applicable'] = $productData['drug_class'] === 'อาหารเสริม';

            Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('Created ' . count($products) . ' pharmacy products!');
    }
}
