<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trek;
use App\Models\TrekImage;
use App\Models\Itinerary;
use App\Models\Departure;
use Illuminate\Support\Str;

class TrekSeeder extends Seeder
{
    public function run(): void
    {
        $trekData = [
            [
                'title' => 'Everest Base Camp Trek',
                'description' => 'The definitive journey to the roof of the world. Standing at the foot of Mount Everest is more than a trek; it\'s a pilgrimage through the heart of Sherpa culture. Why it stands out: You\'ll stand at 5,364m, surrounded by giants like Lhotse and Nuptse, and witness the sunrise from Kalapatthar. It’s an epic mix of high-altitude adventure and legendary hospitality that makes every single step worth the thinning air.',
                'base_price' => 9800,
                'difficulty' => 'difficult',
                'duration_days' => 12,
                'max_altitude' => 5364,
                'itinerary' => [
                    ['Day 1', 'Arrival & Kathmandu Entry', 'Meet our team and prep your gear for the high altitude.'],
                    ['Day 2', 'Lukla Flight & Phakding', 'A thrilling flight to 2,860m and a gentle river-side walk.'],
                    ['Day 3', 'Namche Bazaar (3,440m)', 'Gateway to Everest. The steep climb rewards you with the first peak views.'],
                    ['Day 4', 'Acclimatization in Namche', 'Explore the world\'s highest museum and see Everest View Hotel.'],
                    ['Day 5', 'Tengboche Monastery', 'Spiritual heart of the Khumbu with panoramic mountain vistas.'],
                    ['Day 6', 'Dingboche Valley', 'Enter the higher altitudes with changing desert-like landscapes.'],
                    ['Day 7', 'Island Peak Viewpoint', 'A vital rest and acclimatization hike to the Nagarjun ridge.'],
                    ['Day 8', 'Lobuche (4,940m)', 'Walk alongside the Khumbu glacier with giants above you.'],
                    ['Day 9', 'Everest Base Camp & Gorakshep', 'The ultimate goal. Touch the base camp and walk the glacier.'],
                    ['Day 10', 'Kalapatthar Sunrise & Pheriche', 'The iconic Everest photo spot at 5,550m before descending.'],
                    ['Day 11', 'Return to Namche', 'Enjoying the lower altitude air and celebrating in the bazaar.'],
                    ['Day 12', 'Lukla & Final Flight', 'The final walk back to the airstrip for an evening of celebration.']
                ]
            ],
            [
                'title' => 'Annapurna Circuit Trek',
                'description' => 'A classic odyssey that circles the entire Annapurna Massif. What makes this trek stand out is the sheer diversity—you begin in tropical rice fields and end in the high-altitude desert of Mustang. Crossing the Thorong La Pass at 5,416m is a test of grit and spirit, while the views of Dhaulagiri and Annapurna I are simply peerless. It\'s a journey of transition, from Hindu lowlands to Tibetan Buddhist highlands.',
                'base_price' => 9200,
                'difficulty' => 'difficult',
                'duration_days' => 14,
                'max_altitude' => 5416,
                'itinerary' => [
                    ['Day 1', 'Arrival in Kathmandu', 'Full briefing and gear check over dinner.'],
                    ['Day 2', 'Drive to Dharapani', 'Scenic road trip through the Marsyangdi River valley.'],
                    ['Day 3', 'Chame (2,670m)', 'Walk along the river through apple orchards and pine forests.'],
                    ['Day 4', 'Upper Pisang', 'The landscape turns drier as you enter the rain-shadow area.'],
                    ['Day 5', 'Manang (3,540m)', 'Regional HQ. Spectacular views of Tilicho Peak.'],
                    ['Day 6', 'Acclimatization in Manang', 'Explore the local monastery or hike to Ice Lake.'],
                    ['Day 7', 'Yak Kharka', 'Gradual climb into high alpine meadows where yaks graze.'],
                    ['Day 8', 'Thorong Phedi', 'Setting the stage for the big pass tomorrow morning.'],
                    ['Day 9', 'Thorong La Pass (5,416m)', 'The highest point. Steep descent to the sacred Muktinath.'],
                    ['Day 10', 'Muktinath & Jomsom', 'Visit 108 water spouts before the windy Jomsom valley.'],
                    ['Day 11', 'Marpha & Kalopani', 'Deepest gorge in the world between Annapurna/Dhaulagiri.'],
                    ['Day 12', 'Tatopani Hot Springs', 'Soak your tired muscles in natural riverside springs.'],
                    ['Day 13', 'Ghorepani', 'Steady climb through the hills for the final views.'],
                    ['Day 14', 'Poon Hill & Return', 'Sunrise over the massif followed by departure to Pokhara.']
                ]
            ],
            [
                'title' => 'Manaslu Circuit Trek',
                'description' => 'Remote, rugged, and restricted—Manaslu is for the trekker who wants to escape the crowds. This trek stands out for its untouched Tibetan-style culture in the Nupri region and the challenging Larkya La Pass (5,106m). It offers a more authentic "off-the-beaten-path" feel than Everest, taking you through dramatic landscapes under the watchful eye of the "Mountain of the Spirit."',
                'base_price' => 9600,
                'difficulty' => 'extreme',
                'duration_days' => 15,
                'max_altitude' => 5106,
                'itinerary' => [
                    ['Day 1', 'Drive to Machha Khola', 'Long drive into the heart of Gorkha district.'],
                    ['Day 2', 'Jagat (1,340m)', 'Enter restricted area following the Budhi Gandaki river.'],
                    ['Day 3', 'Deng', 'The culture begins to shift from Hindu to Buddhist.'],
                    ['Day 4', 'Namrung', 'First views of Siringit Himal and Ganesh Himal.'],
                    ['Day 5', 'Lho Village', 'Magnificent views of Mount Manaslu from the monastery.'],
                    ['Day 6', 'Sama Gaon', 'Traditional hub with Tibetan architecture.'],
                    ['Day 7', 'Acclimatization day', 'Hike to Birendra Lake or the Manaslu Base Camp.'],
                    ['Day 10', 'Larkya La Pass (5,106m)', 'Long, snowy traverse with peak panoramas.'],
                    ['Day 15', 'Return to Kathmandu', 'Final celebration after a remote trek.']
                ]
            ],
            [
                'title' => 'Langtang Valley Trek',
                'description' => 'The "Valley of Glaciers" is the closest high-mountain trek to Kathmandu. Langtang stands out for its intimate mountain views—you are surrounded by 7,000m peaks at Kyanjin Gompa. The hospitality of the Tamang people is legendary, and the high-altitude cheese factory is a local delight. Perfectly blends accessibility with high-Himalayan drama.',
                'base_price' => 6500,
                'difficulty' => 'moderate',
                'duration_days' => 8,
                'max_altitude' => 4984,
                'itinerary' => [
                    ['Day 1', 'Drive to Syabrubesi', 'Scenic drive into the Langtang National Park.'],
                    ['Day 2', 'Lama Hotel', 'Lush forest walk following the Langtang Khola river.'],
                    ['Day 3', 'Langtang Village', 'The rebuilt village, resilient and very welcoming.'],
                    ['Day 4', 'Kyanjin Gompa (3,830m)', 'The spiritual heart of the valley. Visit the dairy.'],
                    ['Day 5', 'Kyanjin Ri Peak', 'The high point. Stunning glaciers and peaks all around.'],
                    ['Day 8', 'Drive to Kathmandu', 'Journey back across the hills to the city.']
                ]
            ],
            [
                'title' => 'Ghorepani Poon Hill Trek',
                'description' => 'The ultimate short adventure for a grand view. Stand on Poon Hill as the first light of day paints the Annapurna and Dhaulagiri massifs gold. Why it stands out: World\'s largest rhododendron forests (stunning in spring) and the most beautiful Gurung village in Nepal, Ghandruk. A perfect blend of culture, forest trails, and massifs.',
                'base_price' => 5400,
                'difficulty' => 'easy',
                'duration_days' => 5,
                'max_altitude' => 3210,
                'itinerary' => [
                    ['Day 1', 'Pokhara to Ulleri', 'Short drive and steady climb up iconic stone steps.'],
                    ['Day 2', 'Ghorepani (2,860m)', 'Magical walk through ancient rhododendron and oak forests.'],
                    ['Day 3', 'Poon Hill Sunrise', 'Iconic 3,210m viewpoint followed by hike to Tadapani.'],
                    ['Day 4', 'Ghandruk village', 'Exploring the most beautiful stone village of Gurungs.'],
                    ['Day 5', 'Pokhara return', 'Gentle walk to the road-head and drive to Lakeside.']
                ]
            ],
            [
                'title' => 'Mardi Himal Ridge Trek',
                'description' => 'A hidden gem that has quickly become a favorite for those seeking peace and mountain drama. The trail sticks to a high ridge, offering constant, unobstructed views of Mount Machhapuchhre (Fishtail). Why it stands out: Pristine forest trails, quiet teahouses, and the most incredible perspective of the entire Annapurna Sanctuary from High Camp.',
                'base_price' => 6000,
                'difficulty' => 'moderate',
                'duration_days' => 6,
                'max_altitude' => 4500,
                'itinerary' => [
                    ['Day 1', 'Pokhara to Forest Camp', 'Drive to Kande and a forest-bound hike.'],
                    ['Day 2', 'High Camp (3,580m)', 'Landscape turns alpine above the clouds.'],
                    ['Day 3', 'Mardi Base Camp (4,500m)', 'Stand right under the towering Fishtail peak.'],
                    ['Day 6', 'Pokhara Return', 'Final leg to the lakeside city.']
                ]
            ],
            [
                'title' => 'Upper Mustang Trek',
                'description' => 'Enter the "Last Forbidden Kingdom." This trek stands out for its desert-like landscape, ancient sky-caves, and a culture that has remained unchanged for 500 years. Lo Manthang, the walled capital, is a spiritual fortress on the edge of Tibet. A deeply spiritual journey through a landscape carved by wind and deep history.',
                'base_price' => 10000,
                'difficulty' => 'moderate',
                'duration_days' => 12,
                'max_altitude' => 3810,
                'itinerary' => [
                    ['Day 1', 'Jomsom & Kagbeni', 'Begin in the windy Kali Gandaki valley.'],
                    ['Day 6', 'Lo Manthang (3,810m)', 'Arrival at the walled capital of the Kingdom.'],
                    ['Day 7', 'Sky Cave Exploration', 'Ancient history in the caves of Chhoser.']
                ]
            ],
            [
                'title' => 'Gokyo Lakes Trek',
                'description' => 'The turquoise gems of the Everest region. This trek stands out for the six high-altitude glacial lakes and the Ngozumpa Glacier. Climbing Gokyo Ri (5,357m) offers a perspective of four 8,000m peaks at once—Everest, Lhotse, Makalu, and Cho Oyu. A quieter, more scenic alternative to the base camp route.',
                'base_price' => 9000,
                'difficulty' => 'difficult',
                'duration_days' => 12,
                'max_altitude' => 5357,
                'itinerary' => [
                    ['Day 1', 'Lukla to Namche', 'Standard Khumbu approach via river valleys.'],
                    ['Day 8', 'Gokyo Lakes (4,790m)', 'Arrival at the stunning turquoise glacier lakes.'],
                    ['Day 9', 'Gokyo Ri Sunrise', 'Panoramic views of 8,000m titans.']
                ]
            ],
            [
                'title' => 'Kanchenjunga Base Camp',
                'description' => 'A remote trek to the far eastern frontier near the 3rd highest peak. Stands out for massive scale and absolute isolation. Truly untouched wilderness for those who want to feel like a real explorer. Pristine beauty under the Five Treasures of Snow.',
                'base_price' => 9900,
                'difficulty' => 'extreme',
                'duration_days' => 22,
                'max_altitude' => 5143,
                'itinerary' => [
                    ['Day 1-22', 'Expedition Phase', 'Venture into the far east where few trekkers follow.']
                ]
            ],
            [
                'title' => 'Tsum Valley Sacred Trek',
                'description' => 'The Hidden Valley of non-violence. Stands out for ancient monasteries like Mu Gompa where no animals are harmed. Deeply spiritual and culturally rich behind the Manaslu range.',
                'base_price' => 7000,
                'difficulty' => 'moderate',
                'duration_days' => 15,
                'max_altitude' => 3700,
                'itinerary' => [
                    ['Day 1-15', 'Spiritual exploration', 'Walk through the land where history remains alive.']
                ]
            ],
            [
                'title' => 'Rara Lake Western Gem',
                'description' => 'Nepal\'s largest lake in the remote far west. Stands out for the deep blue contrast against cedar forests and snow peaks. Absolute tranquility away from any crowds.',
                'base_price' => 6200,
                'difficulty' => 'moderate',
                'duration_days' => 10,
                'max_altitude' => 3480,
                'itinerary' => [
                    ['Day 1-10', 'Western Discovery', 'Journey through national parks to the Queen of Lakes.']
                ]
            ],
            [
                'title' => 'Makalu Base Camp Trek',
                'description' => 'Dramatic scenery in the fifth highest mountain\'s shadow. Stands out for biodiversity in Makalu Barun and ultimate high-altitude isolation.',
                'base_price' => 9800,
                'difficulty' => 'extreme',
                'duration_days' => 20,
                'max_altitude' => 4870,
                'itinerary' => [
                    ['Day 1-20', 'High Altitude Drama', 'Isolated exploration for the serious Himalayan trekker.']
                ]
            ],
            [
                'title' => 'Rolwaling Wild Valley',
                'description' => 'The valley of legends and shadows. Stands out for the Tashi Lapcha Pass at 5,755m and the proximity to the Tibet border under Gauri Shankar peak.',
                'base_price' => 9700,
                'difficulty' => 'extreme',
                'duration_days' => 18,
                'max_altitude' => 5755,
                'itinerary' => [
                    ['Day 1-18', 'Wild Frontier', 'Serious mountaineering and rugged valley exploration.']
                ]
            ],
            [
                'title' => 'Ama Dablam Base Camp',
                'description' => 'Trek to the base of the world\'s most beautiful technical peak. Short, scenic, and deeply inspiring for climbing enthusiasts.',
                'base_price' => 7400,
                'difficulty' => 'moderate',
                'duration_days' => 11,
                'max_altitude' => 4600,
                'itinerary' => [
                    ['Day 1-11', 'Scenic Beauty', 'Following the footsteps of legendary alpinists.']
                ]
            ],
            [
                'title' => 'Island Peak Summit Trek',
                'description' => 'The perfect intro to peak climbing. Scale a 6,189m peak with crampons and ice axes for a monumental life achievement.',
                'base_price' => 10000,
                'difficulty' => 'extreme',
                'duration_days' => 18,
                'max_altitude' => 6189,
                'itinerary' => [
                    ['Day 1-18', 'Summit Phase', 'Preparation, high camp, and the midnight summit push.']
                ]
            ]
        ];

        foreach ($trekData as $data) {
            $trek = Trek::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'base_price' => $data['base_price'],
                'difficulty' => $data['difficulty'],
                'duration_days' => $data['duration_days'],
                'max_altitude' => $data['max_altitude'],
                'status' => 'active',
            ]);

            // Single requested sample image
            TrekImage::create([
                'trek_id' => $trek->id,
                'path' => 'treks/VlRhKV4hHoMl3sgXJtSN9ljSTjKv97wNS4Q9oeIg.jpg',
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            // Professional Itinerary
            foreach ($data['itinerary'] as $item) {
                Itinerary::create([
                    'trek_id' => $trek->id,
                    'day_number' => (int) filter_var($item[0], FILTER_SANITIZE_NUMBER_INT) ?: 1,
                    'title' => $item[1],
                    'description' => $item[2],
                ]);
            }

            // Realistic departures
            Departure::create([
                'trek_id' => $trek->id,
                'start_date' => now()->addMonths(1)->addDays(rand(1, 10)),
                'end_date' => now()->addMonths(1)->addDays($trek->duration_days + 11),
                'price' => $trek->base_price,
                'capacity' => 12,
                'booked_seats' => rand(0, 4),
                'status' => 'available',
            ]);
        }
    }
}
