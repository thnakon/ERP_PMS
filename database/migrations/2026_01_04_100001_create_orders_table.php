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
        // Add new columns to existing orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pos_shift_id')) {
                $table->foreignId('pos_shift_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('discount');
            }
            if (!Schema::hasColumn('orders', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('orders', 'vat_amount')) {
                $table->decimal('vat_amount', 12, 2)->default(0)->after('tax');
            }
            if (!Schema::hasColumn('orders', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'partial', 'refunded'])->default('pending')->after('payment_details');
            }
            if (!Schema::hasColumn('orders', 'prescription_notes')) {
                $table->text('prescription_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'requires_prescription')) {
                $table->boolean('requires_prescription')->default(false)->after('prescription_notes');
            }
            if (!Schema::hasColumn('orders', 'pharmacist_id')) {
                $table->foreignId('pharmacist_id')->nullable()->after('requires_prescription')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('pharmacist_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'pos_shift_id',
                'discount_amount',
                'discount_percent',
                'vat_amount',
                'payment_details',
                'payment_status',
                'prescription_notes',
                'requires_prescription',
                'pharmacist_id',
                'completed_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
