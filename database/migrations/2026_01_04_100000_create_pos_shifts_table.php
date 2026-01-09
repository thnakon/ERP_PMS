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
        Schema::create('pos_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('expected_cash', 12, 2)->default(0);
            $table->decimal('closing_balance', 12, 2)->nullable();
            $table->decimal('variance', 12, 2)->nullable();
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_cash', 12, 2)->default(0);
            $table->decimal('total_card', 12, 2)->default(0);
            $table->decimal('total_transfer', 12, 2)->default(0);
            $table->decimal('total_qr', 12, 2)->default(0);
            $table->integer('transactions_count')->default(0);
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_shifts');
    }
};
