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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->unique(); // Adj No.
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_lot_id')->nullable()->constrained('product_lots')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Auditor

            $table->enum('type', ['increase', 'decrease', 'set']);
            $table->integer('quantity'); // Adjustment amount
            $table->integer('before_quantity');
            $table->integer('after_quantity');

            $table->string('reason'); // Damaged, Expired, etc.
            $table->text('notes')->nullable();

            $table->timestamp('adjusted_at')->useCurrent();
            $table->timestamps();

            $table->index(['product_id', 'adjusted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
