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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_lot_id')->nullable()->constrained('product_lots')->nullOnDelete(); // Track which lot was used
            $table->string('product_name'); // Snapshot of product name at time of sale
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
