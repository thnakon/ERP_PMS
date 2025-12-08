<?php

namespace Database\Seeders;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $tiers = ['Standard', 'Silver', 'Gold', 'Platinum'];
        $chronic_diseases = ['Hypertension', 'Diabetes', 'Asthma', 'High Cholesterol', 'None'];
        $drug_allergies = ['Penicillin', 'Sulfa', 'Aspirin', 'NSAIDs', 'None'];
        $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        $patients = [
            [
                'first_name' => 'Somchai',
                'last_name' => 'Meesuk',
                'hn_number' => 'MB-2023-889',
                'gender' => 'Male',
                'birthdate' => '1980-05-19',
                'phone' => '081-999-8888',
                'email' => 'somchai@example.com',
                'membership_tier' => 'Gold',
                'chronic_diseases' => ['Hypertension'],
                'drug_allergies' => ['Penicillin'],
                'blood_group' => 'O+',
                'points' => 1250,
                'last_visit_at' => Carbon::yesterday(),
            ],
            [
                'first_name' => 'Malee',
                'last_name' => 'Jaiyen',
                'hn_number' => 'MB-2024-002',
                'gender' => 'Female',
                'birthdate' => '1962-10-04',
                'phone' => '089-777-6655',
                'email' => 'malee.j@example.com',
                'membership_tier' => 'Platinum',
                'chronic_diseases' => ['Diabetes', 'Kidney Disease'],
                'drug_allergies' => null,
                'blood_group' => 'B+',
                'points' => 5400,
                'last_visit_at' => Carbon::now()->subDays(20),
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'hn_number' => 'MB-2025-110',
                'gender' => 'Male',
                'birthdate' => '1995-02-14',
                'phone' => '090-111-2233',
                'email' => 'james.w@example.com',
                'membership_tier' => 'Silver',
                'chronic_diseases' => null,
                'drug_allergies' => null,
                'blood_group' => 'A-',
                'points' => 300,
                'last_visit_at' => Carbon::now()->subMonths(1),
            ],
        ];

        // Generate 12 more random patients
        for ($i = 0; $i < 12; $i++) {
            $hasDisease = rand(0, 1) === 1;
            $hasAllergy = rand(0, 1) === 1;

            $diseases = $hasDisease ? [$chronic_diseases[array_rand($chronic_diseases)]] : null;
            if ($diseases && $diseases[0] === 'None') $diseases = null;

            $allergies = $hasAllergy ? [$drug_allergies[array_rand($drug_allergies)]] : null;
            if ($allergies && $allergies[0] === 'None') $allergies = null;

            $patients[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'hn_number' => 'MB-2025-' . str_pad($i + 111, 3, '0', STR_PAD_LEFT),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'birthdate' => $faker->date('Y-m-d', '2000-01-01'),
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'membership_tier' => $faker->randomElement($tiers),
                'chronic_diseases' => $diseases,
                'drug_allergies' => $allergies,
                'blood_group' => $faker->randomElement($blood_groups),
                'points' => rand(0, 5000),
                'last_visit_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];
        }

        foreach ($patients as $patient) {
            Patient::updateOrCreate(
                ['hn_number' => $patient['hn_number']],
                $patient
            );
        }
    }
}
