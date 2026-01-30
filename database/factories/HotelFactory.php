<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => \App\Models\User::factory(),
            'name' => fake()->company() . ' Hotel',
            'location' => fake()->city(),
            'description' => fake()->paragraphs(2, true),
            'image' => fake()->imageUrl(640, 480, 'city', true),
            'status' => 'Active',
        ];
    }
}
