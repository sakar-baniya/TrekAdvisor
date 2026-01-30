<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelBooking>
 */
class HotelBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('+1 week', '+3 months');
        $numNights = fake()->numberBetween(1, 7);
        $checkOut = (clone $checkIn)->modify('+' . $numNights . ' nights');
        $pricePerNight = fake()->randomFloat(2, 20, 500);
        $numRooms = fake()->numberBetween(1, 2);
        $totalPrice = $pricePerNight * $numNights * $numRooms;

        return [
            'user_id' => \App\Models\User::factory(),
            'hotel_room_id' => \App\Models\HotelRoom::factory(),
            'booking_reference' => 'HB-' . strtoupper(Str::random(8)),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'num_rooms' => $numRooms,
            'num_nights' => $numNights,
            'price_per_night' => $pricePerNight,
            'total_price' => $totalPrice,
            'status' => 'Pending',
        ];
    }
}
