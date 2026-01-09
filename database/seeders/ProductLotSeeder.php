<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductLotSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        if ($suppliers->isEmpty()) {
            // Create some default suppliers if none exist
            $supplierData = [
                ['name' => 'GPO Distribution', 'contact_person' => 'สมชาย ใจดี', 'phone' => '02-123-4567', 'email' => 'gpo@example.com'],
                ['name' => 'Siam Pharma Supply', 'contact_person' => 'สมหญิง รักดี', 'phone' => '02-234-5678', 'email' => 'siam@example.com'],
                ['name' => 'MedTech Thailand', 'contact_person' => 'ประสิทธิ์ ดีใจ', 'phone' => '02-345-6789', 'email' => 'medtech@example.com'],
            ];
            foreach ($supplierData as $data) {
                Supplier::create($data);
            }
            $suppliers = Supplier::all();
        }

        $lotCount = 0;

        foreach ($products as $product) {
            // Create 2-4 lots per product
            $numLots = rand(2, 4);

            for ($i = 0; $i < $numLots; $i++) {
                // Generate various expiry scenarios
                $expiryScenario = rand(1, 10);

                switch ($expiryScenario) {
                    case 1: // Already expired
                        $expiryDate = Carbon::now()->subDays(rand(1, 60));
                        break;
                    case 2: // Expiring within 7 days (critical)
                        $expiryDate = Carbon::now()->addDays(rand(1, 7));
                        break;
                    case 3: // Expiring within 30 days (warning)
                        $expiryDate = Carbon::now()->addDays(rand(8, 30));
                        break;
                    case 4: // Expiring within 90 days (notice)
                        $expiryDate = Carbon::now()->addDays(rand(31, 90));
                        break;
                    default: // Good (more than 90 days)
                        $expiryDate = Carbon::now()->addDays(rand(91, 730));
                        break;
                }

                $manufacturedDate = $expiryDate->copy()->subYears(rand(1, 3));
                $initialQty = rand(50, 500);
                $usedQty = $expiryScenario <= 4 ? rand(10, $initialQty - 10) : rand(0, $initialQty / 2);
                $remainingQty = $initialQty - $usedQty;

                ProductLot::create([
                    'product_id' => $product->id,
                    'supplier_id' => $suppliers->random()->id,
                    'lot_number' => 'LOT' . strtoupper(substr(md5(uniqid()), 0, 8)),
                    'expiry_date' => $expiryDate,
                    'manufactured_date' => $manufacturedDate,
                    'quantity' => max(0, $remainingQty),
                    'initial_quantity' => $initialQty,
                    'cost_price' => $product->cost_price ?? rand(5, 50),
                    'gr_reference' => 'GR-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'received_at' => $manufacturedDate->copy()->addDays(rand(7, 30)),
                    'notes' => $expiryScenario <= 1 ? 'สินค้าหมดอายุแล้ว ต้องทำลาย' : null,
                ]);

                $lotCount++;
            }
        }

        $this->command->info("Created {$lotCount} product lots for expiry management!");
    }
}
