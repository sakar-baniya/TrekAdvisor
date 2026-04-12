<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelRoom>
 */
class HotelRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => \App\Models\Hotel::factory(),
            'room_type' => fake()->randomElement(['Single', 'Double', 'Twin', 'Suite', 'Deluxe', 'Standard']),
            'price_per_night' => fake()->randomFloat(2, 2000, 8000),
            'total_rooms' => fake()->numberBetween(5, 50),
        ];
    }
}
