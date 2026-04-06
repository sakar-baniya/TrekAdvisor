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
            'name' => fake()->company() . ' Lodge',
            'location' => fake()->randomElement(['Kathmandu, Thamel', 'Pokhara, Lakeside', 'Lukla, Everest Region', 'Namche, Khumbu', 'Ghandruk, Annapurna']),
            'description' => fake()->paragraphs(2, true),
            'status' => 'active',
        ];
    }
}
