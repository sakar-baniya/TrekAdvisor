<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Trek;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Departure;
use App\Models\TrekBooking;
use App\Models\Passenger;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CuratedTrekSeeder::class,
            CuratedItinerarySeeder::class,
            HotelSeeder::class,
        ]);

        $customers = User::where('role', 'customer')->get();
        $treks = Trek::all();
        $hotels = Hotel::all();

        // ─── Trek Reviews ───────────────────────────────────────
        // Each review is written for a specific kind of trek experience.
        // They are distributed round-robin across treks so no two treks
        // get identical review sets.

        $trekReviews = [
            ['rating' => 5, 'comment' => "We reached base camp on a clear morning and every peak was visible. Our guide Pemba managed the pace perfectly — nobody in our group had altitude trouble."],
            ['rating' => 5, 'comment' => "The teahouse food surprised me. Dal bhat every day and I never got tired of it. The lodge owners along the trail were genuinely warm."],
            ['rating' => 4, 'comment' => "Tough final two days but the views from the pass made it all worthwhile. Take the acclimatization day seriously — I'm glad I did."],
            ['rating' => 5, 'comment' => "My third time in Nepal and this was the best route I've done. Quieter than Annapurna Circuit, far more dramatic scenery, and the villages felt untouched."],
            ['rating' => 4, 'comment' => "Good overall experience. The lodges at higher altitude are basic but clean. Bring a good sleeping bag — it gets properly cold above 4,000m."],
            ['rating' => 5, 'comment' => "The sunrise viewpoint alone was worth the trip. I stood there for twenty minutes in complete silence, watching the light hit the snow. Unforgettable."],
            ['rating' => 5, 'comment' => "Booking was straightforward, the departure date worked perfectly with my schedule, and the guide was clearly experienced with the route."],
            ['rating' => 4, 'comment' => "The rhododendron forests in spring were incredible — tunnels of red and pink flowers for hours. A very different vibe from high-altitude desert treks."],
            ['rating' => 5, 'comment' => "First time at this altitude and I managed well thanks to the itinerary pacing. Two acclimatization days made a real difference."],
            ['rating' => 4, 'comment' => "Honestly, crossing the pass was the hardest physical thing I've done. But standing at the top with prayer flags snapping in the wind — totally worth the pain."],
            ['rating' => 5, 'comment' => "The cultural side of this trek is underrated. Monastery visits, local festivals, and staying in family-run lodges gave it a personal feel you don't get on busier routes."],
            ['rating' => 5, 'comment' => "Glacier views were stunning. We had one afternoon where the clouds cleared completely and you could see the entire ice wall. My camera ran out of storage."],
            ['rating' => 4, 'comment' => "Be prepared for the river crossings — some are sketchy suspension bridges that swing. But that's part of the adventure. Great trek for someone who wants a challenge."],
            ['rating' => 5, 'comment' => "Our porter team was excellent. They were faster than us every single day and always had hot tea ready when we arrived at the lodge."],
            ['rating' => 5, 'comment' => "I've trekked in Patagonia and the Alps, but Nepal is different. The scale of the mountains, the warmth of the people — nothing else compares."],
            ['rating' => 4, 'comment' => "Good trek for the price. The itinerary was realistic and we didn't feel rushed on any day. Would recommend adding the optional ridge hike if weather allows."],
            ['rating' => 5, 'comment' => "The final day descent felt bittersweet. After a week in the mountains, coming back to the road felt surreal. Already planning my next Nepal trip."],
            ['rating' => 5, 'comment' => "Saw Dhaulagiri, Annapurna, and Machhapuchhre all in one panorama. The guide knew exactly where to stop for the best angles. Worth every rupee."],
            ['rating' => 4, 'comment' => "The hot springs stop at the end of the trek was a highlight I didn't expect. Soaking tired legs in natural spring water with mountain views — perfect recovery."],
            ['rating' => 5, 'comment' => "We had a small group of four and it felt like a private expedition. The logistics were handled completely — permits, transport, everything. I just had to walk."],
            ['rating' => 4, 'comment' => "Route was more demanding than I expected from a \"moderate\" rating, but achievable with decent fitness. The altitude is the real challenge, not the walking."],
            ['rating' => 5, 'comment' => "Night sky at high camp was unreal. No light pollution at all. I could see the Milky Way clearly for the first time in my life."],
            ['rating' => 5, 'comment' => "The yak cheese at the high settlement was surprisingly good. Little details like that make Nepal trekking special — it's not just scenery, it's the whole experience."],
            ['rating' => 4, 'comment' => "Weather changed fast on day three but the guide handled it calmly and we adjusted our plan. Flexibility from the team made a big difference."],
        ];

        $reviewIndex = 0;
        foreach ($treks as $trek) {
            // Give each trek 5-8 reviews, cycling through the pool
            $count = 5 + ($trek->id % 4);
            for ($i = 0; $i < $count; $i++) {
                $review = $trekReviews[$reviewIndex % count($trekReviews)];
                Review::create([
                    'user_id' => $customers->random()->id,
                    'reviewable_id' => $trek->id,
                    'reviewable_type' => Trek::class,
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                ]);
                $reviewIndex++;
            }
        }

        // ─── Hotel Reviews ──────────────────────────────────────

        $hotelReviews = [
            ['rating' => 5, 'comment' => 'Perfect place to recover after two weeks on the trail. Hot shower, clean sheets, and the restaurant served proper coffee. Exactly what I needed.'],
            ['rating' => 4, 'comment' => 'Location is excellent — walking distance to the lake and easy to arrange airport transfers. Room was comfortable and the WiFi actually worked.'],
            ['rating' => 5, 'comment' => 'The staff remembered my name from check-in and asked about my trek every morning at breakfast. That personal touch is rare.'],
            ['rating' => 5, 'comment' => 'Balcony view of the mountains at sunrise was spectacular. I sat out there with tea for an hour before breakfast. Best morning of the trip.'],
            ['rating' => 4, 'comment' => 'Solid mid-range option. Nothing fancy but everything works — hot water, clean towels, good dal bhat at dinner. Fair price for the area.'],
            ['rating' => 5, 'comment' => 'The garden area is beautiful and quiet. After noisy teahouses on the trail, having a peaceful place to read and rest was wonderful.'],
            ['rating' => 4, 'comment' => 'Good base for organizing trek logistics. The front desk helped me with permits and bus tickets without charging extra fees.'],
            ['rating' => 5, 'comment' => 'We stayed three nights pre-trek and two nights post-trek. Both times the service was consistent and welcoming. Will book again for my Manaslu trip.'],
            ['rating' => 5, 'comment' => 'Breakfast buffet was much better than I expected — fresh fruit, eggs to order, and Nepali tea that was genuinely excellent.'],
            ['rating' => 4, 'comment' => 'Room was a bit small but the bed was comfortable and the bathroom was modern. Good value for Lakeside. Would recommend for trekkers on a budget.'],
            ['rating' => 5, 'comment' => 'The spa treatment after our Annapurna Circuit was exactly what my muscles needed. Professional staff and a relaxing atmosphere.'],
            ['rating' => 4, 'comment' => 'Central location, easy to find, and they stored our extra luggage while we were on the trek. Very practical and well-organized.'],
        ];

        $reviewIndex = 0;
        foreach ($hotels as $hotel) {
            $count = 4 + ($hotel->id % 3);
            for ($i = 0; $i < $count; $i++) {
                $review = $hotelReviews[$reviewIndex % count($hotelReviews)];
                Review::create([
                    'user_id' => $customers->random()->id,
                    'reviewable_id' => $hotel->id,
                    'reviewable_type' => Hotel::class,
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                ]);
                $reviewIndex++;
            }
        }

        // ─── Sample Bookings ────────────────────────────────────

        $primaryCustomer = User::where('name', 'Arjun Thapa')->first();
        if ($primaryCustomer) {
            $featuredDepartures = Departure::whereIn('trek_id', $treks->take(3)->pluck('id'))->get();
            foreach ($featuredDepartures as $dep) {
                $booking = TrekBooking::create([
                    'user_id' => $primaryCustomer->id,
                    'departure_id' => $dep->id,
                    'booking_reference' => 'TB-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'total_passengers' => 2,
                    'price_per_person' => $dep->price,
                    'subtotal' => $dep->price * 2,
                    'discount_amount' => 50,
                    'total_price' => ($dep->price * 2) - 50,
                    'status' => 'confirmed',
                ]);

                Passenger::create([
                    'trek_booking_id' => $booking->id,
                    'full_name' => 'Arjun Thapa',
                    'passport_number' => 'NPL1234567',
                    'age' => 28,
                ]);

                Passenger::create([
                    'trek_booking_id' => $booking->id,
                    'full_name' => 'Sara Thapa',
                    'passport_number' => 'NPL7654321',
                    'age' => 26,
                ]);

                Payment::create([
                    'user_id' => $primaryCustomer->id,
                    'payable_id' => $booking->id,
                    'payable_type' => 'trek',
                    'transaction_id' => 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    'amount' => $booking->total_price,
                    'currency' => 'USD',
                    'gateway' => 'stripe',
                    'status' => 'success',
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
