<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ThaiProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->pluck('id', 'name')->toArray();

        $products = [
            // ยาแก้ปวด (20)
            ['sku' => 'TF001', 'name' => 'Sara 500mg', 'name_th' => 'ซาร่า', 'category' => 'Pain Relief', 'unit_price' => 2.50, 'cost_price' => 1.00],
            ['sku' => 'TF002', 'name' => 'Tylenol 500mg', 'name_th' => 'ไทลินอล', 'category' => 'Pain Relief', 'unit_price' => 5.00, 'cost_price' => 2.50],
            ['sku' => 'TF003', 'name' => 'Ponstan 500mg', 'name_th' => 'พอนสแตน', 'category' => 'Pain Relief', 'unit_price' => 8.00, 'cost_price' => 4.00],
            ['sku' => 'TF004', 'name' => 'Arcoxia 90mg', 'name_th' => 'อาร์ค็อกเซีย', 'category' => 'Pain Relief', 'unit_price' => 45.00, 'cost_price' => 30.00],
            ['sku' => 'TF005', 'name' => 'Celebrex 200mg', 'name_th' => 'เซเลเบร็กซ์', 'category' => 'Pain Relief', 'unit_price' => 55.00, 'cost_price' => 35.00],
            ['sku' => 'TF006', 'name' => 'Voltaren Gel 50g', 'name_th' => 'โวลทาเรน เจล', 'category' => 'Pain Relief', 'unit_price' => 180.00, 'cost_price' => 120.00],
            ['sku' => 'TF007', 'name' => 'Counterpain 60g', 'name_th' => 'เคาน์เตอร์เพน', 'category' => 'Pain Relief', 'unit_price' => 95.00, 'cost_price' => 60.00],
            ['sku' => 'TF008', 'name' => 'Salonpas Patch 5s', 'name_th' => 'ซาลอนพาส', 'category' => 'Pain Relief', 'unit_price' => 65.00, 'cost_price' => 40.00],
            ['sku' => 'TF009', 'name' => 'Neurogesic 300mg', 'name_th' => 'นิวโรเจสิค', 'category' => 'Pain Relief', 'unit_price' => 12.00, 'cost_price' => 6.00],
            ['sku' => 'TF010', 'name' => 'Tramadol 50mg', 'name_th' => 'ทรามาดอล', 'category' => 'Pain Relief', 'unit_price' => 8.00, 'cost_price' => 4.00],

            // ยาหวัดภูมิแพ้ (20)
            ['sku' => 'TF011', 'name' => 'Tiffy Dey', 'name_th' => 'ทิฟฟี่ เดย์', 'category' => 'Cold & Flu', 'unit_price' => 5.00, 'cost_price' => 2.00],
            ['sku' => 'TF012', 'name' => 'Decolgen', 'name_th' => 'ดีคอลเจน', 'category' => 'Cold & Flu', 'unit_price' => 6.00, 'cost_price' => 3.00],
            ['sku' => 'TF013', 'name' => 'Zyrlex 10mg', 'name_th' => 'เซอร์เลค', 'category' => 'Cold & Flu', 'unit_price' => 15.00, 'cost_price' => 8.00],
            ['sku' => 'TF014', 'name' => 'Clarityne 10mg', 'name_th' => 'คลาริทีน', 'category' => 'Cold & Flu', 'unit_price' => 18.00, 'cost_price' => 10.00],
            ['sku' => 'TF015', 'name' => 'Aerius 5mg', 'name_th' => 'แอเรียส', 'category' => 'Cold & Flu', 'unit_price' => 25.00, 'cost_price' => 15.00],
            ['sku' => 'TF016', 'name' => 'Telfast 180mg', 'name_th' => 'เทลฟาสต์', 'category' => 'Cold & Flu', 'unit_price' => 35.00, 'cost_price' => 22.00],
            ['sku' => 'TF017', 'name' => 'Prospan Syrup 100ml', 'name_th' => 'โปรสแปน', 'category' => 'Cold & Flu', 'unit_price' => 220.00, 'cost_price' => 150.00],
            ['sku' => 'TF018', 'name' => 'Bisolvon 8mg', 'name_th' => 'บิโซลวอน', 'category' => 'Cold & Flu', 'unit_price' => 8.00, 'cost_price' => 4.00],
            ['sku' => 'TF019', 'name' => 'ACC 200mg', 'name_th' => 'เอซีซี', 'category' => 'Cold & Flu', 'unit_price' => 12.00, 'cost_price' => 6.00],
            ['sku' => 'TF020', 'name' => 'Afrin Spray 15ml', 'name_th' => 'แอฟริน สเปรย์', 'category' => 'Cold & Flu', 'unit_price' => 150.00, 'cost_price' => 90.00],

            // ยาโรคกระเพาะ (15)
            ['sku' => 'TF021', 'name' => 'Antacil Gel 240ml', 'name_th' => 'แอนตาซิล', 'category' => 'Gastrointestinal', 'unit_price' => 85.00, 'cost_price' => 50.00],
            ['sku' => 'TF022', 'name' => 'Gaviscon 150ml', 'name_th' => 'แกวิสคอน', 'category' => 'Gastrointestinal', 'unit_price' => 195.00, 'cost_price' => 130.00],
            ['sku' => 'TF023', 'name' => 'Nexium 20mg', 'name_th' => 'เน็กเซียม', 'category' => 'Gastrointestinal', 'unit_price' => 45.00, 'cost_price' => 28.00],
            ['sku' => 'TF024', 'name' => 'Losec 20mg', 'name_th' => 'โลเซค', 'category' => 'Gastrointestinal', 'unit_price' => 38.00, 'cost_price' => 22.00],
            ['sku' => 'TF025', 'name' => 'Motilium 10mg', 'name_th' => 'โมทิเลียม', 'category' => 'Gastrointestinal', 'unit_price' => 8.00, 'cost_price' => 4.00],
            ['sku' => 'TF026', 'name' => 'Smecta 3g', 'name_th' => 'สเม็กต้า', 'category' => 'Gastrointestinal', 'unit_price' => 18.00, 'cost_price' => 10.00],
            ['sku' => 'TF027', 'name' => 'Imodium 2mg', 'name_th' => 'อิโมเดียม', 'category' => 'Gastrointestinal', 'unit_price' => 12.00, 'cost_price' => 6.00],
            ['sku' => 'TF028', 'name' => 'Normagut 250mg', 'name_th' => 'นอร์มากัท', 'category' => 'Gastrointestinal', 'unit_price' => 22.00, 'cost_price' => 12.00],
            ['sku' => 'TF029', 'name' => 'Buscopan 10mg', 'name_th' => 'บัสโคพาน', 'category' => 'Gastrointestinal', 'unit_price' => 15.00, 'cost_price' => 8.00],
            ['sku' => 'TF030', 'name' => 'Dulcolax 5mg', 'name_th' => 'ดัลโคแล็กซ์', 'category' => 'Gastrointestinal', 'unit_price' => 6.00, 'cost_price' => 3.00],

            // วิตามิน (20)
            ['sku' => 'TF031', 'name' => 'Centrum Silver 30s', 'name_th' => 'เซนทรัม ซิลเวอร์', 'category' => 'Vitamins & Supplements', 'unit_price' => 550.00, 'cost_price' => 380.00],
            ['sku' => 'TF032', 'name' => 'Blackmores Fish Oil 60s', 'name_th' => 'แบลคมอร์ส ฟิชออยล์', 'category' => 'Vitamins & Supplements', 'unit_price' => 650.00, 'cost_price' => 450.00],
            ['sku' => 'TF033', 'name' => 'Nat C 1000mg 30s', 'name_th' => 'แนท ซี', 'category' => 'Vitamins & Supplements', 'unit_price' => 320.00, 'cost_price' => 200.00],
            ['sku' => 'TF034', 'name' => 'DHC Vitamin C 60s', 'name_th' => 'DHC วิตามินซี', 'category' => 'Vitamins & Supplements', 'unit_price' => 290.00, 'cost_price' => 180.00],
            ['sku' => 'TF035', 'name' => 'Mega We Care Zinc 30s', 'name_th' => 'เมก้า วีแคร์ ซิงค์', 'category' => 'Vitamins & Supplements', 'unit_price' => 180.00, 'cost_price' => 110.00],
            ['sku' => 'TF036', 'name' => 'Vistra B Complex 30s', 'name_th' => 'วิสทร้า บีคอมเพล็กซ์', 'category' => 'Vitamins & Supplements', 'unit_price' => 250.00, 'cost_price' => 160.00],
            ['sku' => 'TF037', 'name' => 'Amsel Collagen 10s', 'name_th' => 'แอมเซล คอลลาเจน', 'category' => 'Vitamins & Supplements', 'unit_price' => 450.00, 'cost_price' => 300.00],
            ['sku' => 'TF038', 'name' => 'Probac 7 Sachet 10s', 'name_th' => 'โปรแบค 7', 'category' => 'Vitamins & Supplements', 'unit_price' => 280.00, 'cost_price' => 180.00],
            ['sku' => 'TF039', 'name' => 'Enervon-C 30s', 'name_th' => 'เอเนอร์วอน-ซี', 'category' => 'Vitamins & Supplements', 'unit_price' => 195.00, 'cost_price' => 120.00],
            ['sku' => 'TF040', 'name' => 'Caltrate Plus 30s', 'name_th' => 'แคลเทรต พลัส', 'category' => 'Vitamins & Supplements', 'unit_price' => 320.00, 'cost_price' => 200.00],

            // ยาผิวหนัง (15)
            ['sku' => 'TF041', 'name' => 'Daktarin Cream 15g', 'name_th' => 'ดักทาริน ครีม', 'category' => 'Skin Care', 'unit_price' => 95.00, 'cost_price' => 60.00],
            ['sku' => 'TF042', 'name' => 'Canesten Cream 20g', 'name_th' => 'คาเนสเทน ครีม', 'category' => 'Skin Care', 'unit_price' => 180.00, 'cost_price' => 120.00],
            ['sku' => 'TF043', 'name' => 'Fucidin Cream 15g', 'name_th' => 'ฟิวซิดิน ครีม', 'category' => 'Skin Care', 'unit_price' => 250.00, 'cost_price' => 170.00],
            ['sku' => 'TF044', 'name' => 'Bepanthen 30g', 'name_th' => 'บีแพนเธน', 'category' => 'Skin Care', 'unit_price' => 290.00, 'cost_price' => 190.00],
            ['sku' => 'TF045', 'name' => 'Sudocrem 60g', 'name_th' => 'ซูโดครีม', 'category' => 'Skin Care', 'unit_price' => 220.00, 'cost_price' => 140.00],
            ['sku' => 'TF046', 'name' => 'Hirudoid Cream 20g', 'name_th' => 'ฮีรูดอยด์ ครีม', 'category' => 'Skin Care', 'unit_price' => 180.00, 'cost_price' => 110.00],
            ['sku' => 'TF047', 'name' => 'Benzac AC 5% 60g', 'name_th' => 'เบนแซค', 'category' => 'Skin Care', 'unit_price' => 350.00, 'cost_price' => 230.00],
            ['sku' => 'TF048', 'name' => 'Eucerin Lotion 250ml', 'name_th' => 'ยูเซอริน โลชั่น', 'category' => 'Skin Care', 'unit_price' => 550.00, 'cost_price' => 380.00],
            ['sku' => 'TF049', 'name' => 'Physiogel 200ml', 'name_th' => 'ฟิสิโอเจล', 'category' => 'Skin Care', 'unit_price' => 520.00, 'cost_price' => 350.00],
            ['sku' => 'TF050', 'name' => 'Lactacyd 150ml', 'name_th' => 'แลคตาซิด', 'category' => 'Skin Care', 'unit_price' => 180.00, 'cost_price' => 110.00],
        ];

        // Add more products (51-100)
        $moreProducts = [
            // ยาตา/หู (10)
            ['sku' => 'TF051', 'name' => 'Visine Eye Drops 15ml', 'name_th' => 'วิซีน', 'category' => 'Antibiotics', 'unit_price' => 120.00, 'cost_price' => 75.00],
            ['sku' => 'TF052', 'name' => 'Systane Ultra 10ml', 'name_th' => 'ซิสเทน อัลตร้า', 'category' => 'Antibiotics', 'unit_price' => 280.00, 'cost_price' => 180.00],
            ['sku' => 'TF053', 'name' => 'Tobrex Eye Drop 5ml', 'name_th' => 'โทเบร็กซ์', 'category' => 'Antibiotics', 'unit_price' => 185.00, 'cost_price' => 120.00],
            ['sku' => 'TF054', 'name' => 'Refresh Tears 15ml', 'name_th' => 'รีเฟรช เทียร์ส', 'category' => 'Antibiotics', 'unit_price' => 195.00, 'cost_price' => 130.00],
            ['sku' => 'TF055', 'name' => 'Ciprodex Otic 7.5ml', 'name_th' => 'ไซโปรเด็กซ์', 'category' => 'Antibiotics', 'unit_price' => 320.00, 'cost_price' => 220.00],

            // ยาเบาหวาน (10)
            ['sku' => 'TF056', 'name' => 'Glucophage 500mg', 'name_th' => 'กลูโคฟาจ', 'category' => 'Diabetes Care', 'unit_price' => 5.00, 'cost_price' => 2.50],
            ['sku' => 'TF057', 'name' => 'Glucophage XR 750mg', 'name_th' => 'กลูโคฟาจ XR', 'category' => 'Diabetes Care', 'unit_price' => 12.00, 'cost_price' => 6.00],
            ['sku' => 'TF058', 'name' => 'Amaryl 2mg', 'name_th' => 'อมาริล', 'category' => 'Diabetes Care', 'unit_price' => 18.00, 'cost_price' => 10.00],
            ['sku' => 'TF059', 'name' => 'Januvia 100mg', 'name_th' => 'จานูเวีย', 'category' => 'Diabetes Care', 'unit_price' => 95.00, 'cost_price' => 65.00],
            ['sku' => 'TF060', 'name' => 'Forxiga 10mg', 'name_th' => 'ฟอร์ซิก้า', 'category' => 'Diabetes Care', 'unit_price' => 85.00, 'cost_price' => 55.00],

            // ยาความดัน/หัวใจ (15)
            ['sku' => 'TF061', 'name' => 'Norvasc 5mg', 'name_th' => 'นอร์แวสค์', 'category' => 'Cardiovascular', 'unit_price' => 25.00, 'cost_price' => 15.00],
            ['sku' => 'TF062', 'name' => 'Concor 5mg', 'name_th' => 'คอนคอร์', 'category' => 'Cardiovascular', 'unit_price' => 22.00, 'cost_price' => 12.00],
            ['sku' => 'TF063', 'name' => 'Cozaar 50mg', 'name_th' => 'โคซาร์', 'category' => 'Cardiovascular', 'unit_price' => 28.00, 'cost_price' => 16.00],
            ['sku' => 'TF064', 'name' => 'Micardis 40mg', 'name_th' => 'ไมคาร์ดิส', 'category' => 'Cardiovascular', 'unit_price' => 35.00, 'cost_price' => 22.00],
            ['sku' => 'TF065', 'name' => 'Lipitor 20mg', 'name_th' => 'ลิพิทอร์', 'category' => 'Cardiovascular', 'unit_price' => 45.00, 'cost_price' => 28.00],
            ['sku' => 'TF066', 'name' => 'Crestor 10mg', 'name_th' => 'เครสตอร์', 'category' => 'Cardiovascular', 'unit_price' => 55.00, 'cost_price' => 35.00],
            ['sku' => 'TF067', 'name' => 'Plavix 75mg', 'name_th' => 'พลาวิกซ์', 'category' => 'Cardiovascular', 'unit_price' => 65.00, 'cost_price' => 42.00],
            ['sku' => 'TF068', 'name' => 'Aspirin Cardio 100mg', 'name_th' => 'แอสไพริน คาร์ดิโอ', 'category' => 'Cardiovascular', 'unit_price' => 8.00, 'cost_price' => 4.00],
            ['sku' => 'TF069', 'name' => 'Warfarin 5mg', 'name_th' => 'วาร์ฟาริน', 'category' => 'Cardiovascular', 'unit_price' => 3.00, 'cost_price' => 1.50],
            ['sku' => 'TF070', 'name' => 'Xarelto 20mg', 'name_th' => 'ซาเรลโต้', 'category' => 'Cardiovascular', 'unit_price' => 150.00, 'cost_price' => 100.00],

            // ยาปฏิชีวนะ (15)
            ['sku' => 'TF071', 'name' => 'Augmentin 625mg', 'name_th' => 'ออกเมนติน', 'category' => 'Antibiotics', 'unit_price' => 45.00, 'cost_price' => 28.00],
            ['sku' => 'TF072', 'name' => 'Zithromax 250mg', 'name_th' => 'ซิโธรแม็กซ์', 'category' => 'Antibiotics', 'unit_price' => 85.00, 'cost_price' => 55.00],
            ['sku' => 'TF073', 'name' => 'Cefixime 400mg', 'name_th' => 'เซฟิซิม', 'category' => 'Antibiotics', 'unit_price' => 55.00, 'cost_price' => 35.00],
            ['sku' => 'TF074', 'name' => 'Norfloxacin 400mg', 'name_th' => 'นอร์ฟลอกซาซิน', 'category' => 'Antibiotics', 'unit_price' => 15.00, 'cost_price' => 8.00],
            ['sku' => 'TF075', 'name' => 'Levofloxacin 500mg', 'name_th' => 'ลีโวฟลอกซาซิน', 'category' => 'Antibiotics', 'unit_price' => 35.00, 'cost_price' => 20.00],
            ['sku' => 'TF076', 'name' => 'Metronidazole 400mg', 'name_th' => 'เมโทรนิดาโซล', 'category' => 'Antibiotics', 'unit_price' => 5.00, 'cost_price' => 2.00],
            ['sku' => 'TF077', 'name' => 'Clindamycin 300mg', 'name_th' => 'คลินดามัยซิน', 'category' => 'Antibiotics', 'unit_price' => 18.00, 'cost_price' => 10.00],
            ['sku' => 'TF078', 'name' => 'Cephalexin 500mg', 'name_th' => 'เซฟาเล็กซิน', 'category' => 'Antibiotics', 'unit_price' => 12.00, 'cost_price' => 6.00],
            ['sku' => 'TF079', 'name' => 'Roxithromycin 150mg', 'name_th' => 'ร็อกซิโธรมัยซิน', 'category' => 'Antibiotics', 'unit_price' => 22.00, 'cost_price' => 12.00],
            ['sku' => 'TF080', 'name' => 'Cotrimoxazole 480mg', 'name_th' => 'โคไตรม็อกซาโซล', 'category' => 'Antibiotics', 'unit_price' => 3.00, 'cost_price' => 1.50],

            // สินค้าอื่นๆ (20)
            ['sku' => 'TF081', 'name' => 'Betadine Solution 30ml', 'name_th' => 'เบตาดีน', 'category' => 'Medical Supplies', 'unit_price' => 75.00, 'cost_price' => 45.00],
            ['sku' => 'TF082', 'name' => 'Hydrogen Peroxide 3% 100ml', 'name_th' => 'ไฮโดรเจนเพอร์ออกไซด์', 'category' => 'Medical Supplies', 'unit_price' => 25.00, 'cost_price' => 12.00],
            ['sku' => 'TF083', 'name' => 'Cotton Ball 100pcs', 'name_th' => 'สำลี ก้อน', 'category' => 'Medical Supplies', 'unit_price' => 35.00, 'cost_price' => 18.00],
            ['sku' => 'TF084', 'name' => 'Bandage Roll 2 inch', 'name_th' => 'ผ้าพันแผล', 'category' => 'Medical Supplies', 'unit_price' => 28.00, 'cost_price' => 15.00],
            ['sku' => 'TF085', 'name' => 'Face Mask 50pcs', 'name_th' => 'หน้ากากอนามัย', 'category' => 'Medical Supplies', 'unit_price' => 120.00, 'cost_price' => 70.00],
            ['sku' => 'TF086', 'name' => 'Thermometer Digital', 'name_th' => 'ปรอทวัดไข้ดิจิตอล', 'category' => 'Medical Supplies', 'unit_price' => 250.00, 'cost_price' => 150.00],
            ['sku' => 'TF087', 'name' => 'Blood Pressure Monitor', 'name_th' => 'เครื่องวัดความดัน', 'category' => 'Medical Supplies', 'unit_price' => 1200.00, 'cost_price' => 750.00],
            ['sku' => 'TF088', 'name' => 'Glucose Monitor Strips 50s', 'name_th' => 'แถบตรวจน้ำตาล', 'category' => 'Medical Supplies', 'unit_price' => 650.00, 'cost_price' => 420.00],
            ['sku' => 'TF089', 'name' => 'Nebulizer Mask Adult', 'name_th' => 'หน้ากากพ่นยา ผู้ใหญ่', 'category' => 'Medical Supplies', 'unit_price' => 180.00, 'cost_price' => 100.00],
            ['sku' => 'TF090', 'name' => 'Oral Syringe 5ml', 'name_th' => 'ไซริงค์ป้อนยา', 'category' => 'Medical Supplies', 'unit_price' => 15.00, 'cost_price' => 8.00],
            ['sku' => 'TF091', 'name' => 'ORS (Oral Rehydration Salt)', 'name_th' => 'ผงเกลือแร่', 'category' => 'Gastrointestinal', 'unit_price' => 8.00, 'cost_price' => 4.00],
            ['sku' => 'TF092', 'name' => 'Pedialyte Solution 500ml', 'name_th' => 'พีเดียไลท์', 'category' => 'Gastrointestinal', 'unit_price' => 85.00, 'cost_price' => 55.00],
            ['sku' => 'TF093', 'name' => 'Ensure Gold 850g', 'name_th' => 'เอ็นชัวร์ โกลด์', 'category' => 'Vitamins & Supplements', 'unit_price' => 850.00, 'cost_price' => 580.00],
            ['sku' => 'TF094', 'name' => 'Glucerna 850g', 'name_th' => 'กลูเซอร์นา', 'category' => 'Vitamins & Supplements', 'unit_price' => 920.00, 'cost_price' => 650.00],
            ['sku' => 'TF095', 'name' => 'Similac 850g', 'name_th' => 'ซิมิแลค', 'category' => 'Vitamins & Supplements', 'unit_price' => 780.00, 'cost_price' => 520.00],
            ['sku' => 'TF096', 'name' => 'Isomil 850g', 'name_th' => 'ไอโซมิล', 'category' => 'Vitamins & Supplements', 'unit_price' => 820.00, 'cost_price' => 560.00],
            ['sku' => 'TF097', 'name' => 'Desitin Cream 57g', 'name_th' => 'เดซิติน ครีม', 'category' => 'Skin Care', 'unit_price' => 320.00, 'cost_price' => 210.00],
            ['sku' => 'TF098', 'name' => 'Cetaphil Cleanser 250ml', 'name_th' => 'เซตาฟิล คลีนเซอร์', 'category' => 'Skin Care', 'unit_price' => 380.00, 'cost_price' => 250.00],
            ['sku' => 'TF099', 'name' => 'Ceradan Cream 80g', 'name_th' => 'เซราดาน ครีม', 'category' => 'Skin Care', 'unit_price' => 450.00, 'cost_price' => 300.00],
            ['sku' => 'TF100', 'name' => 'Bioderma Sensibio 250ml', 'name_th' => 'ไบโอเดอร์มา', 'category' => 'Skin Care', 'unit_price' => 520.00, 'cost_price' => 350.00],
        ];

        $allProducts = array_merge($products, $moreProducts);

        foreach ($allProducts as $productData) {
            $categoryId = $categories[$productData['category']] ?? ($categories['Pain Relief'] ?? 1);
            unset($productData['category']);

            $productData['category_id'] = $categoryId;
            $productData['generic_name'] = $productData['name'];
            $productData['barcode'] = '885' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $productData['member_price'] = $productData['unit_price'] * 0.9;
            $productData['stock_qty'] = rand(50, 500);
            $productData['min_stock'] = rand(10, 50);
            $productData['max_stock'] = $productData['min_stock'] * 10;
            $productData['reorder_point'] = $productData['min_stock'] * 1.5;
            $productData['unit'] = 'piece';
            $productData['base_unit'] = 'piece';
            $productData['sell_unit'] = 'box';
            $productData['conversion_factor'] = rand(10, 30);
            $productData['drug_class'] = 'ยาสามัญประจำบ้าน';
            $productData['manufacturer'] = 'Thai Pharma';
            $productData['location'] = chr(65 + rand(0, 5)) . rand(1, 5) . '-' . str_pad(rand(1, 20), 2, '0', STR_PAD_LEFT);
            $productData['requires_prescription'] = rand(0, 10) > 7;
            $productData['vat_applicable'] = false;

            Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('Created ' . count($allProducts) . ' Thai pharmacy products!');
    }
}
