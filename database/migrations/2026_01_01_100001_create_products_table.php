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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('name_th')->nullable(); // Thai name
            $table->string('generic_name')->nullable(); // Drug generic name (important for pharmacy)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->integer('stock_qty')->default(0);
            $table->integer('min_stock')->default(10); // Minimum stock alert threshold
            $table->string('unit')->default('pcs'); // Box, Pcs, Bottle, etc.
            $table->boolean('requires_prescription')->default(false); // Pharmacy specific
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'sku', 'generic_name']); // For search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
