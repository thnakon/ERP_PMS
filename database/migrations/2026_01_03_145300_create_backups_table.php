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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->unsignedBigInteger('size')->default(0); // Size in bytes
            $table->string('type')->default('manual'); // manual, scheduled
            $table->string('status')->default('completed'); // completed, failed, in_progress
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Add backup settings to hardware_settings table
        $backupSettings = [
            ['key' => 'backup_auto_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'backup', 'description' => 'Enable automatic backup'],
            ['key' => 'backup_schedule', 'value' => 'daily', 'type' => 'string', 'group' => 'backup', 'description' => 'Backup schedule (daily/weekly/monthly)'],
            ['key' => 'backup_time', 'value' => '02:00', 'type' => 'string', 'group' => 'backup', 'description' => 'Time to run backup'],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'integer', 'group' => 'backup', 'description' => 'Days to keep backups'],
            ['key' => 'backup_include_files', 'value' => '1', 'type' => 'boolean', 'group' => 'backup', 'description' => 'Include uploaded files in backup'],
        ];

        foreach ($backupSettings as $setting) {
            DB::table('hardware_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
        DB::table('hardware_settings')->where('group', 'backup')->delete();
    }
};
