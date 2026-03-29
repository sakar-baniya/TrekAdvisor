<?php

namespace Database\Seeders;

use App\Models\Departure;
use App\Models\Trek;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CuratedTrekSeeder extends Seeder
{
    public function run(): void
    {
        collect($this->trekData())->each(function (array $trekData): void {
            $lookupSlugs = $trekData['lookup_slugs'] ?? [];

            $trek = Trek::query()
                ->whereIn('slug', $lookupSlugs)
                ->orWhere('title', $trekData['title'])
                ->first();

            if (! $trek) {
                $trek = new Trek();
            }

            $trek->fill([
                'title' => $trekData['title'],
                'slug' => Str::slug($trekData['title']),
                'description' => $trekData['description'],
                'base_price' => $trekData['base_price'],
                'difficulty' => $trekData['difficulty'],
                'duration_days' => $trekData['duration_days'],
                'max_altitude' => $trekData['max_altitude'],
                'image' => $trek->image,
                'status' => 'Active',
            ]);

            $trek->save();

            $this->syncDepartures($trek, $trekData['base_price'], $trekData['duration_days']);
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function trekData(): array
    {
        return [
            [
                'title' => 'Annapurna Base Camp',
                'lookup_slugs' => ['annapurna-base-camp'],
                'base_price' => 780.00,
                'difficulty' => 'Moderate',
                'duration_days' => 10,
                'max_altitude' => 4130,
                'description' => 'A classic teahouse trek through terraced villages, rhododendron forest, and the Modi Khola valley to the Annapurna Sanctuary. Most standard itineraries run 9 to 11 trekking days and reach Annapurna Base Camp at 4,130 meters, making it one of Nepal’s best-value high mountain routes.',
            ],
            [
                'title' => 'Langtang Valley Trek',
                'lookup_slugs' => ['langtang-valley-trek'],
                'base_price' => 720.00,
                'difficulty' => 'Moderate',
                'duration_days' => 8,
                'max_altitude' => 3870,
                'description' => 'The Langtang Valley Trek combines close-up alpine scenery with Tamang culture north of Kathmandu. A typical 7 to 9 day itinerary climbs gradually to Kyanjin Gompa at 3,870 meters, offering glacier views, yak pastures, and one of Nepal’s strongest short-to-mid length trekking experiences.',
            ],
            [
                'title' => 'Manaslu Circuit Trek',
                'lookup_slugs' => ['manaslu-circuit-trek'],
                'base_price' => 1390.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5106,
                'description' => 'A longer restricted-area journey circling Mount Manaslu through remote Buddhist villages and dramatic high valley terrain. Standard programs take around 14 to 16 days and cross Larkya La at 5,106 meters, so costs are higher due to permits, guide requirements, and the trek’s logistical complexity.',
            ],
            [
                'title' => 'Ghorepani Poon Hill Trek',
                'lookup_slugs' => ['ghorepani-poon-hill-trek'],
                'base_price' => 325.00,
                'difficulty' => 'Easy',
                'duration_days' => 5,
                'max_altitude' => 3210,
                'description' => 'A short and approachable Annapurna region trek known for sunrise from Poon Hill and comfortable village lodges along the route. Most itineraries take 4 to 5 days and top out at roughly 3,210 meters, which makes it a popular introduction to trekking in Nepal.',
            ],
            [
                'title' => 'Upper Mustang Trek',
                'lookup_slugs' => ['upper-mustang-trek'],
                'base_price' => 2235.00,
                'difficulty' => 'Moderate',
                'duration_days' => 17,
                'max_altitude' => 4230,
                'description' => 'Upper Mustang is a restricted trans-Himalayan route into Nepal’s former walled kingdom, known for desert landscapes, monasteries, and Tibetan-influenced culture. Public itineraries commonly run 14 to 17 days and reach around 4,230 meters, with pricing driven mainly by permits and transport.',
            ],
            [
                'title' => 'Mardi Himal Trek',
                'lookup_slugs' => ['mardi-himal-trek'],
                'base_price' => 450.00,
                'difficulty' => 'Moderate',
                'duration_days' => 5,
                'max_altitude' => 4500,
                'description' => 'A compact ridge-line trek east of the Annapurna Base Camp trail, popular for forest camps, open viewpoints, and a strong mountain panorama in a shorter time frame. Most trips take 5 to 7 days and reach the Mardi Himal viewpoint or base camp area at about 4,500 meters.',
            ],
            [
                'title' => 'Gokyo Lakes Trek',
                'lookup_slugs' => ['gokyo-lakes-trek'],
                'base_price' => 1150.00,
                'difficulty' => 'Difficult',
                'duration_days' => 13,
                'max_altitude' => 5357,
                'description' => 'An Everest-region alternative focused on the turquoise Gokyo Lakes and the summit viewpoint of Gokyo Ri. Typical itineraries take about 12 to 13 days and rise to 5,357 meters, offering a quieter but still demanding high-altitude trek with exceptional Khumbu scenery.',
            ],
            [
                'title' => 'Kanchenjunga Base Camp Trek',
                'lookup_slugs' => ['kanchenjunga-base-camp-trek'],
                'base_price' => 2400.00,
                'difficulty' => 'Difficult',
                'duration_days' => 20,
                'max_altitude' => 5143,
                'description' => 'One of Nepal’s most remote long-distance treks, the Kanchenjunga Base Camp route visits far eastern valleys, Rai and Limbu villages, and the base camp region below the world’s third-highest mountain. Standard journeys span around 20 to 24 days and require strong endurance and logistics planning.',
            ],
            [
                'title' => 'Helambu Trek',
                'lookup_slugs' => ['helambu-trek'],
                'base_price' => 550.00,
                'difficulty' => 'Easy',
                'duration_days' => 7,
                'max_altitude' => 3490,
                'description' => 'A lower-altitude cultural trek north of Kathmandu through Sherpa and Hyolmo villages, forested ridges, and wide valley views. Most itineraries run 6 to 7 days and stay below the more technical high-Himalayan zones, making it a gentle entry point for new trekkers.',
            ],
            [
                'title' => 'Everest Base Camp Trek',
                'lookup_slugs' => ['everest-base-camp-trek'],
                'base_price' => 1399.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5364,
                'description' => 'Nepal’s signature Khumbu route follows the Dudh Koshi valley through Namche, Tengboche, and Gorak Shep to Everest Base Camp. Most standard packages take 12 to 14 days and reach 5,364 meters at base camp, with the trip graded difficult because of altitude, daily elevation gain, and the length of the walk.',
            ],
            [
                'title' => 'Annapurna Circuit Trek',
                'lookup_slugs' => ['test-tek', 'annapurna-circuit-trek'],
                'base_price' => 1190.00,
                'difficulty' => 'Difficult',
                'duration_days' => 14,
                'max_altitude' => 5416,
                'description' => 'The Annapurna Circuit remains one of Nepal’s benchmark long treks, linking lush lower valleys with the dry trans-Himalayan landscape beyond Manang. Most classic itineraries take around 14 days and cross Thorong La at 5,416 meters, combining big altitude, changing terrain, and broad route variety.',
            ],
        ];
    }

    protected function syncDepartures(Trek $trek, float $basePrice, int $durationDays): void
    {
        $departures = $trek->departures()->orderBy('start_date')->get();

        if ($departures->isEmpty()) {
            $this->createDefaultDepartures($trek, $basePrice, $durationDays);
            return;
        }

        $departures->each(function (Departure $departure, int $index) use ($basePrice, $durationDays): void {
            $startDate = now()->addDays(21 + ($index * 28))->startOfDay();

            $departure->update([
                'start_date' => $startDate,
                'end_date' => (clone $startDate)->addDays(max(1, $durationDays - 1)),
                'price' => $basePrice,
                'capacity' => max($departure->capacity, 12),
                'status' => 'Available',
            ]);
        });
    }

    protected function createDefaultDepartures(Trek $trek, float $basePrice, int $durationDays): void
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
