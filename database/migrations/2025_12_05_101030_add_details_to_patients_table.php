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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('last_name');
            $table->string('membership_tier')->default('Standard')->after('phone'); // Standard, Silver, Gold, Platinum
            $table->json('chronic_diseases')->nullable()->after('membership_tier');
            $table->json('drug_allergies')->nullable()->after('chronic_diseases');
            $table->string('blood_group')->nullable()->after('drug_allergies');
            $table->integer('points')->default(0)->after('blood_group');
            $table->timestamp('last_visit_at')->nullable()->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'membership_tier',
                'chronic_diseases',
                'drug_allergies',
                'blood_group',
                'points',
                'last_visit_at'
            ]);
        });
    }
};
