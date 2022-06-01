<?php

namespace Database\Factories;

use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'avatar' => '',
            'gender' => rand(1, 2),
            'birthday' => $this->faker->dateTimeBetween(now()->subYears(60), now()->subYears(18)),
            'tel' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->boolean(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
