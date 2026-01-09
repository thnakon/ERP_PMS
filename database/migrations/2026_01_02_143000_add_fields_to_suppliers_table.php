<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Company Info
            $table->string('tax_id')->nullable()->after('name');
            $table->text('shipping_address')->nullable()->after('address');

            // Additional Contact
            $table->string('mobile')->nullable()->after('phone');
            $table->string('line_id')->nullable()->after('email');

            // Trade Terms
            $table->integer('credit_term')->default(30)->after('line_id'); // days
            $table->integer('lead_time')->default(3)->after('credit_term'); // days
            $table->decimal('min_order_qty', 10, 2)->default(0)->after('lead_time');

            // Banking
            $table->string('bank_name')->nullable()->after('min_order_qty');
            $table->string('bank_account_no')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_no');

            // Status
            $table->boolean('is_active')->default(true)->after('bank_account_name');
            $table->text('notes')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'tax_id',
                'shipping_address',
                'mobile',
                'line_id',
                'credit_term',
                'lead_time',
                'min_order_qty',
                'bank_name',
                'bank_account_no',
                'bank_account_name',
                'is_active',
                'notes'
            ]);
        });
    }
};
