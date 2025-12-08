<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'hn_number' => 'MB-' . date('Y') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'birthdate' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'membership_tier' => $this->faker->randomElement(['Standard', 'Silver', 'Gold', 'Platinum']),
            'blood_group' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'points' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
