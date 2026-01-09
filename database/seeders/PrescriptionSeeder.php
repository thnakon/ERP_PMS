<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding prescriptions...');

        // Get existing customers and products
        $customers = Customer::limit(5)->get();
        $products = Product::where('is_active', true)->limit(10)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please seed customers first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please seed products first.');
            return;
        }

        $doctors = [
            ['name' => 'นพ.สมชาย รักษาดี', 'license' => 'ว.12345', 'hospital' => 'โรงพยาบาลกรุงเทพ', 'phone' => '02-123-4567'],
            ['name' => 'พญ.สมหญิง ใจดี', 'license' => 'ว.23456', 'hospital' => 'คลินิกสุขภาพ', 'phone' => '02-234-5678'],
            ['name' => 'นพ.วิชัย หายป่วย', 'license' => 'ว.34567', 'hospital' => 'โรงพยาบาลเอกชน', 'phone' => '02-345-6789'],
            ['name' => 'พญ.มาลี รักษ์สุข', 'license' => 'ว.45678', 'hospital' => 'ศูนย์การแพทย์มหาวิทยาลัย', 'phone' => '02-456-7890'],
        ];

        $diagnoses = [
            'ไข้หวัด อาการคัดจมูก',
            'ความดันโลหิตสูง',
            'เบาหวานชนิดที่ 2',
            'ปวดหลังเรื้อรัง',
            'ภูมิแพ้ทางเดินหายใจ',
            'กรดไหลย้อน',
            'อาการปวดศีรษะไมเกรน',
            'ติดเชื้อทางเดินหายใจส่วนบน',
        ];

        $frequencies = [
            'วันละ 1 ครั้ง',
            'วันละ 2 ครั้ง',
            'วันละ 3 ครั้ง',
            'ทุก 8 ชั่วโมง',
            'ก่อนนอน',
            'หลังอาหาร',
            'เมื่อมีอาการ',
        ];

        $routes = [
            'รับประทาน',
            'ทาภายนอก',
            'สูดดม',
            'หยอดตา',
        ];

        $count = 0;

        // Create 8 sample prescriptions
        foreach ($customers->take(4) as $customer) {
            // Create 2 prescriptions per customer
            for ($i = 0; $i < 2; $i++) {
                $doctor = $doctors[array_rand($doctors)];
                $diagnosis = $diagnoses[array_rand($diagnoses)];
                $prescriptionDate = Carbon::now()->subDays(rand(1, 30));

                // Random status
                $statuses = ['pending', 'dispensed', 'dispensed'];
                $status = $statuses[array_rand($statuses)];

                $prescription = Prescription::create([
                    'customer_id' => $customer->id,
                    'user_id' => 1, // Admin user
                    'doctor_name' => $doctor['name'],
                    'doctor_license_no' => $doctor['license'],
                    'hospital_clinic' => $doctor['hospital'],
                    'doctor_phone' => $doctor['phone'],
                    'prescription_date' => $prescriptionDate,
                    'expiry_date' => $prescriptionDate->copy()->addMonths(3),
                    'diagnosis' => $diagnosis,
                    'notes' => 'ใบสั่งยาตัวอย่าง - สร้างจาก Seeder',
                    'status' => $status,
                    'dispensed_at' => $status === 'dispensed' ? $prescriptionDate->copy()->addHours(rand(1, 4)) : null,
                    'refill_allowed' => rand(0, 3),
                    'refill_count' => 0,
                    'next_refill_date' => $status === 'dispensed' ? Carbon::now()->addDays(rand(-5, 10)) : null,
                ]);

                // Add 2-4 items per prescription
                $itemCount = rand(2, 4);
                $selectedProducts = $products->random(min($itemCount, $products->count()));

                foreach ($selectedProducts as $product) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'product_id' => $product->id,
                        'quantity' => rand(10, 60),
                        'dosage' => rand(1, 2) . ' เม็ด',
                        'frequency' => $frequencies[array_rand($frequencies)],
                        'duration' => rand(5, 14) . ' วัน',
                        'route' => $routes[array_rand($routes)],
                        'instructions' => rand(0, 1) ? 'รับประทานพร้อมอาหาร' : null,
                        'unit_price' => $product->unit_price,
                        'quantity_dispensed' => $status === 'dispensed' ? rand(10, 60) : 0,
                        'is_dispensed' => $status === 'dispensed',
                    ]);
                }

                $count++;
            }
        }

        $this->command->info("Created {$count} sample prescriptions.");
    }
}
