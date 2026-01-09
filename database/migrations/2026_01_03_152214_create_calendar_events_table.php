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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();

            // Event Type
            $table->enum('type', ['shift', 'expiry', 'appointment', 'holiday', 'reminder', 'other'])->default('other');

            // Event Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('color', 20)->default('#3B82F6'); // Blue default

            // References
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_lot_id')->nullable()->constrained('product_lots')->nullOnDelete();

            // Created by
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Status
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();

            // Recurring
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->date('recurrence_end')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type', 'start_time']);
            $table->index(['staff_id', 'start_time']);
            $table->index(['status', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
