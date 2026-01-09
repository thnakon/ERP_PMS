<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table or just allow any string
        // Since SQLite doesn't support ENUM, the column is already TEXT
        // This migration is only needed for MySQL

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE activity_logs MODIFY COLUMN action ENUM('login', 'logout', 'create', 'update', 'delete', 'print', 'export', 'download', 'restore', 'view', 'other') DEFAULT 'other'");
        }
        // For SQLite, no action needed as TEXT accepts any string
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE activity_logs MODIFY COLUMN action ENUM('login', 'logout', 'create', 'update', 'delete', 'print', 'export', 'view', 'other') DEFAULT 'other'");
        }
    }
};
