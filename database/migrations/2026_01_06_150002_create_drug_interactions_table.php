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
        Schema::create('drug_interactions', function (Blueprint $table) {
            $table->id();

            // Can be product_id or generic drug name
            $table->foreignId('drug_a_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('drug_a_name')->nullable()->comment('Generic name if no product');

            $table->foreignId('drug_b_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('drug_b_name')->nullable()->comment('Generic name if no product');

            // Interaction Details
            $table->enum('severity', ['minor', 'moderate', 'major', 'contraindicated'])->default('moderate');
            $table->text('description');
            $table->text('mechanism')->nullable();
            $table->text('management')->nullable()->comment('How to manage the interaction');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['drug_a_id', 'drug_b_id']);
            $table->index(['drug_a_name', 'drug_b_name']);
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_interactions');
    }
};
