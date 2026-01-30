<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a few users with different roles (Admin, Hotel Owner, Customer)
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@trekadvisor.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $hotelOwner = User::factory()->create([
            'name' => 'Hotel Owner',
            'email' => 'owner@trekadvisor.com',
            'password' => bcrypt('password'),
            'role' => 'hotel_owner',
        ]);

        $customer = User::factory()->create([
            'name' => 'John Customer',
            'email' => 'customer@trekadvisor.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // 2. Create 10 Treks with Itineraries and Departures
        $treks = \App\Models\Trek::factory(10)->create();

        foreach ($treks as $trek) {
            // Create 5-10 days of itinerary
            for ($i = 1; $i <= rand(5, 10); $i++) {
                \App\Models\Itinerary::factory()->create([
                    'trek_id' => $trek->id,
                    'day_number' => $i,
                ]);
            }

            // Create 2-3 departures
            \App\Models\Departure::factory(rand(2, 3))->create([
                'trek_id' => $trek->id,
            ]);
        }

        // 3. Create 5 Hotels owned by the hotel owner with Rooms
        $hotels = \App\Models\Hotel::factory(5)->create([
            'owner_id' => $hotelOwner->id,
        ]);

        foreach ($hotels as $hotel) {
            \App\Models\HotelRoom::factory(rand(2, 4))->create([
                'hotel_id' => $hotel->id,
            ]);
        }

        // 4. Create Gear Items
        $gearItems = \App\Models\GearItem::factory(10)->create();

        // 5. Create some bookings for the customer
        $departures = \App\Models\Departure::all();
        foreach ($departures->random(3) as $departure) {
            $booking = \App\Models\TrekBooking::factory()->create([
                'user_id' => $customer->id,
                'departure_id' => $departure->id,
            ]);

            // Create passengers for the booking
            \App\Models\Passenger::factory($booking->total_passengers)->create([
                'trek_booking_id' => $booking->id,
            ]);

            // Create payment for the booking
            \App\Models\Payment::factory()->create([
                'user_id' => $customer->id,
                'amount' => $booking->total_price,
                'payment_for' => 'trek',
                'reference_id' => $booking->id,
            ]);
        }

        // 6. Create some hotel bookings
        $rooms = \App\Models\HotelRoom::all();
        foreach ($rooms->random(2) as $room) {
            $hBooking = \App\Models\HotelBooking::factory()->create([
                'user_id' => $customer->id,
                'hotel_room_id' => $room->id,
            ]);

            \App\Models\Payment::factory()->create([
                'user_id' => $customer->id,
                'amount' => $hBooking->total_price,
                'payment_for' => 'hotel',
                'reference_id' => $hBooking->id,
            ]);
        }

        // 7. Create some gear rentals
        foreach ($gearItems->random(2) as $gearItem) {
            $rental = \App\Models\GearRental::factory()->create([
                'user_id' => $customer->id,
                'gear_item_id' => $gearItem->id,
            ]);

            \App\Models\Payment::factory()->create([
                'user_id' => $customer->id,
                'amount' => $rental->total_price,
                'payment_for' => 'gear',
                'reference_id' => $rental->id,
            ]);
        }

        // 8. Create some reviews
        foreach ($treks as $trek) {
            \App\Models\Review::factory(rand(1, 3))->create([
                'user_id' => $customer->id,
                'reviewable_id' => $trek->id,
                'reviewable_type' => \App\Models\Trek::class,
            ]);
        }

        foreach ($hotels as $hotel) {
            \App\Models\Review::factory(rand(1, 2))->create([
                'user_id' => $customer->id,
                'reviewable_id' => $hotel->id,
                'reviewable_type' => \App\Models\Hotel::class,
            ]);
        }
    }
}
