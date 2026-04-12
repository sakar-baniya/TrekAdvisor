<?php

namespace Database\Seeders;

use App\Models\Departure;
use App\Models\Trek;
use App\Models\TrekImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CuratedTrekSeeder extends Seeder
{
    // A single reliable fallback image — use a known-working Unsplash photo
    private const FALLBACK_IMAGE = 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80';

    public function run(): void
    {
        // Clear old data for clean re-seed
        Trek::query()->delete();

        foreach ($this->trekData() as $data) {
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

            // Image — use provided URL or fallback
            TrekImage::create([
                'trek_id' => $trek->id,
                'path' => $data['image'] ?? self::FALLBACK_IMAGE,
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            // Create two future departures per trek
            $this->createDepartures($trek, $data['base_price'], $data['duration_days']);
        }
    }

    protected function trekData(): array
    {
        return [
            [
                'title' => 'Everest Base Camp Trek',
                'base_price' => 9800.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5364,
                'image' => 'https://images.unsplash.com/photo-1533130061792-64b345e4a833?auto=format&fit=crop&w=800&q=80',
                'description' => "The Everest Base Camp trek follows the Dudh Koshi river valley through Sagarmatha National Park to the foot of the world's highest peak. You'll walk through Namche Bazaar, the commercial heart of the Khumbu, stay near Tengboche Monastery with its commanding view of Ama Dablam, and push through the stark glacial terrain above Gorak Shep to reach base camp at 5,364 metres.\n\nWhat draws people to this route over and over is the combination of Sherpa culture, high-altitude drama, and the pure scale of the landscape. The optional sunrise climb to Kala Patthar delivers arguably the best Everest panorama available without climbing gear. Lodges along the trail are well-established and the route is clearly marked, though altitude remains the real challenge — acclimatization days at Namche and Dingboche are built in for good reason.",
            ],
            [
                'title' => 'Annapurna Circuit Trek',
                'base_price' => 9200.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5416,
                'image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80',
                'description' => "The Annapurna Circuit remains one of the world's great long-distance treks, circling the entire Annapurna massif through terrain that shifts from subtropical river valleys to the wind-scoured trans-Himalayan plateau beyond Manang. The centrepiece is the crossing of Thorong La at 5,416 metres — a full-day effort that drops you into the pilgrimage town of Muktinath on the other side.\n\nWhat makes this route exceptional is the sheer range of landscapes packed into two weeks. You start among rice paddies and banana trees, climb through pine and rhododendron forest, and finish on dry, treeless plateau that looks more like Ladakh than Nepal. The Kali Gandaki gorge — the deepest in the world — adds geological drama to the descent. Road construction has shortened some lower sections, which actually leaves more energy for the best high-altitude days.",
            ],
            [
                'title' => 'Annapurna Base Camp',
                'base_price' => 7800.00,
                'difficulty' => 'Moderate',
                'duration_days' => 10,
                'max_altitude' => 4130,
                'image' => 'https://images.unsplash.com/photo-1585409677983-0f6c41ca9c3b?auto=format&fit=crop&w=800&q=80',
                'description' => "The Annapurna Base Camp trek takes you into the heart of the Annapurna Sanctuary — a natural amphitheatre rimmed by Annapurna I, Annapurna South, Hiunchuli, and the iconic Machhapuchhre. The trail winds through Gurung villages like Ghandruk and Chhomrong, dips into bamboo and rhododendron forest along the Modi Khola, and opens into the wide glacier basin at 4,130 metres.\n\nThis is one of Nepal's best-value high-mountain treks because you get genuinely dramatic scenery without extreme altitude. The sanctuary itself feels like a hidden world — steep walls on all sides, glaciers spilling down from the peaks, and a silence that puts the rest of the trail in perspective. Spring brings the forests alive with blooming rhododendron, and the teahouses at Machhapuchhre Base Camp serve some of the best dal bhat on any trekking route.",
            ],
            [
                'title' => 'Langtang Valley Trek',
                'base_price' => 6500.00,
                'difficulty' => 'Moderate',
                'duration_days' => 8,
                'max_altitude' => 3870,
                'image' => 'https://images.unsplash.com/photo-1582234052329-87b64082f10b?auto=format&fit=crop&w=800&q=80',
                'description' => "The Langtang Valley is the closest high-mountain experience to Kathmandu, following the Langtang Khola north through dense forest and past yak pastures to Kyanjin Gompa at 3,870 metres. The valley was heavily affected by the 2015 earthquake, and the rebuilt villages carry a quiet resilience that adds emotional depth to the trekking experience.\n\nWhat sets Langtang apart from busier routes is the intimacy of the landscape. The valley walls close in around you, glaciers hang directly overhead, and the Tamang communities along the trail are genuinely welcoming without the commercialisation you find in parts of the Khumbu or Annapurna regions. The cheese factory at Kyanjin Gompa is a quirky highlight, and the optional climb to Kyanjin Ri or Tserko Ri opens up panoramic views across the entire Langtang range.",
            ],
            [
                'title' => 'Manaslu Circuit Trek',
                'base_price' => 9600.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5106,
                'image' => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?auto=format&fit=crop&w=800&q=80',
                'description' => "The Manaslu Circuit is Nepal's answer to the Annapurna Circuit for trekkers who want fewer crowds and wilder scenery. The route circles Mount Manaslu through a restricted area that requires special permits and a registered guide, passing through remote Buddhist settlements in the Nupri valley before crossing Larkya La at 5,106 metres.\n\nThe restricted-area status keeps group sizes smaller and preserves a quieter, more local feel along the trail. Villages like Samagaon and Samdo have stone buildings with Tibetan-style architecture, prayer wheels turning by the river, and a pace of life that hasn't changed much in decades. The pass crossing day is demanding — an early start across moraine and snow — but the descent into the green river valleys on the Annapurna side provides a welcome contrast. This is a trek for people who have done the popular routes and want something deeper.",
            ],
            [
                'title' => 'Ghorepani Poon Hill Trek',
                'base_price' => 5400.00,
                'difficulty' => 'Easy',
                'duration_days' => 5,
                'max_altitude' => 3210,
                'image' => 'https://images.unsplash.com/photo-1570535451152-32b014798935?auto=format&fit=crop&w=800&q=80',
                'description' => "Poon Hill is Nepal's most accessible mountain viewpoint trek — a short, well-lodged route through the Annapurna foothills that delivers a panoramic sunrise view of Dhaulagiri, the Annapurna range, and Machhapuchhre without any extreme altitude. The trail passes through rhododendron forest that turns spectacularly red and pink in spring, and the Gurung village of Ghandruk is one of the most photogenic settlements in the country.\n\nThis trek works well as a first Himalayan experience or as a short add-on to a longer Nepal trip. The stone steps to Ulleri on day one set the tone — steep but rewarding — and the lodges along the route are comfortable enough that you don't need a sleeping bag in most seasons. It's a four-to-five-day investment that punches well above its weight in terms of mountain scenery.",
            ],
            [
                'title' => 'Upper Mustang Trek',
                'base_price' => 10000.00,
                'difficulty' => 'Moderate',
                'duration_days' => 17,
                'max_altitude' => 4230,
                'image' => 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=800&q=80',
                'description' => "Upper Mustang is a restricted trans-Himalayan region that was closed to foreigners until 1992, and the landscape shows why early travellers called it the Last Forbidden Kingdom. The route follows the Kali Gandaki upstream from Kagbeni through wind-carved ochre cliffs, ancient cave dwellings, and sparse settlements to the walled city of Lo Manthang — the historical capital of the Kingdom of Lo.\n\nThe appeal here is not altitude but atmosphere. This is a desert landscape at 3,500-4,200 metres, shaped by wind and geological time rather than ice and glacier. Monasteries with 500-year-old murals, painted chortens, and a culture that draws more from Tibet than from Kathmandu give the trek a character unlike anything else in Nepal. The permit cost is higher than standard routes, but the remoteness and cultural depth justify the investment for travellers who want to go beyond mountain photography.",
            ],
            [
                'title' => 'Mardi Himal Trek',
                'base_price' => 6000.00,
                'difficulty' => 'Moderate',
                'duration_days' => 5,
                'max_altitude' => 4500,
                'image' => 'https://images.unsplash.com/photo-1526481280693-3bfa7568e0f3?auto=format&fit=crop&w=800&q=80',
                'description' => "Mardi Himal is a ridge-line trek on the eastern side of the Annapurna Sanctuary that has grown popular for its direct, head-on views of Machhapuchhre and Annapurna South. The trail leaves the forest behind Forest Camp and follows an increasingly exposed ridge through Low Camp and High Camp to a viewpoint at roughly 4,500 metres.\n\nThe route's strength is its efficiency — you get above the treeline and into open alpine terrain faster than on almost any other Annapurna trek. The ridge feels like walking along the spine of the mountains, with steep drops on both sides and panoramic views that build with every hour of elevation. Teahouse accommodation is simpler than on the Annapurna Base Camp trail, which adds to the quieter, less commercial feel. It's a five-to-seven-day investment that delivers big mountain impact.",
            ],
            [
                'title' => 'Gokyo Lakes Trek',
                'base_price' => 9000.00,
                'difficulty' => 'Difficult',
                'duration_days' => 13,
                'max_altitude' => 5357,
                'image' => 'https://images.unsplash.com/photo-1563200781-067a999a4c51?auto=format&fit=crop&w=800&q=80',
                'description' => "The Gokyo Lakes trek is the Everest region's scenic alternative — a route that branches from the main Everest Base Camp trail at Namche Bazaar and follows the Ngozumpa Glacier valley to a series of turquoise glacial lakes at the foot of Cho Oyu. The summit of Gokyo Ri at 5,357 metres provides a view of four 8,000-metre peaks in a single panorama: Everest, Lhotse, Makalu, and Cho Oyu.\n\nThe lakes themselves are the visual centrepiece — genuinely turquoise, set against grey moraine and white ice, with a colour intensity that's hard to believe until you see it in person. The route is quieter than the EBC trail but shares the same Sherpa village culture and lodge infrastructure in the lower sections. For trekkers who've already done Everest Base Camp, this adds a completely different dimension to the Khumbu.",
            ],
            [
                'title' => 'Kanchenjunga Base Camp Trek',
                'base_price' => 9900.00,
                'difficulty' => 'Difficult',
                'duration_days' => 20,
                'max_altitude' => 5143,
                'image' => 'https://images.unsplash.com/photo-1521360144391-766b1a37c02b?auto=format&fit=crop&w=800&q=80',
                'description' => "Kanchenjunga Base Camp is one of Nepal's great expeditionary treks — a three-week journey to the far eastern border region beneath the world's third-highest peak. The route passes through Rai and Limbu villages where local traditions remain strong, climbs through some of the country's most biodiverse forest, and reaches both the north base camp at Pangpema and optionally the south base camp at Oktang.\n\nThis is a trek for experienced walkers who are comfortable with long days, basic facilities, and genuine remoteness. There are no Starbucks-branded coffee shops here — the trail infrastructure is simple and the landscapes are vast. The reward is a sense of wilderness and scale that the more popular regions can no longer offer. You will meet very few other trekkers, and the mountain views in the upper valleys are as dramatic as anything in the Himalaya.",
            ],
            [
                'title' => 'Helambu Trek',
                'base_price' => 5600.00,
                'difficulty' => 'Easy',
                'duration_days' => 7,
                'max_altitude' => 3490,
                'image' => 'https://images.unsplash.com/photo-1517411032315-54ef2cb783bb?auto=format&fit=crop&w=800&q=80',
                'description' => "Helambu is a low-altitude cultural trek north of Kathmandu through Sherpa and Hyolmo villages, forested ridgelines, and wide valley views of the Langtang and Jugal Himal ranges. The route starts at Sundarijal on the edge of the Kathmandu Valley and climbs through Shivapuri National Park before traversing to traditional settlements like Tarkeghyang and Sermathang.\n\nThe trek's main draw is accessibility — you can start and finish by local bus from Kathmandu, it stays below the altitude where acclimatization becomes critical, and the cultural exposure to Hyolmo Buddhist traditions is rich and genuine. Monastery visits, mani walls, and homestay-style lodges give the route a personal, unhurried character. It's ideal for trekkers with limited time or for those who want a gentler introduction to Nepal's hill country before committing to a longer, higher route.",
            ],
        ];
    }

    protected function createDepartures(Trek $trek, float $basePrice, int $durationDays): void
    {
        foreach (range(0, 1) as $index) {
            $startDate = now()->addDays(21 + ($index * 28))->startOfDay();

            $trek->departures()->create([
                'start_date' => $startDate,
                'end_date' => (clone $startDate)->addDays(max(1, $durationDays - 1)),
                'price' => $basePrice,
                'capacity' => 16,
                'booked_seats' => 0,
                'status' => 'Available',
            ]);
        }
    }
}
