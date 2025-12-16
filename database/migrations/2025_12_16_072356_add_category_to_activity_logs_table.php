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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('category')->default('system')->after('action'); // sales, inventory, system, security, user
            $table->string('status')->default('success')->after('user_agent'); // success, error, warning
            $table->string('subject_type')->nullable()->after('description'); // Model class name
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type'); // Model ID
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['category', 'status', 'subject_type', 'subject_id']);
        });
    }
};
