<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Main Admin
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'gender' => 'male',
            'birthdate' => '1990-01-01',
        ]);

        // 2. Additional Admins (3 more to make 4 total)
        $admins = [
            ['first_name' => 'Sarah', 'last_name' => 'Connor', 'gender' => 'female'],
            ['first_name' => 'John', 'last_name' => 'Doe', 'gender' => 'male'],
            ['first_name' => 'Alice', 'last_name' => 'Smith', 'gender' => 'female'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'first_name' => $admin['first_name'],
                'last_name' => $admin['last_name'],
                'email' => strtolower($admin['first_name']) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'gender' => $admin['gender'],
                'birthdate' => '1985-05-15',
            ]);
        }

        // 3. Staff (6 users)
        $staffs = [
            ['first_name' => 'Bob', 'last_name' => 'Brown', 'gender' => 'male'],
            ['first_name' => 'Charlie', 'last_name' => 'Davis', 'gender' => 'male'],
            ['first_name' => 'Diana', 'last_name' => 'Evans', 'gender' => 'female'],
            ['first_name' => 'Frank', 'last_name' => 'Green', 'gender' => 'male'],
            ['first_name' => 'Grace', 'last_name' => 'Hall', 'gender' => 'female'],
            ['first_name' => 'Henry', 'last_name' => 'King', 'gender' => 'male'],
        ];

        foreach ($staffs as $staff) {
            User::create([
                'first_name' => $staff['first_name'],
                'last_name' => $staff['last_name'],
                'email' => strtolower($staff['first_name']) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'gender' => $staff['gender'],
                'birthdate' => '1995-08-20',
            ]);
        }
    }
}
