<?php

namespace Database\Seeders;

use App\Models\ControlledDrugLog;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ControlledDrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding controlled drug data...');

        // First, update some products to be controlled drugs
        $this->updateProductsToControlled();

        // Then create controlled drug logs
        $this->createControlledDrugLogs();
    }

    private function updateProductsToControlled(): void
    {
        $controlledDrugs = [
            // Dangerous Drugs (ยาอันตราย)
            ['name' => 'Amoxicillin', 'schedule' => 'dangerous', 'approval' => true],
            ['name' => 'Ciprofloxacin', 'schedule' => 'dangerous', 'approval' => true],
            ['name' => 'Ibuprofen', 'schedule' => 'dangerous', 'approval' => true],
            ['name' => 'Omeprazole', 'schedule' => 'dangerous', 'approval' => true],

            // Specially Controlled (ยาควบคุมพิเศษ)
            ['name' => 'Tramadol', 'schedule' => 'specially_controlled', 'approval' => true],
            ['name' => 'Gabapentin', 'schedule' => 'specially_controlled', 'approval' => true],

            // Psychotropic (วัตถุออกฤทธิ์ต่อจิตประสาท)
            ['name' => 'Diazepam', 'schedule' => 'psychotropic', 'approval' => true],
            ['name' => 'Alprazolam', 'schedule' => 'psychotropic', 'approval' => true],
        ];

        foreach ($controlledDrugs as $drug) {
            $product = Product::where('name', 'like', "%{$drug['name']}%")->first();
            if ($product) {
                $product->update([
                    'drug_schedule' => $drug['schedule'],
                    'requires_pharmacist_approval' => $drug['approval'],
                ]);
                $this->command->info("  Updated {$product->name} to {$drug['schedule']}");
            }
        }

        // If no products found with those names, just update some random products
        $updatedCount = Product::whereIn('drug_schedule', ['dangerous', 'specially_controlled', 'narcotic', 'psychotropic'])->count();

        if ($updatedCount < 4) {
            $this->command->info('  Marking some products as controlled drugs...');

            // Update 4 random products to dangerous
            Product::where('is_active', true)
                ->where('drug_schedule', 'normal')
                ->limit(4)
                ->update([
                    'drug_schedule' => 'dangerous',
                    'requires_pharmacist_approval' => true,
                ]);

            // Update 2 random products to specially_controlled
            Product::where('is_active', true)
                ->where('drug_schedule', 'normal')
                ->limit(2)
                ->update([
                    'drug_schedule' => 'specially_controlled',
                    'requires_pharmacist_approval' => true,
                ]);

            // Update 1 random product to psychotropic
            Product::where('is_active', true)
                ->where('drug_schedule', 'normal')
                ->limit(1)
                ->update([
                    'drug_schedule' => 'psychotropic',
                    'requires_pharmacist_approval' => true,
                ]);
        }
    }

    private function createControlledDrugLogs(): void
    {
        $products = Product::controlled()->where('is_active', true)->get();
        $customers = Customer::limit(5)->get();
        $adminUser = User::where('role', 'admin')->first() ?? User::first();

        if ($products->isEmpty()) {
            $this->command->warn('No controlled drugs found.');
            return;
        }

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found.');
            return;
        }

        $doctors = [
            ['name' => 'นพ.สุรชัย แพทย์ดี', 'license' => 'ว.11111', 'hospital' => 'โรงพยาบาลศิริราช'],
            ['name' => 'พญ.อรทัย หมอใจดี', 'license' => 'ว.22222', 'hospital' => 'โรงพยาบาลจุฬาลงกรณ์'],
            ['name' => 'นพ.วิศิษฐ์ รักษาใจ', 'license' => 'ว.33333', 'hospital' => 'คลินิกแพทย์วิศิษฐ์'],
        ];

        $purposes = [
            'บรรเทาอาการปวด',
            'รักษาการติดเชื้อ',
            'ลดความวิตกกังวล',
            'รักษาอาการนอนไม่หลับ',
            'ควบคุมอาการลมชัก',
        ];

        $count = 0;

        // Create 15 sample controlled drug logs
        for ($i = 0; $i < 15; $i++) {
            $product = $products->random();
            $customer = $customers->random();
            $doctor = $doctors[array_rand($doctors)];
            $purpose = $purposes[array_rand($purposes)];

            $createdAt = Carbon::now()->subDays(rand(0, 30));

            // Random status - more approved than pending
            $statuses = ['approved', 'approved', 'approved', 'pending', 'pending'];
            $status = $statuses[array_rand($statuses)];

            $transactionTypes = ['sale', 'sale', 'dispense', 'sale'];
            $transactionType = $transactionTypes[array_rand($transactionTypes)];

            $log = ControlledDrugLog::create([
                'product_id' => $product->id,
                'quantity' => rand(5, 30),
                'transaction_type' => $transactionType,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_id_card' => $this->generateThaiIdCard(),
                'customer_phone' => $customer->phone,
                'customer_address' => $customer->address,
                'customer_age' => rand(20, 75) . ' ปี',
                'prescription_number' => $transactionType === 'dispense' ? 'RX-' . strtoupper(substr(md5(rand()), 0, 8)) : null,
                'doctor_name' => $transactionType === 'dispense' ? $doctor['name'] : null,
                'doctor_license_no' => $transactionType === 'dispense' ? $doctor['license'] : null,
                'hospital_clinic' => $transactionType === 'dispense' ? $doctor['hospital'] : null,
                'purpose' => $purpose,
                'indication' => $product->description ?? 'ตามดุลยพินิจของแพทย์',
                'status' => $status,
                'approved_by' => $status === 'approved' ? $adminUser->id : null,
                'approved_at' => $status === 'approved' ? $createdAt->copy()->addMinutes(rand(5, 60)) : null,
                'created_by' => $adminUser->id,
                'notes' => 'ข้อมูลตัวอย่าง - สร้างจาก Seeder',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $count++;
        }

        $this->command->info("Created {$count} controlled drug logs.");
    }

    /**
     * Generate a fake Thai ID card number
     */
    private function generateThaiIdCard(): string
    {
        $digits = '';
        for ($i = 0; $i < 13; $i++) {
            $digits .= rand(0, 9);
        }
        return substr($digits, 0, 1) . '-' .
            substr($digits, 1, 4) . '-' .
            substr($digits, 5, 5) . '-' .
            substr($digits, 10, 2) . '-' .
            substr($digits, 12, 1);
    }
}
