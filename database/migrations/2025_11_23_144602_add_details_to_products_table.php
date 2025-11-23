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
        Schema::table('products', function (Blueprint $table) {
            $table->string('strength')->nullable()->after('generic_name');
            $table->string('dosage_form')->nullable()->after('strength');
            $table->string('registration_number')->nullable()->after('dosage_form');
            $table->decimal('cost_price', 10, 2)->nullable()->after('registration_number');
            $table->decimal('selling_price', 10, 2)->nullable()->after('cost_price');
            $table->string('primary_indication')->nullable()->after('selling_price');
            $table->string('regulatory_class')->nullable()->after('primary_indication');
            $table->string('image_path')->nullable()->after('regulatory_class');

            // Make unit_id nullable if it's not already, or just ensure we handle it. 
            // Since we can't easily change existing column to nullable without doctrine/dbal, 
            // we will assume we need to provide a unit_id or we will handle it in seeder.
            // For now, let's just add the new fields.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'strength',
                'dosage_form',
                'registration_number',
                'cost_price',
                'selling_price',
                'primary_indication',
                'regulatory_class',
                'image_path'
            ]);
        });
    }
};
