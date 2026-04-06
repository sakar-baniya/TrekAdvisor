<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelImage;
use App\Models\User;

class HotelSeeder extends Seeder
{
    private const FALLBACK_IMAGE = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=1200';

    public function run(): void
    {
        $owners = User::where('role', 'hotel_owner')->get();
        if ($owners->isEmpty()) {
            // Create a fallback owner if none exist
            $owners = collect([User::factory()->create([
                'name' => 'Mountain Hospitality Group',
                'email' => 'owner.fallback@trekadvisor.com',
                'role' => 'hotel_owner',
                'approval_status' => 'approved',
            ])]);
        }

        Hotel::query()->delete();

        $hotels = [
            [
                'name' => 'Everest Summit Lodge',
                'location' => 'Lukla, Khumbu Region',
                'description' => "Situated just a ten-minute walk from the Tenzing-Hillary airstrip, Everest Summit Lodge offers a sanctuary of Sherpa hospitality. The lodge was built using traditional dry-stone masonry and features heated floors that are an absolute luxury in the high-altitude chill. \n\nWhat sets this place apart is the attention to post-trek recovery — they offer excellent organic coffee, thick goose-down duvets, and a dining hall that faces the imposing peak of Kongde Ri. Whether you're nervously sipping tea before your trek or celebrating your return from Base Camp, this lodge feels like a true Himalayan home.",
                'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'name' => 'Hotel Yeti Mountain Home',
                'location' => 'Namche Bazaar, Everest Region',
                'description' => "Perched on the steep terraced slopes of Namche Bazaar, Yeti Mountain Home is a boutique lodge combining traditional Khumbu aesthetics with unexpected modern comfort. The rooms are finished with hand-carved wood panelling and feature electric blankets, making those biting sub-zero nights incredibly cozy.\n\nThe real highlight here is the panoramic view of the horseshoe-shaped town from the restaurant terrace, complete with Everest and Thamserku looming in the distance. The lodge employs local Sherpa staff who provide deep insights into the region's culture, making it an ideal acclimatization stop before pushing higher up the valley.",
                'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'name' => 'Annapurna Luxury Lodge',
                'location' => 'Ghandruk, Annapurna Region',
                'description' => "Set against the staggering backdrop of Annapurna South and Machhapuchhre, this lodge is located in the culturally rich Gurung village of Ghandruk. Built sensitively into the hillside to reflect local village architecture, it offers a level of comfort rarely found so far from road access.\n\nGuests love the landscaped gardens where you can enjoy breakfast while watching the morning light catch the 7,000-metre peaks. The lodge sources its vegetables from its own organic garden and nearby farms, ensuring meals are fresh and hearty — exactly what you need after ascending the thousands of stone steps from the Modi River valley.",
                'image' => 'https://images.unsplash.com/photo-1445013564756-3a74a1124237?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'name' => 'Temple Tree Resort & Spa',
                'location' => 'Lakeside, Pokhara',
                'description' => "A serene oasis tucked away from the main bustle of Pokhara's Lakeside, Temple Tree Resort is designed around a series of lush courtyards and a two-tier temperature-controlled pool. The architecture embraces traditional Western Himalayan style, utilizing rough-hewn stone columns and exposed timber beams.\n\nIt is the perfect post-trek recovery environment. The on-site spa specializes in deep tissue massages targeted specifically at hikers' tired muscles. The rooms are spacious, quiet, and feature private balconies that look out onto the gardens, with the occasional glimpse of the Annapurna range on clear mornings.",
                'image' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'name' => 'Mountain Glory Forest Resort',
                'location' => 'Dovan, Pokhara',
                'description' => "Ducked into the dense sal forests just outside Pokhara city, Mountain Glory is for travelers seeking absolute tranquility and connection to nature without sacrificing upscale amenities. The property is vast, allowing for short forest walks directly from your room.\n\nThe resort's infinity pool seems to drop off right into the Seti River gorge below, offering sweeping views of the surrounding hills. It's a fantastic operational base for those looking to do day hikes in the lower Annapurna foothills, offering high-end dining and impeccable service upon your return.",
                'image' => 'https://images.unsplash.com/photo-1455587734955-081b22074882?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'name' => 'The Pavilions Himalayas',
                'location' => 'Chisapani, Pokhara Valley',
                'description' => "An absolutely stunning eco-luxury resort situated in a secluded valley backed by the majestic Annapurna range. What makes The Pavilions stand out is its commitment to sustainability — it operates completely off-grid, utilizing solar power and biogas, yet offers uncompromising luxury.\n\nThe villas are massive, featuring private terraces and sunken baths that are simply miraculous after a week on a trekking trail. The food is farm-to-table, utilizing ingredients grown right on the property. It offers an elite, private, and deeply restful experience for discerning travelers ending their Nepal journey.",
                'image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&q=80&w=1200'
            ]
        ];

        foreach ($hotels as $index => $data) {
            $owner = $owners[$index % count($owners)];
            $hotel = Hotel::create([
                'owner_id' => $owner->id,
                'name' => $data['name'],
                'location' => $data['location'],
                'description' => $data['description'],
                'status' => 'active',
            ]);

            // Add images
            HotelImage::create([
                'hotel_id' => $hotel->id,
                'path' => $data['image'] ?? self::FALLBACK_IMAGE,
                'sort_order' => 0,
            ]);

            // Add standard room pricing logic
            HotelRoom::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'Standard Double',
                'price_per_night' => rand(60, 110),
                'total_rooms' => 12,
            ]);

            HotelRoom::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'Deluxe Suite',
                'price_per_night' => rand(150, 300),
                'total_rooms' => 4,
            ]);
        }
    }
}
