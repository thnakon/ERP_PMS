<?php

namespace App\Console\Commands;

use App\Models\Backup;
use App\Models\HardwareSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-db {--force : Force backup even if auto-backup is disabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup based on settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $autoEnabled = HardwareSetting::get('backup_auto_enabled', false);

        if (!$autoEnabled && !$force) {
            $this->info('Automatic backup is disabled.');
            return 0;
        }

        $this->info('Starting database backup...');

        try {
            $filename = 'backup_' . date('Y-m-d_His') . '_auto.sql';
            $backupPath = storage_path('app/backups');

            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $fullPath = $backupPath . '/' . $filename;

            // Get database credentials
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Try mysqldump first
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );

            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode !== 0) {
                $this->warn('mysqldump failed, falling back to PHP-based backup.');
                $this->createPhpBackup($fullPath, $database);
            }

            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;

            // Save record
            Backup::create([
                'filename' => $filename,
                'path' => 'backups/' . $filename,
                'size' => $fileSize,
                'type' => 'scheduled',
                'status' => 'completed',
                'notes' => 'Automatic scheduled backup',
                'created_by' => null, // Created by system
            ]);

            $this->info("Backup created successfully: {$filename}");
            Log::info("Automatic database backup completed: {$filename}");

            // Handle retention
            $this->cleanupOldBackups();

            return 0;
        } catch (\Exception $e) {
            Log::error("Automatic database backup failed: " . $e->getMessage());
            $this->error("Backup failed: " . $e->getMessage());

            Backup::create([
                'filename' => 'failed_auto_backup_' . date('Y-m-d_Hi'),
                'path' => '',
                'size' => 0,
                'type' => 'scheduled',
                'status' => 'failed',
                'notes' => $e->getMessage(),
                'created_by' => null,
            ]);

            return 1;
        }
    }

    /**
     * Create PHP-based backup (fallback)
     */
    protected function createPhpBackup(string $path, string $database): void
    {
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;

        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . $database . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Get create table statement
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get table data
            $rows = DB::table($tableName)->get();

            if ($rows->count() > 0) {
                $columns = array_keys((array) $rows->first());
                $columnList = '`' . implode('`, `', $columns) . '`';

                foreach ($rows as $row) {
                    $values = array_map(function ($value) {
                        if (is_null($value)) {
                            return 'NULL';
                        }
                        return "'" . addslashes($value) . "'";
                    }, (array) $row);

                    $sql .= "INSERT INTO `{$tableName}` ({$columnList}) VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        file_put_contents($path, $sql);
    }

    /**
     * Delete backups older than retention days
     */
    protected function cleanupOldBackups()
    {
        $retentionDays = HardwareSetting::get('backup_retention_days', 30);
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        $oldBackups = Backup::where('created_at', '<', $cutoffDate)->get();

        foreach ($oldBackups as $backup) {
            $fullPath = storage_path('app/' . $backup->path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $backup->delete();
            $this->info("Deleted old backup: {$backup->filename}");
        }
    }
}
