<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $actions = [
            'Login' => 'User logged in',
            'Updated Profile' => 'Changed phone number',
            'Updated Product' => 'Updated stock for Paracetamol',
            'Created Category' => 'Created new category: Vitamins',
            'Deleted Supplier' => 'Deleted supplier: Old Pharma Co.',
            'Updated Settings' => 'Changed theme to Dark Mode',
        ];

        foreach ($users as $user) {
            // Create 3-5 random logs for each user
            for ($i = 0; $i < rand(3, 5); $i++) {
                $action = array_rand($actions);
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => $action,
                    'description' => $actions[$action],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    'created_at' => now()->subHours(rand(1, 100)),
                ]);
            }
        }
    }
}
