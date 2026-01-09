<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Simulates 3 months of usage data for Thai pharmacy
     */
    public function run(): void
    {
        $this->command->info('🏪 Starting Thai Pharmacy Demo Data Seeder...');
        $this->command->info('');

        // 1. Core data
        $this->command->info('📌 Step 1: Creating Users & Staff...');
        $this->call(UserSeeder::class);

        $this->command->info('📌 Step 2: Creating Categories...');
        $this->call(CategorySeeder::class);

        $this->command->info('📌 Step 3: Creating Suppliers (Thai)...');
        $this->call(SupplierSeeder::class);

        // 2. Products
        $this->command->info('📌 Step 4: Creating Thai Pharmacy Products (100+)...');
        $this->call(ThaiProductSeeder::class);
        $this->call(ProductSeeder::class);

        $this->command->info('📌 Step 5: Creating Product Lots (with expiry)...');
        $this->call(ProductLotSeeder::class);

        // 3. Customers
        $this->command->info('📌 Step 6: Creating Customers...');
        $this->call(CustomerSeeder::class);

        // 4. Purchasing
        $this->command->info('📌 Step 7: Creating Purchase Orders...');
        $this->call(PurchaseOrderSeeder::class);

        $this->command->info('📌 Step 8: Creating Goods Received...');
        $this->call(GoodsReceivedSeeder::class);

        // 5. Sales (3 months history)
        $this->command->info('📌 Step 9: Creating Orders (3 months history)...');
        $this->call(OrderSeeder::class);

        // 6. Inventory
        $this->command->info('📌 Step 10: Creating Stock Adjustments...');
        $this->call(StockAdjustmentSeeder::class);

        // 7. Calendar & Events
        $this->command->info('📌 Step 11: Creating Calendar Events (Past & Future)...');
        $this->call(CalendarEventSeeder::class);

        // 8. Activity Logs
        $this->command->info('📌 Step 12: Creating Activity Logs...');
        $this->call(ActivityLogSeeder::class);

        $this->command->info('📌 Step 13: Creating Backup History...');
        $this->call(BackupSeeder::class);

        $this->command->info('📌 Step 14: Creating Shift Notes (Sticky Notes)...');
        $this->call(ShiftNoteSeeder::class);

        $this->command->info('📌 Step 15: Creating Controlled Drug Logs...');
        $this->call(ControlledDrugSeeder::class);

        $this->command->info('📌 Step 16: Creating Drug Interactions...');
        $this->call(DrugInteractionSeeder::class);

        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📝 Demo Accounts:');
        $this->command->info('   Admin: admin@oboun.local / password');
        $this->command->info('   Staff: staff@oboun.local / password');
        $this->command->info('');
    }
}
