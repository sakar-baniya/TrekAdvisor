<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Passenger>
 */
class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trek_booking_id' => \App\Models\TrekBooking::factory(),
            'name' => fake()->name(),
            'passport_no' => fake()->bothify('??######'),
            'age' => fake()->numberBetween(18, 70),
        ];
    }
}
