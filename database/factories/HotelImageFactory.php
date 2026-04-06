<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HotelImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hotel_id' => \App\Models\Hotel::factory(),
            'path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=1200',
            'sort_order' => 1,
        ];
    }
}
