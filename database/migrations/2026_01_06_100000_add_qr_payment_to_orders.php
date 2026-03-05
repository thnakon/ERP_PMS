<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Modify payment_method enum to include 'qr' and 'promptpay'
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'card', 'transfer', 'credit', 'qr', 'promptpay') DEFAULT 'cash'");
        } elseif ($driver === 'pgsql') {
            // PostgreSQL doesn't support MODIFY COLUMN ENUM easily. Using string with fallback.
            DB::statement("ALTER TABLE orders ALTER COLUMN payment_method TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE orders ALTER COLUMN payment_method SET DEFAULT 'cash'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'card', 'transfer', 'credit') DEFAULT 'cash'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE orders ALTER COLUMN payment_method TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE orders ALTER COLUMN payment_method SET DEFAULT 'cash'");
        }
    }
};
