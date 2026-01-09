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
        Schema::create('product_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('lot_number');
            $table->date('expiry_date');
            $table->date('manufactured_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('initial_quantity')->default(0); // Original quantity when received
            $table->decimal('cost_price', 10, 2)->default(0); // Cost per lot
            $table->string('supplier')->nullable();
            $table->date('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'expiry_date']); // For expiry tracking
            $table->index(['expiry_date']); // For alerts
            $table->unique(['product_id', 'lot_number']); // Unique lot per product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lots');
    }
};
