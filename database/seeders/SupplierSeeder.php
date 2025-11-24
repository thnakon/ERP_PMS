<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing suppliers to avoid duplicates if re-seeded
        // DB::table('suppliers')->truncate(); // Optional: Be careful with foreign keys

        $suppliers = [
            [
                'name' => 'บริษัท ยาดี จำกัด',
                'contact_person' => 'คุณสมชาย',
                'phone' => '081-234-5678',
                'email' => 'contact@yad.co.th',
                'address' => '123 ถ.สุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            ],
            [
                'name' => 'Siam Pharma Supply',
                'contact_person' => 'Ms. Sarah',
                'phone' => '02-999-8888',
                'email' => 'sales@siampharma.com',
                'address' => '456 Phetchaburi Rd, Ratchathewi, Bangkok 10400',
            ],
            [
                'name' => 'MediCare Distribution',
                'contact_person' => 'คุณวิชัย',
                'phone' => '089-111-2222',
                'email' => 'support@medicare.co.th',
                'address' => '789 Phahonyothin Rd, Chatuchak, Bangkok 10900',
            ],
            [
                'name' => 'Global Health Logistics',
                'contact_person' => 'Mr. David',
                'phone' => '02-555-4444',
                'email' => 'info@globalhealth.com',
                'address' => '101 Sathorn Rd, Silom, Bang Rak, Bangkok 10500',
            ],
            [
                'name' => 'Thai Medical Supplies',
                'contact_person' => 'คุณนารี',
                'phone' => '086-777-6666',
                'email' => 'sales@thaimed.com',
                'address' => '222 Lat Phrao Rd, Wang Thonglang, Bangkok 10310',
            ],
            [
                'name' => 'Apex Pharma',
                'contact_person' => 'Dr. Somkiat',
                'phone' => '02-123-4567',
                'email' => 'contact@apexpharma.com',
                'address' => '88 Vibhavadi Rangsit Rd, Din Daeng, Bangkok 10400',
            ],
            [
                'name' => 'BioLife Solutions',
                'contact_person' => 'Ms. Jenny',
                'phone' => '090-987-6543',
                'email' => 'orders@biolife.com',
                'address' => '55 Rama IX Rd, Huai Khwang, Bangkok 10310',
            ],
            [
                'name' => 'Central Medical Lab',
                'contact_person' => 'คุณประเสริฐ',
                'phone' => '02-888-9999',
                'email' => 'lab@centralmed.com',
                'address' => '333 Charoen Krung Rd, Bang Kho Laem, Bangkok 10120',
            ],
            [
                'name' => 'Eastern Drug Store',
                'contact_person' => 'คุณมานะ',
                'phone' => '038-111-222',
                'email' => 'info@easterndrug.com',
                'address' => '12 Sukhumvit Rd, Si Racha, Chon Buri 20110',
            ],
            [
                'name' => 'Phuket Health Mart',
                'contact_person' => 'Mrs. Suda',
                'phone' => '076-333-444',
                'email' => 'sales@phukethealth.com',
                'address' => '99 Thepkrasattri Rd, Mueang Phuket, Phuket 83000',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['email' => $supplier['email']], // Check by email to avoid duplicates
                $supplier
            );
        }
    }
}
