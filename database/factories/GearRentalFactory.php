<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GearRental>
 */
class GearRentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+1 month');
        $numDays = fake()->numberBetween(3, 15);
        $endDate = (clone $startDate)->modify('+' . $numDays . ' days');
        $dailyPrice = fake()->randomFloat(2, 2, 50);
        $quantity = fake()->numberBetween(1, 3);
        $totalPrice = $dailyPrice * $numDays * $quantity;

        return [
            'user_id' => \App\Models\User::factory(),
            'gear_item_id' => \App\Models\GearItem::factory(),
            'rental_reference' => 'GR-' . strtoupper(Str::random(8)),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'quantity' => $quantity,
            'num_days' => $numDays,
            'daily_price' => $dailyPrice,
            'total_price' => $totalPrice,
            'status' => 'Pending',
        ];
    }
}
