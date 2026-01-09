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
        if (Schema::hasTable('order_items')) {
            // Add new columns if table exists
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'cost_price')) {
                    $table->decimal('cost_price', 12, 2)->default(0)->after('unit_price');
                }
                if (!Schema::hasColumn('order_items', 'discount_amount')) {
                    $table->decimal('discount_amount', 12, 2)->default(0)->after('cost_price');
                }
                if (!Schema::hasColumn('order_items', 'discount_percent')) {
                    $table->decimal('discount_percent', 5, 2)->default(0)->after('discount_amount');
                }
                if (!Schema::hasColumn('order_items', 'vat_amount')) {
                    $table->decimal('vat_amount', 12, 2)->default(0)->after('subtotal');
                }
                if (!Schema::hasColumn('order_items', 'total')) {
                    $table->decimal('total', 12, 2)->default(0)->after('vat_amount');
                }
                if (!Schema::hasColumn('order_items', 'requires_prescription')) {
                    $table->boolean('requires_prescription')->default(false)->after('total');
                }
                if (!Schema::hasColumn('order_items', 'instructions')) {
                    $table->text('instructions')->nullable()->after('requires_prescription');
                }
                if (!Schema::hasColumn('order_items', 'notes')) {
                    $table->text('notes')->nullable()->after('instructions');
                }
            });
        } else {
            // Create table if not exists
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_lot_id')->nullable()->constrained()->onDelete('set null');

                $table->string('product_name'); // Snapshot at time of sale
                $table->string('product_sku');
                $table->integer('quantity');
                $table->decimal('unit_price', 12, 2);
                $table->decimal('cost_price', 12, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('discount_percent', 5, 2)->default(0);
                $table->decimal('subtotal', 12, 2);
                $table->decimal('vat_amount', 12, 2)->default(0);
                $table->decimal('total', 12, 2);

                $table->boolean('requires_prescription')->default(false);
                $table->text('instructions')->nullable(); // Dosage instructions
                $table->text('notes')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
