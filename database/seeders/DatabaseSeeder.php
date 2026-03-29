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

        // 2. Sync curated trek content with real route names and pricing
        $this->call(CuratedTrekSeeder::class);
        $this->call(CuratedItinerarySeeder::class);
        $treks = \App\Models\Trek::query()->get();

        // 3. Create hotels, rooms, gear items, related bookings, and reviews
        $this->call(HotelAndGearSeeder::class);

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

        // 6. Create some reviews
        foreach ($treks as $trek) {
            \App\Models\Review::factory(rand(1, 3))->create([
                'user_id' => $customer->id,
                'reviewable_id' => $trek->id,
                'reviewable_type' => \App\Models\Trek::class,
            ]);
        }

    }
}
