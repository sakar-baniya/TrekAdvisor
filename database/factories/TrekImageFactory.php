<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrekImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trek_id' => \App\Models\Trek::factory(),
            'path' => 'treks/VlRhKV4hHoMl3sgXJtSN9ljSTjKv97wNS4Q9oeIg.jpg',
            'is_primary' => false,
            'sort_order' => 1,
        ];
    }
}
