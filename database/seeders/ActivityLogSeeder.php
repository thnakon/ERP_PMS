<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Creating a sample user first.');
            $user = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@pharmacy.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
            $users = collect([$user]);
        }

        $activities = [
            // Sales Activities
            ['action' => 'Created Invoice #INV-2025-001', 'category' => 'sales', 'description' => 'Sold: Paracetamol 500mg x10, Vitamin C x5'],
            ['action' => 'Created Invoice #INV-2025-002', 'category' => 'sales', 'description' => 'Sold: Amoxicillin x2, Ibuprofen x3'],
            ['action' => 'Void Invoice #INV-2024-998', 'category' => 'sales', 'description' => 'Customer requested refund'],
            ['action' => 'Payment Received', 'category' => 'sales', 'description' => 'Received 2,500 baht cash payment for Invoice #INV-2025-001'],

            // Inventory Activities
            ['action' => 'Stock Adjustment', 'category' => 'inventory', 'description' => "Updated Tylenol 500mg qty: 50 -> 45 (Damaged)"],
            ['action' => 'Stock Received', 'category' => 'inventory', 'description' => 'Received 100 units of Paracetamol from Supplier ABC'],
            ['action' => 'Low Stock Alert', 'category' => 'inventory', 'description' => "Aspirin 100mg stock is below minimum threshold (5 remaining)", 'status' => 'warning'],
            ['action' => 'Stock Transfer', 'category' => 'inventory', 'description' => 'Transferred 20 units of Vitamin B to Branch 2'],
            ['action' => 'Expiry Alert', 'category' => 'inventory', 'description' => '15 products expiring within 30 days', 'status' => 'warning'],

            // User Activities
            ['action' => 'New Patient Registered', 'category' => 'user', 'description' => "Added Mrs. Malee Jaiyen to system"],
            ['action' => 'Patient Updated', 'category' => 'user', 'description' => "Updated allergy info for Mr. Somchai"],
            ['action' => 'New Employee Added', 'category' => 'user', 'description' => "Added Wipawee S. as Pharmacist Assistant"],
            ['action' => 'Employee Role Changed', 'category' => 'user', 'description' => "Changed Napat K. role from Staff to Pharmacist"],

            // System Activities
            ['action' => 'Daily Backup Completed', 'category' => 'system', 'description' => 'Database backup file generated (24MB)'],
            ['action' => 'System Update', 'category' => 'system', 'description' => 'Updated to version 2.5.1'],
            ['action' => 'Report Generated', 'category' => 'system', 'description' => 'Monthly Sales Report - November 2024 generated'],
            ['action' => 'Email Sent', 'category' => 'system', 'description' => 'Sent expiry notification to suppliers'],

            // Security Activities
            ['action' => 'Login Successful', 'category' => 'security', 'description' => 'User logged in from 192.168.1.100'],
            ['action' => 'Failed Login Attempt', 'category' => 'security', 'description' => 'Incorrect password entered 3 times', 'status' => 'error'],
            ['action' => 'Password Changed', 'category' => 'security', 'description' => 'User changed their password'],
            ['action' => 'Logout', 'category' => 'security', 'description' => 'User logged out'],
            ['action' => 'Session Expired', 'category' => 'security', 'description' => 'User session timed out after 30 minutes', 'status' => 'warning'],
        ];

        // Create logs over the past week
        $now = Carbon::now();

        foreach ($activities as $index => $activity) {
            // Spread activities across the past 7 days
            $daysAgo = $index % 7;
            $hoursAgo = rand(0, 23);
            $minutesAgo = rand(0, 59);

            $createdAt = $now->copy()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes($minutesAgo);

            ActivityLog::create([
                'user_id' => $users->random()->id,
                'action' => $activity['action'],
                'category' => $activity['category'],
                'description' => $activity['description'],
                'status' => $activity['status'] ?? 'success',
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Created ' . count($activities) . ' activity log entries.');
    }
}
