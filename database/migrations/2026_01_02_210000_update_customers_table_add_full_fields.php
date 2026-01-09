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
        Schema::table('customers', function (Blueprint $table) {
            // Personal Info
            if (!Schema::hasColumn('customers', 'nickname')) {
                $table->string('nickname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('customers', 'national_id')) {
                $table->string('national_id', 20)->nullable()->after('birth_date');
            }

            // Contact Info
            if (!Schema::hasColumn('customers', 'line_id')) {
                $table->string('line_id')->nullable()->after('address');
            }

            // Medical Records - Critical for Drug Safety
            if (!Schema::hasColumn('customers', 'drug_allergies')) {
                $table->json('drug_allergies')->nullable()->after('allergy_notes');
            }
            if (!Schema::hasColumn('customers', 'chronic_diseases')) {
                $table->json('chronic_diseases')->nullable()->after('drug_allergies');
            }
            if (!Schema::hasColumn('customers', 'pregnancy_status')) {
                $table->enum('pregnancy_status', ['none', 'pregnant', 'breastfeeding'])->default('none')->after('chronic_diseases');
            }

            // Loyalty Program
            if (!Schema::hasColumn('customers', 'points_balance')) {
                $table->decimal('points_balance', 10, 2)->default(0)->after('pregnancy_status');
            }
            if (!Schema::hasColumn('customers', 'member_tier')) {
                $table->enum('member_tier', ['regular', 'silver', 'gold', 'platinum'])->default('regular')->after('points_balance');
            }
            if (!Schema::hasColumn('customers', 'member_since')) {
                $table->date('member_since')->nullable()->after('member_tier');
            }

            // Extra
            if (!Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable()->after('medical_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $columns = ['nickname', 'national_id', 'line_id', 'drug_allergies', 'chronic_diseases', 'pregnancy_status', 'points_balance', 'member_tier', 'member_since', 'notes'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
