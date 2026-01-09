<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\HardwareSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    /**
     * Display backup settings page
     */
    public function index()
    {
        $backupSettings = HardwareSetting::getByGroup('backup');
        $backups = Backup::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get database info
        $dbInfo = $this->getDatabaseInfo();

        return view('settings.backup', compact('backupSettings', 'backups', 'dbInfo'));
    }

    /**
     * Update backup settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'backup_schedule' => ['required', 'in:daily,weekly,monthly'],
            'backup_time' => ['required', 'date_format:H:i'],
            'backup_retention_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $settings = [
            'backup_auto_enabled' => $request->has('backup_auto_enabled') ? '1' : '0',
            'backup_schedule' => $request->input('backup_schedule', 'daily'),
            'backup_time' => $request->input('backup_time', '02:00'),
            'backup_retention_days' => $request->input('backup_retention_days', '30'),
            'backup_include_files' => $request->has('backup_include_files') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            HardwareSetting::set($key, $value);
        }

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าสำรองข้อมูล'
        );

        return back()->with('success', __('backup.settings_updated'));
    }

    /**
     * Create manual backup
     */
    public function createBackup(Request $request)
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups');

            // Create backups directory if not exists
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

            // Create backup using mysqldump
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
                // Fallback: Try to create a PHP-based backup
                $this->createPhpBackup($fullPath, $database);
            }

            // Get file size
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;

            // Save backup record
            $backup = Backup::create([
                'filename' => $filename,
                'path' => 'backups/' . $filename,
                'size' => $fileSize,
                'type' => 'manual',
                'status' => 'completed',
                'notes' => $request->input('notes'),
                'created_by' => Auth::id(),
            ]);

            ActivityLog::log(
                action: 'create',
                module: 'Backup',
                description: 'สร้างข้อมูลสำรอง: ' . $filename
            );

            return back()->with('success', __('backup.backup_created'));
        } catch (\Exception $e) {
            // Log failed backup
            Backup::create([
                'filename' => $filename ?? 'failed_backup',
                'path' => '',
                'size' => 0,
                'type' => 'manual',
                'status' => 'failed',
                'notes' => $e->getMessage(),
                'created_by' => Auth::id(),
            ]);

            return back()->with('error', __('backup.backup_failed') . ': ' . $e->getMessage());
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
     * Download backup file
     */
    public function download(Backup $backup)
    {
        $fullPath = storage_path('app/' . $backup->path);

        if (!file_exists($fullPath)) {
            return back()->with('error', __('backup.file_not_found'));
        }

        ActivityLog::log(
            action: 'download',
            module: 'Backup',
            description: 'ดาวน์โหลดข้อมูลสำรอง: ' . $backup->filename
        );

        return response()->download($fullPath, $backup->filename);
    }

    /**
     * Delete backup
     */
    public function destroy(Backup $backup)
    {
        $filename = $backup->filename;
        $fullPath = storage_path('app/' . $backup->path);

        // Delete file
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Delete record
        $backup->delete();

        ActivityLog::log(
            action: 'delete',
            module: 'Backup',
            description: 'ลบข้อมูลสำรอง: ' . $filename
        );

        return back()->with('success', __('backup.backup_deleted'));
    }

    /**
     * Get database information
     */
    protected function getDatabaseInfo(): array
    {
        try {
            $database = config('database.connections.mysql.database');

            // Get database size
            $sizeResult = DB::select("
                SELECT 
                    SUM(data_length + index_length) as size
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$database]);

            $size = $sizeResult[0]->size ?? 0;

            // Get table count
            $tableCount = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$database]);

            // Get record count from important tables
            $recordCounts = [];
            $importantTables = ['users', 'products', 'orders', 'customers'];

            foreach ($importantTables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $recordCounts[$table] = $count;
                } catch (\Exception $e) {
                    $recordCounts[$table] = 0;
                }
            }

            return [
                'name' => $database,
                'size' => $this->formatBytes($size),
                'size_bytes' => $size,
                'table_count' => $tableCount[0]->count ?? 0,
                'record_counts' => $recordCounts,
            ];
        } catch (\Exception $e) {
            return [
                'name' => config('database.connections.mysql.database'),
                'size' => 'N/A',
                'size_bytes' => 0,
                'table_count' => 0,
                'record_counts' => [],
            ];
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }
}
