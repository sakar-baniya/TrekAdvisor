<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departure>
 */
class DepartureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 month', '+6 months');
        $endDate = (clone $startDate)->modify('+' . rand(5, 15) . ' days');

        return [
            'trek_id' => \App\Models\Trek::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => fake()->randomFloat(2, 500, 5000),
            'capacity' => fake()->numberBetween(10, 30),
            'booked_seats' => 0,
            'status' => 'Available',
        ];
    }
}
