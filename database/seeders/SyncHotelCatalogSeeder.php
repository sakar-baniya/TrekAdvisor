<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class SyncHotelCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->hotelData() as $hotelData) {
            $rooms = $hotelData['rooms'];
            unset($hotelData['rooms']);

            $hotel = Hotel::query()->where('name', $hotelData['name'])->first();

            if (! $hotel) {
                continue;
            }

            $hotel->update($hotelData);

            foreach ($rooms as $index => $roomData) {
                $room = $hotel->rooms()->orderBy('id')->get()[$index] ?? null;

                if ($room) {
                    $room->update($roomData);
                } else {
                    $hotel->rooms()->create($roomData);
                }
            }
        }
    }

    protected function hotelData(): array
    {
        return [
            [
                'name' => 'Hotel Barahi Pokhara',
                'location' => 'Lakeside, Pokhara',
                'description' => "A long-running full-service Lakeside property set close to Phewa Lake, restaurants, and the main Pokhara promenade.\nIt is a practical pre- and post-trek base for travelers who want a central location, dependable service, and room categories that scale from standard stays to suites.",
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
                'status' => 'Active',
                'rooms' => [
                    ['room_type' => 'Deluxe Room', 'price_per_night' => 85, 'total_rooms' => 12],
                    ['room_type' => 'Super Deluxe Room', 'price_per_night' => 100, 'total_rooms' => 10],
                    ['room_type' => 'Suite', 'price_per_night' => 250, 'total_rooms' => 4],
                ],
            ],
            [
                'name' => 'Hotel Middle Path & Spa',
                'location' => 'Lakeside 6, Pokhara',
                'description' => "A well-reviewed mid-range Lakeside stay known for its quieter side-street setting, garden atmosphere, and walkable access to cafes and the lakeshore.\nIt works especially well for trekkers who want a comfortable but value-conscious stopover before or after Annapurna-region routes.",
                'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=80',
                'status' => 'Active',
                'rooms' => [
                    ['room_type' => 'Standard Double Room', 'price_per_night' => 45, 'total_rooms' => 8],
                    ['room_type' => 'Deluxe Double or Twin Room', 'price_per_night' => 62, 'total_rooms' => 9],
                    ['room_type' => 'Family Room', 'price_per_night' => 88, 'total_rooms' => 5],
                ],
            ],
            [
                'name' => 'Temple Tree Resort & Spa',
                'location' => 'Gaurighat, Lakeside, Pokhara',
                'description' => "A polished four-star resort near the lakefront known for landscaped courtyards, spa facilities, and a more resort-style experience than a typical city hotel.\nIt suits travelers extending a Nepal trip with extra comfort before or after major trekking departures.",
                'image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
                'status' => 'Active',
                'rooms' => [
                    ['room_type' => 'Deluxe Room', 'price_per_night' => 167, 'total_rooms' => 11],
                    ['room_type' => 'Junior Suite', 'price_per_night' => 220, 'total_rooms' => 6],
                    ['room_type' => 'Executive Suite', 'price_per_night' => 290, 'total_rooms' => 3],
                ],
            ],
            [
                'name' => 'Waterfront Resort by KGH Group',
                'location' => 'Sedi Heights, Pokhara',
                'description' => "A quieter lakeside resort on the Sedi side of Pokhara, known for broad water views and a more relaxed setting away from the busiest central lanes.\nIt is a strong fit for couples and small groups who want a scenic recovery stop after mountain travel.",
                'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'status' => 'Active',
                'rooms' => [
                    ['room_type' => 'Deluxe Room', 'price_per_night' => 51, 'total_rooms' => 7],
                    ['room_type' => 'Deluxe Room with Balcony', 'price_per_night' => 68, 'total_rooms' => 8],
                    ['room_type' => 'Suite Room', 'price_per_night' => 96, 'total_rooms' => 3],
                ],
            ],
            [
                'name' => 'Mount Kailash Resort',
                'location' => 'Lakeside, Pokhara',
                'description' => "A long-established Pokhara resort near Phewa Lake with mountain-facing upper floors, garden spaces, and easy road access for airport transfers and trekking logistics.\nIt is a practical option for guests who want a full-service Lakeside base without moving into the most crowded part of town.",
                'image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=80',
                'status' => 'Active',
                'rooms' => [
                    ['room_type' => 'Deluxe Room', 'price_per_night' => 58, 'total_rooms' => 10],
                    ['room_type' => 'Premium Deluxe Room', 'price_per_night' => 82, 'total_rooms' => 12],
                    ['room_type' => 'Junior Suite', 'price_per_night' => 128, 'total_rooms' => 4],
                ],
            ],
        ];
    }
}
