<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrekBooking>
 */
class TrekBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalPassengers = fake()->numberBetween(1, 4);
        $pricePerPerson = fake()->randomFloat(2, 500, 5000);
        $subtotal = $totalPassengers * $pricePerPerson;
        $discountPercent = fake()->randomElement([0, 5, 10]);
        $discountAmount = ($subtotal * $discountPercent) / 100;
        $totalPrice = $subtotal - $discountAmount;

        return [
            'user_id' => \App\Models\User::factory(),
            'departure_id' => \App\Models\Departure::factory(),
            'booking_reference' => 'TB-' . strtoupper(Str::random(8)),
            'total_passengers' => $totalPassengers,
            'price_per_person' => $pricePerPerson,
            'subtotal' => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ];
    }
}
