<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Admins (3 users)
        $admins = [
            ['first_name' => 'Admin', 'last_name' => 'User', 'gender' => 'male', 'email' => 'admin@example.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Connor', 'gender' => 'female', 'email' => 'sarah@example.com'],
            ['first_name' => 'John', 'last_name' => 'Doe', 'gender' => 'male', 'email' => 'john@example.com'],
        ];

        foreach ($admins as $index => $admin) {
            User::create([
                'first_name' => $admin['first_name'],
                'last_name' => $admin['last_name'],
                'email' => $admin['email'],
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'System Administrator',
                'employee_id' => 'ADM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'gender' => $admin['gender'],
                'birthdate' => '1985-01-01',
                'phone_number' => '081-111-111' . $index,
                'language' => 'en',
                'theme' => 'light',
            ]);
        }

        // 2. Staff (4 users)
        $staffs = [
            ['first_name' => 'Bob', 'last_name' => 'Brown', 'gender' => 'male'],
            ['first_name' => 'Charlie', 'last_name' => 'Davis', 'gender' => 'male'],
            ['first_name' => 'Diana', 'last_name' => 'Evans', 'gender' => 'female'],
            ['first_name' => 'Frank', 'last_name' => 'Green', 'gender' => 'male'],
        ];

        foreach ($staffs as $index => $staff) {
            User::create([
                'first_name' => $staff['first_name'],
                'last_name' => $staff['last_name'],
                'email' => strtolower($staff['first_name']) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'position' => 'Sales Staff',
                'employee_id' => 'STF-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'gender' => $staff['gender'],
                'birthdate' => '1995-05-15',
                'phone_number' => '082-222-222' . $index,
                'language' => 'th',
                'theme' => 'light',
            ]);
        }

        // 3. Pharmacists (3 users)
        $pharmacists = [
            ['first_name' => 'Alice', 'last_name' => 'Smith', 'gender' => 'female'],
            ['first_name' => 'Grace', 'last_name' => 'Hall', 'gender' => 'female'],
            ['first_name' => 'Henry', 'last_name' => 'King', 'gender' => 'male'],
        ];

        foreach ($pharmacists as $index => $phar) {
            User::create([
                'first_name' => $phar['first_name'],
                'last_name' => $phar['last_name'],
                'email' => strtolower($phar['first_name']) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'pharmacist', // Assuming 'pharmacist' role exists or is handled as 'staff' with position
                'position' => 'Pharmacist',
                'employee_id' => 'PHM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'pharmacist_license_id' => 'PH-' . rand(10000, 99999),
                'gender' => $phar['gender'],
                'birthdate' => '1990-08-20',
                'phone_number' => '083-333-333' . $index,
                'language' => 'th',
                'theme' => 'light',
            ]);
        }
    }
}
