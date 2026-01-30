<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itinerary>
 */
class ItineraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trek_id' => \App\Models\Trek::factory(),
            'day_number' => fake()->numberBetween(1, 15),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
