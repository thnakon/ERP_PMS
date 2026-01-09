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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->comment('Pharmacist who dispensed');

            // Doctor Information
            $table->string('doctor_name');
            $table->string('doctor_license_no')->nullable();
            $table->string('hospital_clinic')->nullable();
            $table->string('doctor_phone')->nullable();

            // Prescription Details
            $table->date('prescription_date');
            $table->date('expiry_date')->nullable()->comment('Prescription validity');
            $table->text('diagnosis')->nullable();
            $table->text('notes')->nullable();

            // Status
            $table->enum('status', ['pending', 'dispensed', 'partially_dispensed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('dispensed_at')->nullable();

            // Refill Management
            $table->integer('refill_allowed')->default(0)->comment('Number of refills allowed');
            $table->integer('refill_count')->default(0)->comment('Number of times refilled');
            $table->date('next_refill_date')->nullable();
            $table->boolean('refill_reminder_sent')->default(false);

            // Linked Order
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['prescription_date']);
            $table->index(['next_refill_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
