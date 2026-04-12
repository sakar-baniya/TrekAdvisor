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
        $duration = rand(5, 18);
        $endDate = (clone $startDate)->modify('+' . $duration . ' days');

        return [
            'trek_id' => \App\Models\Trek::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => fake()->numberBetween(5000, 10000),
            'capacity' => fake()->numberBetween(10, 24),
            'booked_seats' => 0,
            'status' => 'available',
        ];
    }
}
