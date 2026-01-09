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
            $table->text('description_th')->nullable()->after('description');
            $table->text('precautions_th')->nullable()->after('precautions');
            $table->text('side_effects_th')->nullable()->after('side_effects');
            $table->text('default_instructions_th')->nullable()->after('default_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description_th', 'precautions_th', 'side_effects_th', 'default_instructions_th']);
        });
    }
};
