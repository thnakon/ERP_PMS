<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealisticDataSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic data from Feb 1, 2026 to Apr 1, 2026.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Realistic Data Seeder (Feb 1, 2026 - Apr 1, 2026)...');

        // 0. Clear Transactional Data for a fresh start
        $this->command->info('🧹 Clearing existing transactional data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('orders')->truncate();
        DB::table('backups')->truncate();
        DB::table('order_items')->truncate();
        DB::table('order_items')->truncate();
        DB::table('purchase_orders')->truncate();
        DB::table('purchase_order_items')->truncate();
        DB::table('goods_received')->truncate();
        DB::table('goods_received_items')->truncate();
        DB::table('product_lots')->truncate();
        DB::table('prescriptions')->truncate();
        DB::table('prescription_items')->truncate();
        DB::table('controlled_drug_logs')->truncate();
        DB::table('stock_adjustments')->truncate();
        DB::table('calendar_events')->truncate();
        DB::table('activity_logs')->truncate();
        DB::table('shift_notes')->truncate();
        DB::table('bundles')->truncate(); // We'll re-seed
        DB::table('bundle_items')->truncate();
        DB::table('promotions')->truncate(); // We'll re-seed
        DB::table('promotion_products')->truncate();
        DB::table('promotion_categories')->truncate();
        DB::table('promotion_usages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $startDate = Carbon::create(2026, 2, 1);
        $endDate = Carbon::create(2026, 4, 1);

        // 1. Core Data (Ensure these exist)
        $this->command->info('📌 Seeding Core Data...');
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ThaiProductSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(MemberTierSeeder::class);
        $this->call(CustomerSeeder::class);

        // 2. Transactional Data with Date Range
        $this->command->info('📌 Seeding Transactional Data (Feb - Apr 2026)...');

        // Pass dates to sub-seeders if they support it, or modify them to use these dates.
        // For now, I will modify the seeders directly to use this range for this task.

        $this->call(BackupSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(PurchaseOrderSeeder::class);
        $this->call(GoodsReceivedSeeder::class);
        $this->call(PrescriptionSeeder::class);
        $this->call(ControlledDrugSeeder::class);
        $this->call(StockAdjustmentSeeder::class);
        $this->call(CalendarEventSeeder::class);
        $this->call(ActivityLogSeeder::class);
        $this->call(ShiftNoteSeeder::class);
        $this->call(BundleSeeder::class);
        $this->call(PromotionSeeder::class);

        $this->command->info('✅ Realistic Data Seeding Completed!');
    }
}
