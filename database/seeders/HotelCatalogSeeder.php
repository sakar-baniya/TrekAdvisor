<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class HotelCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create the hotel_owner user
        $hotelOwner = User::where('email', 'owner@trekadvisor.com')->first()
            ?? User::factory()->create([
                'name'     => 'Hotel Owner',
                'email'    => 'owner@trekadvisor.com',
                'password' => bcrypt('password'),
                'role'     => 'hotel_owner',
            ]);

        foreach ($this->hotelData() as $data) {
            $images = $data['images'] ?? [];
            $rooms  = $data['rooms']  ?? [];
            unset($data['images'], $data['rooms']);

            // Upsert hotel by name so re-running is idempotent
            $hotel = Hotel::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['owner_id' => $hotelOwner->id])
            );

            // Sync images
            if (!empty($images) && $hotel->images()->count() === 0) {
                foreach ($images as $i => $path) {
                    HotelImage::create([
                        'hotel_id'   => $hotel->id,
                        'path'       => $path,
                        'sort_order' => $i,
                    ]);
                }
            }

            // Sync rooms
            foreach ($rooms as $roomData) {
                $hotel->rooms()->updateOrCreate(
                    ['room_type' => $roomData['room_type']],
                    $roomData
                );
            }
        }
    }

    protected function hotelData(): array
    {
        return [
            [
                'name'        => 'Hotel Barahi Pokhara',
                'location'    => 'Lakeside, Pokhara',
                'description' => "A long-running full-service Lakeside property set close to Phewa Lake, restaurants, and the main Pokhara promenade.\nIt is a practical pre- and post-trek base for travelers who want a central location, dependable service, and room categories that scale from standard stays to suites.",
                'status'      => 'active',
                'images'      => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80',
                ],
                'rooms' => [
                    ['room_type' => 'Deluxe Room',       'price_per_night' => 6200, 'total_rooms' => 12],
                    ['room_type' => 'Super Deluxe Room', 'price_per_night' => 7400, 'total_rooms' => 10],
                    ['room_type' => 'Suite',             'price_per_night' => 8000, 'total_rooms' => 4],
                ],
            ],
            [
                'name'        => 'Hotel Middle Path & Spa',
                'location'    => 'Lakeside 6, Pokhara',
                'description' => "A well-reviewed mid-range Lakeside stay known for its quieter side-street setting, garden atmosphere, and walkable access to cafes and the lakeshore.\nIt works especially well for trekkers who want a comfortable but value-conscious stopover before or after Annapurna-region routes.",
                'status'      => 'active',
                'images'      => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=80',
                ],
                'rooms' => [
                    ['room_type' => 'Standard Double Room',       'price_per_night' => 3500, 'total_rooms' => 8],
                    ['room_type' => 'Deluxe Double or Twin Room', 'price_per_night' => 5200, 'total_rooms' => 9],
                    ['room_type' => 'Family Room',                'price_per_night' => 7000, 'total_rooms' => 5],
                ],
            ],
            [
                'name'        => 'Temple Tree Resort & Spa',
                'location'    => 'Gaurighat, Lakeside, Pokhara',
                'description' => "A polished four-star resort near the lakefront known for landscaped courtyards, spa facilities, and a more resort-style experience than a typical city hotel.\nIt suits travelers extending a Nepal trip with extra comfort before or after major trekking departures.",
                'status'      => 'active',
                'images'      => [
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=80',
                ],
                'rooms' => [
                    ['room_type' => 'Deluxe Room',     'price_per_night' => 6800, 'total_rooms' => 11],
                    ['room_type' => 'Junior Suite',    'price_per_night' => 7600, 'total_rooms' => 6],
                    ['room_type' => 'Executive Suite', 'price_per_night' => 8000, 'total_rooms' => 3],
                ],
            ],
            [
                'name'        => 'Waterfront Resort by KGH Group',
                'location'    => 'Sedi Heights, Pokhara',
                'description' => "A quieter lakeside resort on the Sedi side of Pokhara, known for broad water views and a more relaxed setting away from the busiest central lanes.\nIt is a strong fit for couples and small groups who want a scenic recovery stop after mountain travel.",
                'status'      => 'active',
                'images'      => [
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1200&q=80',
                ],
                'rooms' => [
                    ['room_type' => 'Deluxe Room',              'price_per_night' => 4500, 'total_rooms' => 7],
                    ['room_type' => 'Deluxe Room with Balcony', 'price_per_night' => 5800, 'total_rooms' => 8],
                    ['room_type' => 'Suite Room',               'price_per_night' => 7600, 'total_rooms' => 3],
                ],
            ],
            [
                'name'        => 'Mount Kailash Resort',
                'location'    => 'Lakeside, Pokhara',
                'description' => "A long-established Pokhara resort near Phewa Lake with mountain-facing upper floors, garden spaces, and easy road access for airport transfers and trekking logistics.\nIt is a practical option for guests who want a full-service Lakeside base without moving into the most crowded part of town.",
                'status'      => 'active',
                'images'      => [
                    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1200&q=80',
                ],
                'rooms' => [
                    ['room_type' => 'Deluxe Room',         'price_per_night' => 5000, 'total_rooms' => 10],
                    ['room_type' => 'Premium Deluxe Room', 'price_per_night' => 6500, 'total_rooms' => 12],
                    ['room_type' => 'Junior Suite',        'price_per_night' => 7800, 'total_rooms' => 4],
                ],
            ],
        ];
    }
}
