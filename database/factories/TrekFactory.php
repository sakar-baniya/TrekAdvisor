<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrekFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'base_price' => fake()->randomFloat(2, 500, 5000),
            'difficulty' => fake()->randomElement(['Easy', 'Moderate', 'Difficult', 'Extreme']),
            'duration_days' => fake()->numberBetween(5, 18),
            'max_altitude' => fake()->numberBetween(1800, 5600),
            'image' => fake()->imageUrl(640, 480, 'nature', true),
            'status' => 'Active',
        ];
    }
}
