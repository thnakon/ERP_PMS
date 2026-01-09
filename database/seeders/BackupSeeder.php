<?php

namespace Database\Seeders;

use App\Models\Backup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = User::whereIn('role', ['admin'])->pluck('id')->toArray();

        if (empty($adminUsers)) {
            $adminUsers = [1];
        }

        $backups = [];
        $now = Carbon::now();

        // Generate 20 backup records over the last 3 months
        for ($i = 0; $i < 20; $i++) {
            $createdAt = $now->copy()->subDays(rand(1, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $type = $i % 3 === 0 ? 'manual' : 'scheduled';
            $status = $i < 18 ? 'completed' : ($i === 18 ? 'failed' : 'in_progress');

            // Random file size between 50MB and 500MB
            $size = rand(50 * 1048576, 500 * 1048576);

            $backups[] = [
                'filename' => 'backup_' . $createdAt->format('Y-m-d_H-i-s') . '.zip',
                'path' => 'backups/backup_' . $createdAt->format('Y-m-d_H-i-s') . '.zip',
                'size' => $size,
                'type' => $type,
                'status' => $status,
                'notes' => $this->getRandomNote($type, $status),
                'created_by' => $adminUsers[array_rand($adminUsers)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // Sort by created_at descending
        usort($backups, fn($a, $b) => $b['created_at'] <=> $a['created_at']);

        foreach ($backups as $backup) {
            Backup::create($backup);
        }

        $this->command->info('Created 20 backup history records!');
    }

    private function getRandomNote(string $type, string $status): ?string
    {
        if ($status === 'failed') {
            $failedNotes = [
                'Disk space insufficient',
                'Database connection timeout',
                'Permission denied on backup directory',
            ];
            return $failedNotes[array_rand($failedNotes)];
        }

        if ($status === 'in_progress') {
            return 'Backup in progress...';
        }

        if ($type === 'manual') {
            $manualNotes = [
                'สำรองข้อมูลก่อนอัปเดตระบบ',
                'Backup before system maintenance',
                'สำรองข้อมูลประจำเดือน',
                'Manual backup by admin',
                null,
            ];
            return $manualNotes[array_rand($manualNotes)];
        }

        // Scheduled backups usually have no notes
        $scheduledNotes = [
            'Automatic daily backup',
            'Weekly scheduled backup',
            null,
            null,
            null,
        ];
        return $scheduledNotes[array_rand($scheduledNotes)];
    }
}
