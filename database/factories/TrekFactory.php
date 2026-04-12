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
            'base_price' => fake()->numberBetween(5000, 10000),
            'difficulty' => fake()->randomElement(['easy', 'moderate', 'difficult', 'extreme']),
            'duration_days' => fake()->numberBetween(5, 18),
            'max_altitude' => fake()->numberBetween(2500, 5600),
            'status' => 'active',
        ];
    }
}
