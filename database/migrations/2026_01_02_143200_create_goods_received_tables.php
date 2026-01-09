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
        // Goods Received Table
        Schema::create('goods_received', function (Blueprint $table) {
            $table->id();
            $table->string('gr_number')->unique();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // Received by

            $table->string('invoice_no')->nullable(); // Supplier invoice/DO number
            $table->date('received_date');

            $table->enum('status', ['pending', 'partial', 'completed'])->default('pending');

            // Totals
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Goods Received Items Table
        Schema::create('goods_received_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_received_id');
            $table->foreign('goods_received_id')->references('id')->on('goods_received')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('purchase_order_item_id')->nullable()->constrained()->onDelete('set null');

            $table->decimal('ordered_qty', 10, 2)->default(0);
            $table->decimal('received_qty', 10, 2);
            $table->decimal('rejected_qty', 10, 2)->default(0);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('line_total', 12, 2);

            // Lot Information
            $table->string('lot_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufactured_date')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_items');
        Schema::dropIfExists('goods_received');
    }
};
