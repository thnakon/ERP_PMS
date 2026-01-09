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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $defaultSettings = [
            ['key' => 'store_name', 'value' => 'OBOUN Pharmacy', 'type' => 'string', 'group' => 'store', 'description' => 'Store name'],
            ['key' => 'store_address', 'value' => '123 ถนนสุขุมวิท กรุงเทพฯ 10110', 'type' => 'string', 'group' => 'store', 'description' => 'Store address'],
            ['key' => 'store_phone', 'value' => '02-123-4567', 'type' => 'string', 'group' => 'store', 'description' => 'Store phone'],
            ['key' => 'store_email', 'value' => 'info@oboun.local', 'type' => 'string', 'group' => 'store', 'description' => 'Store email'],
            ['key' => 'store_logo', 'value' => '', 'type' => 'string', 'group' => 'store', 'description' => 'Store logo path'],
            ['key' => 'store_favicon', 'value' => '', 'type' => 'string', 'group' => 'store', 'description' => 'Store favicon path'],
            ['key' => 'currency_symbol', 'value' => '฿', 'type' => 'string', 'group' => 'financial', 'description' => 'Currency symbol'],
            ['key' => 'vat_percentage', 'value' => '7', 'type' => 'integer', 'group' => 'financial', 'description' => 'VAT percentage'],
            ['key' => 'enable_low_stock_alert', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Enable low stock alerts'],
            ['key' => 'enable_expiry_alert', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Enable expiry alerts'],
            ['key' => 'loyalty_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'loyalty', 'description' => 'Enable loyalty program'],
            ['key' => 'points_per_baht', 'value' => '1', 'type' => 'integer', 'group' => 'loyalty', 'description' => 'Points earned per baht spent'],
            ['key' => 'points_min_redeem', 'value' => '100', 'type' => 'integer', 'group' => 'loyalty', 'description' => 'Minimum points to redeem'],
            ['key' => 'receipt_show_tax', 'value' => '1', 'type' => 'boolean', 'group' => 'receipt', 'description' => 'Show tax details on receipt'],
            ['key' => 'receipt_show_logo', 'value' => '1', 'type' => 'boolean', 'group' => 'receipt', 'description' => 'Show logo on receipt'],
            ['key' => 'receipt_footer', 'value' => 'ขอบคุณที่ใช้บริการ', 'type' => 'string', 'group' => 'receipt', 'description' => 'Receipt footer message'],
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
