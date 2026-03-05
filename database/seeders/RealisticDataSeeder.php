<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        Schema::disableForeignKeyConstraints();

        $tables = [
            'orders',
            'backups',
            'order_items',
            'purchase_orders',
            'purchase_order_items',
            'goods_received',
            'goods_received_items',
            'product_lots',
            'prescriptions',
            'prescription_items',
            'controlled_drug_logs',
            'stock_adjustments',
            'calendar_events',
            'activity_logs',
            'shift_notes',
            'bundles',
            'bundle_items',
            'promotions',
            'promotion_products',
            'promotion_categories',
            'promotion_usages',
        ];

        $driver = Schema::getConnection()->getDriverName();

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if ($driver === 'pgsql') {
                    DB::table($table)->delete();
                    // Reset auto-increment sequence for PostgreSQL
                    DB::statement("ALTER SEQUENCE IF EXISTS {$table}_id_seq RESTART WITH 1");
                } else {
                    DB::table($table)->truncate();
                }
            }
        }

        Schema::enableForeignKeyConstraints();

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
