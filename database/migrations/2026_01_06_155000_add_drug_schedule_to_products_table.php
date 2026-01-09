<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drug Schedule (ประเภทยาตามกฎหมาย):
     * - normal: ยาสามัญประจำบ้าน
     * - dangerous: ยาอันตราย (ต้องขายโดยเภสัชกร)
     * - specially_controlled: ยาควบคุมพิเศษ (ต้องมีใบสั่งแพทย์)
     * - narcotic: ยาเสพติดให้โทษ
     * - psychotropic: วัตถุออกฤทธิ์ต่อจิตและประสาท
     */
    public function up(): void
    {
        // Add drug_schedule column if not exists
        if (!Schema::hasColumn('products', 'drug_schedule')) {
            Schema::table('products', function (Blueprint $table) {
                $table->enum('drug_schedule', [
                    'normal',           // ยาสามัญประจำบ้าน
                    'dangerous',        // ยาอันตราย
                    'specially_controlled', // ยาควบคุมพิเศษ
                    'narcotic',         // ยาเสพติดให้โทษ
                    'psychotropic'      // วัตถุออกฤทธิ์ต่อจิตประสาท
                ])->default('normal')->after('is_active');
            });
        }

        // Add requires_pharmacist_approval column if not exists
        if (!Schema::hasColumn('products', 'requires_pharmacist_approval')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('requires_pharmacist_approval')->default(false)->after('requires_prescription');
            });
        }

        // Add fda_registration_no column if not exists
        if (!Schema::hasColumn('products', 'fda_registration_no')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('fda_registration_no')->nullable()->after('requires_pharmacist_approval');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'drug_schedule')) {
                $table->dropColumn('drug_schedule');
            }
            if (Schema::hasColumn('products', 'requires_pharmacist_approval')) {
                $table->dropColumn('requires_pharmacist_approval');
            }
            if (Schema::hasColumn('products', 'fda_registration_no')) {
                $table->dropColumn('fda_registration_no');
            }
        });
    }
};
