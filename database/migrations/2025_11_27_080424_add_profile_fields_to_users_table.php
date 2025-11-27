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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->unique()->after('id');
            $table->string('position')->nullable()->after('role');
            $table->string('pharmacist_license_id')->nullable()->after('position');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('profile_photo_path', 2048)->nullable()->after('phone_number');
            $table->string('language')->default('th')->after('profile_photo_path');
            $table->string('theme')->default('light')->after('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'position',
                'pharmacist_license_id',
                'phone_number',
                'profile_photo_path',
                'language',
                'theme',
            ]);
        });
    }
};
