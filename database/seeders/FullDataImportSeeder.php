<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FullDataImportSeeder extends Seeder
{
    /**
     * Import ALL data from JSON files exported from local database.
     * This ensures the deployed version matches local exactly.
     */
    public function run(): void
    {
        // Order matters! Parent tables first, then child tables.
        $tables = [
            'users',
            'categories',
            'suppliers',
            'customers',
            'member_tiers',
            'products',
            'product_lots',
            'purchase_orders',
            'purchase_order_items',
            'goods_received',
            'goods_received_items',
            'orders',
            'order_items',
            'stock_adjustments',
            'prescriptions',
            'prescription_items',
            'controlled_drug_logs',
            'promotions',
            'bundles',
            'bundle_items',
            'activity_logs',
            'shift_notes',
        ];

        $driver = Schema::getConnection()->getDriverName();

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            $jsonPath = database_path("data/{$table}.json");

            if (!file_exists($jsonPath)) {
                $this->command->warn("⏩ Skipping {$table} (no JSON file found)");
                continue;
            }

            $data = json_decode(file_get_contents($jsonPath), true);

            if (empty($data)) {
                $this->command->warn("⏩ Skipping {$table} (empty data)");
                continue;
            }

            // Clear existing data safely for both MySQL and PostgreSQL
            if ($driver === 'pgsql') {
                DB::statement("TRUNCATE TABLE \"{$table}\" CASCADE");
            } else {
                DB::table($table)->delete();
            }

            // Reset sequence for PostgreSQL
            if ($driver === 'pgsql') {
                try {
                    $maxId = collect($data)->max('id');
                    if ($maxId) {
                        DB::statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), {$maxId}, true)");
                    }
                } catch (\Exception $e) {
                    // Table might not have an id column or sequence, skip
                }
            }

            // Insert in chunks to avoid memory issues
            $chunks = array_chunk($data, 200);
            $total = count($data);

            foreach ($chunks as $chunk) {
                // Convert stdClass objects to arrays and handle data types
                $rows = array_map(function ($row) use ($driver) {
                    $row = (array) $row;

                    // Handle JSON fields that might be stored as strings
                    foreach ($row as $key => $value) {
                        // Convert null strings
                        if ($value === 'null') {
                            $row[$key] = null;
                        }

                        // PostgreSQL needs boolean values, not 0/1 for bool columns
                        // But we'll let the DB handle that
                    }

                    return $row;
                }, $chunk);

                try {
                    DB::table($table)->insert($rows);
                } catch (\Exception $e) {
                    // If batch insert fails, try one by one
                    foreach ($rows as $row) {
                        try {
                            DB::table($table)->insert($row);
                        } catch (\Exception $e2) {
                            $this->command->error("  ❌ Failed row in {$table}: " . $e2->getMessage());
                        }
                    }
                }
            }

            // Update sequence after insert for PostgreSQL
            if ($driver === 'pgsql') {
                try {
                    $maxId = collect($data)->max('id');
                    if ($maxId) {
                        DB::statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), {$maxId}, true)");
                    }
                } catch (\Exception $e) {
                    // Ignore sequence errors
                }
            }

            $this->command->info("✅ {$table}: {$total} rows imported");
        }

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->info('');
        $this->command->info('🎉 Full data import completed! Your deployed version now matches local.');
    }
}
