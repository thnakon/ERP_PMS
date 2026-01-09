<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// LINE Notification Schedules
// Daily summary at 8:00 AM
Schedule::command('notifications:send-line --type=daily')
    ->dailyAt('08:00')
    ->timezone('Asia/Bangkok')
    ->description('Send daily LINE notification summary');

// Check low stock every 4 hours
Schedule::command('notifications:send-line --type=low_stock')
    ->everyFourHours()
    ->timezone('Asia/Bangkok')
    ->description('Send low stock LINE alerts');

// Check expiring products daily at 9:00 AM
Schedule::command('notifications:send-line --type=expiring')
    ->dailyAt('09:00')
    ->timezone('Asia/Bangkok')
    ->description('Send expiring product LINE alerts');

// Check refill reminders daily at 9:30 AM
Schedule::command('notifications:send-line --type=refill')
    ->dailyAt('09:30')
    ->timezone('Asia/Bangkok')
    ->description('Send refill reminder LINE alerts');

// Automatic Database Backup
$backupTime = \App\Models\HardwareSetting::get('backup_time', '02:00');
$backupSchedule = \App\Models\HardwareSetting::get('backup_schedule', 'daily');

$backupTask = Schedule::command('app:backup-db');

if ($backupSchedule === 'weekly') {
    $backupTask->weeklyOn(0, $backupTime);
} elseif ($backupSchedule === 'monthly') {
    $backupTask->monthlyOn(1, $backupTime);
} else {
    $backupTask->dailyAt($backupTime);
}

$backupTask->timezone('Asia/Bangkok')
    ->description('Automatic database backup');
