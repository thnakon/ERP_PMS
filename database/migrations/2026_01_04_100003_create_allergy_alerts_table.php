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
        Schema::create('allergy_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Staff who acknowledged

            $table->string('allergy_type'); // Drug name or ingredient
            $table->string('alert_level')->default('warning'); // warning, danger, critical
            $table->text('message');
            $table->boolean('acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('pharmacist_notes')->nullable();
            $table->enum('action_taken', ['proceed', 'substitute', 'cancel'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_alerts');
    }
};
