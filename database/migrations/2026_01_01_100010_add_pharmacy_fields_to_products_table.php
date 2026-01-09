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
        Schema::table('products', function (Blueprint $table) {
            // Identity
            $table->string('barcode')->nullable()->after('sku');
            $table->string('image_path')->nullable()->after('generic_name');

            // Classification
            $table->string('drug_class')->nullable()->after('category_id'); // ยาอันตราย, ยาควบคุมพิเศษ, ยาสามัญ, etc.
            $table->string('manufacturer')->nullable()->after('drug_class');

            // Pricing & Units
            $table->decimal('member_price', 10, 2)->nullable()->after('unit_price');
            $table->boolean('vat_applicable')->default(true)->after('member_price');
            $table->string('base_unit')->default('pcs')->after('unit'); // Base unit (เม็ด, กรัม)
            $table->string('sell_unit')->nullable()->after('base_unit'); // Selling unit (แผง, กล่อง)
            $table->integer('conversion_factor')->default(1)->after('sell_unit'); // 1 กล่อง = X เม็ด

            // Inventory Control
            $table->integer('reorder_point')->default(10)->after('min_stock');
            $table->integer('max_stock')->nullable()->after('reorder_point');
            $table->string('location')->nullable()->after('max_stock'); // Shelf/Bin location

            // Clinical Info (Pharmacy specific)
            $table->text('precautions')->nullable()->after('requires_prescription'); // ข้อควรระวัง
            $table->text('side_effects')->nullable()->after('precautions'); // ผลข้างเคียง
            $table->text('default_instructions')->nullable()->after('side_effects'); // วิธีใช้เบื้องต้น
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'barcode',
                'image_path',
                'drug_class',
                'manufacturer',
                'member_price',
                'vat_applicable',
                'base_unit',
                'sell_unit',
                'conversion_factor',
                'reorder_point',
                'max_stock',
                'location',
                'precautions',
                'side_effects',
                'default_instructions',
            ]);
        });
    }
};
