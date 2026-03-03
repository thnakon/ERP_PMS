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
        // Modify payment_method enum to include 'qr' and 'promptpay'
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'card', 'transfer', 'credit', 'qr', 'promptpay') DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing 'qr' or 'promptpay' records to 'transfer' before shrinking the ENUM to avoid truncation error
        DB::table('orders')
            ->whereIn('payment_method', ['qr', 'promptpay'])
            ->update(['payment_method' => 'transfer']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'card', 'transfer', 'credit') DEFAULT 'cash'");
    }
};
