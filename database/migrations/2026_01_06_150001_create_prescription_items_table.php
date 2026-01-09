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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Medication Details
            $table->decimal('quantity', 10, 2);
            $table->string('dosage')->comment('e.g., 500mg, 1 tablet');
            $table->string('frequency')->comment('e.g., 3 times daily, every 8 hours');
            $table->string('duration')->nullable()->comment('e.g., 7 days, 2 weeks');
            $table->string('route')->nullable()->comment('e.g., oral, topical, injection');
            $table->text('instructions')->nullable()->comment('Special instructions');

            // Dispensing
            $table->decimal('quantity_dispensed', 10, 2)->default(0);
            $table->boolean('is_dispensed')->default(false);

            // Pricing (snapshot at time of prescription)
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
