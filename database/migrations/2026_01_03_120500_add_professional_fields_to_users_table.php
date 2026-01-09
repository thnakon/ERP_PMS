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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'pharmacist_license_no')) {
                $table->string('pharmacist_license_no')->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'license_expiry')) {
                $table->date('license_expiry')->nullable()->after('pharmacist_license_no');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended', 'resigned'])->default('active')->after('license_expiry');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'hired_date')) {
                $table->date('hired_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable()->after('hired_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'username',
                'avatar',
                'position',
                'pharmacist_license_no',
                'license_expiry',
                'status',
                'phone',
                'hired_date',
                'notes'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
