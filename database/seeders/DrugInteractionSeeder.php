<?php

namespace Database\Seeders;

use App\Models\DrugInteraction;
use Illuminate\Database\Seeder;

class DrugInteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding drug interactions...');

        $interactions = [
            // Contraindicated - ห้ามใช้ร่วมกัน
            [
                'drug_a_name' => 'Warfarin',
                'drug_b_name' => 'Aspirin',
                'severity' => 'major',
                'description' => 'การใช้ Warfarin ร่วมกับ Aspirin เพิ่มความเสี่ยงในการเกิดเลือดออก',
                'mechanism' => 'ทั้งสองยาต้านการแข็งตัวของเลือด ทำให้เพิ่มความเสี่ยงเลือดออก',
                'management' => 'หลีกเลี่ยงการใช้ร่วมกัน หรือติดตามค่า INR อย่างใกล้ชิด',
            ],
            [
                'drug_a_name' => 'Simvastatin',
                'drug_b_name' => 'Erythromycin',
                'severity' => 'contraindicated',
                'description' => 'ห้ามใช้ Simvastatin ร่วมกับ Erythromycin เพราะเพิ่มความเสี่ยง Rhabdomyolysis',
                'mechanism' => 'Erythromycin ยับยั้ง CYP3A4 ทำให้ระดับ Simvastatin ในเลือดสูงขึ้น',
                'management' => 'พิจารณาเปลี่ยนไปใช้ยากลุ่ม Statin อื่นที่ไม่ถูก metabolize ผ่าน CYP3A4',
            ],
            [
                'drug_a_name' => 'Metformin',
                'drug_b_name' => 'Contrast Media (Iodinated)',
                'severity' => 'major',
                'description' => 'ควรหยุด Metformin ก่อนและหลังฉีดสารทึบรังสี เพื่อป้องกัน Lactic Acidosis',
                'mechanism' => 'สารทึบรังสีอาจทำให้ไตทำงานลดลงชั่วคราว ส่งผลให้ Metformin สะสมในร่างกาย',
                'management' => 'หยุด Metformin 48 ชม. ก่อนและหลังฉีดสารทึบรังสี ตรวจการทำงานของไตก่อนเริ่มยาใหม่',
            ],

            // Major - รุนแรง
            [
                'drug_a_name' => 'Ciprofloxacin',
                'drug_b_name' => 'Theophylline',
                'severity' => 'major',
                'description' => 'Ciprofloxacin เพิ่มระดับ Theophylline ในเลือด อาจทำให้เกิดพิษ',
                'mechanism' => 'Ciprofloxacin ยับยั้ง CYP1A2 ซึ่งเป็นเอนไซม์หลักในการ metabolize Theophylline',
                'management' => 'ลดขนาด Theophylline 30-50% และตรวจระดับยา หรือเปลี่ยนไปใช้ Fluoroquinolone อื่น',
            ],
            [
                'drug_a_name' => 'Omeprazole',
                'drug_b_name' => 'Clopidogrel',
                'severity' => 'major',
                'description' => 'Omeprazole ลดประสิทธิภาพของ Clopidogrel ในการป้องกันลิ่มเลือด',
                'mechanism' => 'Omeprazole ยับยั้ง CYP2C19 ซึ่งจำเป็นต่อการเปลี่ยน Clopidogrel เป็นยาออกฤทธิ์',
                'management' => 'พิจารณาใช้ Pantoprazole หรือ H2-blocker แทน',
            ],
            [
                'drug_a_name' => 'Fluoxetine',
                'drug_b_name' => 'Tramadol',
                'severity' => 'major',
                'description' => 'เพิ่มความเสี่ยงในการเกิด Serotonin Syndrome และชัก',
                'mechanism' => 'ทั้งสองยาเพิ่มการทำงานของ Serotonin',
                'management' => 'หลีกเลี่ยงการใช้ร่วมกัน หรือใช้ยาแก้ปวดตัวอื่น',
            ],

            // Moderate - ปานกลาง
            [
                'drug_a_name' => 'Metformin',
                'drug_b_name' => 'Alcohol',
                'severity' => 'moderate',
                'description' => 'แอลกอฮอล์เพิ่มความเสี่ยง Lactic Acidosis และน้ำตาลในเลือดต่ำ',
                'mechanism' => 'แอลกอฮอล์รบกวนการสร้างกลูโคสในตับ และเพิ่มการสะสมกรด Lactic',
                'management' => 'แนะนำให้จำกัดการดื่มแอลกอฮอล์ และหลีกเลี่ยงการดื่มขณะท้องว่าง',
            ],
            [
                'drug_a_name' => 'Amlodipine',
                'drug_b_name' => 'Simvastatin',
                'severity' => 'moderate',
                'description' => 'Amlodipine เพิ่มระดับ Simvastatin ในเลือด',
                'mechanism' => 'Amlodipine ยับยั้ง CYP3A4 บางส่วน',
                'management' => 'จำกัดขนาด Simvastatin ไม่เกิน 20 mg/วัน เมื่อใช้ร่วมกับ Amlodipine',
            ],
            [
                'drug_a_name' => 'Ibuprofen',
                'drug_b_name' => 'Lisinopril',
                'severity' => 'moderate',
                'description' => 'NSAIDs ลดประสิทธิภาพของ ACE inhibitors และเพิ่มความเสี่ยงไตวาย',
                'mechanism' => 'NSAIDs ลดการสร้าง Prostaglandins ที่ช่วยขยายหลอดเลือดไต',
                'management' => 'ใช้ยาแก้ปวดกลุ่มอื่นถ้าเป็นไปได้ หรือติดตามการทำงานของไต',
            ],
            [
                'drug_a_name' => 'Gabapentin',
                'drug_b_name' => 'Morphine',
                'severity' => 'moderate',
                'description' => 'เพิ่มฤทธิ์กดประสาทและความเสี่ยงหายใจลำบาก',
                'mechanism' => 'ทั้งสองยามีฤทธิ์กดระบบประสาทส่วนกลาง',
                'management' => 'เริ่มต้นด้วยขนาดต่ำและปรับขึ้นอย่างช้าๆ ติดตามอาการซึมและหายใจ',
            ],

            // Minor - เล็กน้อย
            [
                'drug_a_name' => 'Levothyroxine',
                'drug_b_name' => 'Calcium Carbonate',
                'severity' => 'minor',
                'description' => 'Calcium ลดการดูดซึม Levothyroxine',
                'mechanism' => 'Calcium จับกับ Levothyroxine ในทางเดินอาหารทำให้ดูดซึมน้อยลง',
                'management' => 'รับประทาน Levothyroxine 4 ชั่วโมงก่อนหรือหลังรับประทาน Calcium',
            ],
            [
                'drug_a_name' => 'Metformin',
                'drug_b_name' => 'Vitamin B12',
                'severity' => 'minor',
                'description' => 'การใช้ Metformin ระยะยาวลดการดูดซึม Vitamin B12',
                'mechanism' => 'Metformin รบกวนการดูดซึม Vitamin B12 ที่ลำไส้',
                'management' => 'ตรวจระดับ Vitamin B12 เป็นระยะและให้อาหารเสริมถ้าจำเป็น',
            ],
            [
                'drug_a_name' => 'Atorvastatin',
                'drug_b_name' => 'Grapefruit Juice',
                'severity' => 'minor',
                'description' => 'น้ำเกรปฟรุตเพิ่มระดับ Atorvastatin ในเลือดเล็กน้อย',
                'mechanism' => 'น้ำเกรปฟรุตยับยั้ง CYP3A4 ในลำไส้',
                'management' => 'จำกัดการดื่มน้ำเกรปฟรุตหรือหลีกเลี่ยง',
            ],
        ];

        foreach ($interactions as $interaction) {
            DrugInteraction::updateOrCreate(
                [
                    'drug_a_name' => $interaction['drug_a_name'],
                    'drug_b_name' => $interaction['drug_b_name'],
                ],
                [
                    'severity' => $interaction['severity'],
                    'description' => $interaction['description'],
                    'mechanism' => $interaction['mechanism'] ?? null,
                    'management' => $interaction['management'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Created ' . count($interactions) . ' drug interactions.');
    }
}
