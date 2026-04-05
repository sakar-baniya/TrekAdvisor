<?php

namespace Database\Seeders;

use App\Models\GearItem;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HotelAndGearSeeder extends Seeder
{
    public function run(): void
    {
        $pokharaHotels = [
            [
                'name' => 'Hotel Barahi Pokhara',
                'location' => 'Lakeside, Pokhara',
                'description' => "A long-running full-service Lakeside property set close to Phewa Lake, restaurants, and the main Pokhara promenade.\nIt is a practical pre- and post-trek base for travelers who want a central location, dependable service, and room categories that scale from standard stays to suites.",
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
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
                'rooms' => [
                    ['room_type' => 'Deluxe Room', 'price_per_night' => 58, 'total_rooms' => 10],
                    ['room_type' => 'Premium Deluxe Room', 'price_per_night' => 82, 'total_rooms' => 12],
                    ['room_type' => 'Junior Suite', 'price_per_night' => 128, 'total_rooms' => 4],
                ],
            ],
        ];

        $gearCatalog = [
            ['name' => '65L Trekking Backpack', 'type' => 'Backpack', 'description' => 'Multi-day trekking pack suitable for Annapurna and Everest region routes, with enough capacity for layers, water, and sleeping essentials.', 'daily_price' => 6.50, 'total_stock' => 14, 'image' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Four-Season Sleeping Bag', 'type' => 'Sleeping Gear', 'description' => 'Warm rental sleeping bag built for cold teahouse nights at higher altitude, ideal for shoulder-season and colder departures.', 'daily_price' => 7.00, 'total_stock' => 18, 'image' => 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Down Jacket', 'type' => 'Outerwear', 'description' => 'Insulated down layer for early mornings, evening camp stops, and exposed high-altitude sections where temperatures drop quickly.', 'daily_price' => 5.00, 'total_stock' => 22, 'image' => 'https://images.unsplash.com/photo-1548883354-94bcfe321c87?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Trekking Poles Pair', 'type' => 'Trail Support', 'description' => 'Adjustable pole set that helps with balance on stone steps, long descents, and uneven Himalayan trails.', 'daily_price' => 3.50, 'total_stock' => 20, 'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Microspikes', 'type' => 'Footwear Accessory', 'description' => 'Traction add-on for icy mornings and compact snow sections on higher passes and winter treks.', 'daily_price' => 4.00, 'total_stock' => 12, 'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Waterproof Shell Jacket', 'type' => 'Outerwear', 'description' => 'Light rain shell for monsoon drizzle, windy ridges, and layering over insulation during unpredictable mountain weather.', 'daily_price' => 4.50, 'total_stock' => 16, 'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Headlamp', 'type' => 'Accessory', 'description' => 'Compact headlamp useful for early summit starts, lodge power cuts, and organizing gear before sunrise.', 'daily_price' => 2.00, 'total_stock' => 25, 'image' => 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Gaiters', 'type' => 'Footwear Accessory', 'description' => 'Protective gaiters that help keep mud, light snow, and scree out of boots on mixed terrain.', 'daily_price' => 2.50, 'total_stock' => 15, 'image' => 'https://images.unsplash.com/photo-1517999349371-c43520457b23?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Insulated Gloves', 'type' => 'Accessory', 'description' => 'Warm gloves for cold starts, windy ridgelines, and higher camps where exposed hands become uncomfortable fast.', 'daily_price' => 2.25, 'total_stock' => 24, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=1200&q=80'],
            ['name' => 'Foam Sleeping Pad', 'type' => 'Sleeping Gear', 'description' => 'Simple insulated pad that adds warmth and comfort when extra bedding support is needed during remote overnights.', 'daily_price' => 3.00, 'total_stock' => 17, 'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'],
        ];

        $hotelOwner = User::query()->firstOrCreate(
            ['email' => 'owner@trekadvisor.com'],
            [
                'name' => 'Hotel Owner',
                'password' => bcrypt('password'),
                'role' => 'hotel_owner',
                'approval_status' => 'approved',
            ],
        );

        $customer = User::query()->firstOrCreate(
            ['email' => 'customer@trekadvisor.com'],
            [
                'name' => 'John Customer',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'approval_status' => 'approved',
            ],
        );

        DB::transaction(function () use ($pokharaHotels, $gearCatalog, $hotelOwner, $customer): void {
            DB::table('payments')->where('payable_type', 'hotel')->delete();
            DB::table('payments')->where('payable_type', 'gear')->delete();
            DB::table('reviews')->where('reviewable_type', Hotel::class)->delete();
            DB::table('reviews')->where('reviewable_type', GearItem::class)->delete();
            DB::table('gear_rentals')->delete();
            DB::table('hotel_bookings')->delete();
            DB::table('gear_items')->delete();
            DB::table('hotels')->delete();

            $hotels = collect();

            foreach ($pokharaHotels as $hotelData) {
                $rooms = $hotelData['rooms'];
                unset($hotelData['rooms']);

                $hotel = Hotel::query()->create([
                    ...$hotelData,
                    'owner_id' => $hotelOwner->id,
                    'status' => 'Active',
                ]);

                foreach ($rooms as $room) {
                    $hotel->rooms()->create($room);
                }

                $hotels->push($hotel);
            }

            $gearItems = collect();

            foreach ($gearCatalog as $item) {
                $gearItems->push(GearItem::query()->create([
                    ...$item,
                    'status' => 'Active',
                ]));
            }

            foreach ($hotels->random(min(2, $hotels->count())) as $hotel) {
                $room = $hotel->rooms()->inRandomOrder()->first();

                if (! $room) {
                    continue;
                }

                $numNights = rand(1, 4);
                $numRooms = rand(1, min(2, $room->total_rooms));
                $checkInOffset = rand(5, 25);
                $checkIn = now()->addDays($checkInOffset)->toDateString();
                $checkOut = now()->addDays($checkInOffset + $numNights)->toDateString();
                $totalPrice = $room->price_per_night * $numNights * $numRooms;

                $bookingId = DB::table('hotel_bookings')->insertGetId([
                    'user_id' => $customer->id,
                    'hotel_room_id' => $room->id,
                    'booking_reference' => 'HB-' . strtoupper(Str::random(8)),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'num_rooms' => $numRooms,
                    'num_nights' => $numNights,
                    'price_per_night' => $room->price_per_night,
                    'total_price' => $totalPrice,
                    'status' => 'Confirmed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('payments')->insert([
                    'user_id' => $customer->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'amount' => $totalPrice,
                    'currency' => 'USD',
                    'payable_type' => 'hotel',
                    'payable_id' => $bookingId,
                    'gateway' => 'khalti',
                    'status' => 'Success',
                    'gateway_response' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($gearItems->random(min(2, $gearItems->count())) as $gearItem) {
                $quantity = rand(1, min(2, $gearItem->total_stock));
                $numDays = rand(3, 10);
                $startOffset = rand(3, 12);
                $startDate = now()->addDays($startOffset)->toDateString();
                $endDate = now()->addDays($startOffset + $numDays)->toDateString();
                $totalPrice = $gearItem->daily_price * $numDays * $quantity;

                $rentalId = DB::table('gear_rentals')->insertGetId([
                    'user_id' => $customer->id,
                    'gear_item_id' => $gearItem->id,
                    'rental_reference' => 'GR-' . strtoupper(Str::random(8)),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'quantity' => $quantity,
                    'num_days' => $numDays,
                    'daily_price' => $gearItem->daily_price,
                    'total_price' => $totalPrice,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('payments')->insert([
                    'user_id' => $customer->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'amount' => $totalPrice,
                    'currency' => 'USD',
                    'payable_type' => 'gear',
                    'payable_id' => $rentalId,
                    'gateway' => 'khalti',
                    'status' => 'Success',
                    'gateway_response' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($hotels as $hotel) {
                DB::table('reviews')->insert([
                    'user_id' => $customer->id,
                    'reviewable_id' => $hotel->id,
                    'reviewable_type' => Hotel::class,
                    'rating' => rand(4, 5),
                    'comment' => 'Comfortable stay in Pokhara with a convenient base before and after trekking.',
                    'is_flagged' => false,
                    'flagged_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($gearItems->take(4) as $gearItem) {
                DB::table('reviews')->insert([
                    'user_id' => $customer->id,
                    'reviewable_id' => $gearItem->id,
                    'reviewable_type' => GearItem::class,
                    'rating' => rand(4, 5),
                    'comment' => 'Useful rental item with solid condition for trekking preparation.',
                    'is_flagged' => false,
                    'flagged_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}


