<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GearItem>
 */
class GearItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalStock = fake()->numberBetween(10, 50);
        return [
            'name' => fake()->words(2, true),
            'type' => fake()->randomElement(['Clothing', 'Equipment', 'Footwear', 'Camping']),
            'daily_price' => fake()->randomFloat(2, 2, 50),
            'total_stock' => $totalStock,
            'available_stock' => $totalStock,
            'image' => fake()->imageUrl(640, 480, 'gear', true),
        ];
    }
}
