<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trek>
 */
class TrekFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        return [
            'title' => ucfirst($title),
            'slug' => \Illuminate\Support\Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'base_price' => fake()->randomFloat(2, 500, 5000),
            'difficulty' => fake()->randomElement(['Easy', 'Moderate', 'Difficult', 'Extreme']),
            'image' => fake()->imageUrl(640, 480, 'nature', true),
            'status' => 'Active',
        ];
    }
}
