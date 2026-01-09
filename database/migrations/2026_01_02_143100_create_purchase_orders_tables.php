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
        // Purchase Orders Table
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // Created by

            $table->date('order_date');
            $table->date('expected_date')->nullable();

            $table->enum('status', ['draft', 'sent', 'partial', 'completed', 'cancelled'])->default('draft');

            // Totals
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Purchase Order Items Table
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');

            $table->decimal('ordered_qty', 10, 2);
            $table->decimal('received_qty', 10, 2)->default(0);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('line_total', 12, 2);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
