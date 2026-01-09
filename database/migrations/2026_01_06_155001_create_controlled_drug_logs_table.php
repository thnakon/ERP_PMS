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
        Schema::create('controlled_drug_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_number')->unique(); // เลขที่บันทึก

            // Product & Quantity
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->foreignId('product_lot_id')->nullable()->constrained()->onDelete('set null');

            // Transaction Type
            $table->enum('transaction_type', [
                'sale',         // ขาย
                'dispense',     // จ่ายตามใบสั่งยา
                'receive',      // รับเข้า
                'return',       // รับคืน
                'dispose',      // ทำลาย
                'transfer',     // โอนย้าย
            ])->default('sale');

            // Customer/Patient Info (Required by law for controlled drugs)
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name'); // ชื่อผู้ซื้อ/ผู้รับยา
            $table->string('customer_id_card')->nullable(); // เลขบัตรประชาชน
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_age')->nullable(); // อายุ

            // Prescription Info (for specially controlled drugs)
            $table->foreignId('prescription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('prescription_number')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_license_no')->nullable();
            $table->string('hospital_clinic')->nullable();

            // Purpose/Indication
            $table->text('purpose')->nullable(); // วัตถุประสงค์การใช้ยา
            $table->text('indication')->nullable(); // ข้อบ่งใช้

            // Approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Staff
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'transaction_type']);
            $table->index('status');
            $table->index('created_at');
            $table->index('customer_id_card');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controlled_drug_logs');
    }
};
