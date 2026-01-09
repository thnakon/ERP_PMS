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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();

            // Pharmacy Critical: Drug Allergy Information
            $table->json('allergy_notes')->nullable(); // Array of allergic drugs/ingredients
            $table->text('medical_notes')->nullable(); // Other medical conditions

            $table->decimal('total_spent', 12, 2)->default(0);
            $table->integer('visit_count')->default(0);
            $table->timestamp('last_visit_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'phone']); // For search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
