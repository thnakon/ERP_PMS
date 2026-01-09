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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who processed refund
            $table->decimal('amount', 12, 2);
            $table->text('reason');
            $table->enum('type', ['full', 'partial'])->default('full');
            $table->enum('status', ['pending', 'processed', 'rejected'])->default('pending');
            $table->boolean('stock_adjusted')->default(false); // Whether stock was returned
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
