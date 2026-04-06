<?php

namespace Database\Seeders;

use App\Models\Trek;
use Illuminate\Database\Seeder;

class CuratedItinerarySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->itineraries() as $slug => $days) {
            $trek = Trek::query()->where('slug', $slug)->first();

            if (! $trek) {
                continue;
            }

            $trek->itineraries()->delete();

            foreach ($days as $index => $day) {
                $trek->itineraries()->create([
                    'day_number' => $index + 1,
                    'title' => $day['title'],
                    'description' => $day['description'],
                ]);
            }
        }
    }

    protected function itineraries(): array
    {
        return [
            'annapurna-base-camp' => [
                ['title' => 'Drive to Pokhara and prepare for the trail', 'description' => 'Travel from Kathmandu to Pokhara and use the afternoon to check permits, organize your bags, and brief with your guide before the trek begins.'],
                ['title' => 'Drive to Nayapul and trek to Ghandruk', 'description' => 'A short drive brings you to the trailhead at Nayapul. Climb through terraced fields and village paths to Ghandruk, a large Gurung settlement with broad views of Annapurna South and Machhapuchhre.'],
                ['title' => 'Trek from Ghandruk to Chhomrong', 'description' => 'Descend to the Kimrong Khola and climb steadily to Chhomrong, the main gateway village for the Annapurna Sanctuary route. The trail balances stone steps, forest, and ridge views.'],
                ['title' => 'Continue from Chhomrong to Dovan', 'description' => 'Drop to Chhomrong Khola, climb through bamboo and rhododendron forest past Sinuwa and Bamboo, and continue into the narrower upper valley to Dovan for an overnight stop.'],
                ['title' => 'Ascend from Dovan to Deurali', 'description' => 'The forest thins as the route rises through Himalaya and Hinku Cave to Deurali. Waterfalls, avalanche paths, and steeper terrain begin to signal the higher sanctuary environment.'],
                ['title' => 'Reach Annapurna Base Camp', 'description' => 'Pass Machhapuchhre Base Camp and enter the wide amphitheater of the Annapurna Sanctuary. The final stretch to Annapurna Base Camp offers dramatic views of Annapurna I, Hiunchuli, Annapurna South, and surrounding ice walls.'],
                ['title' => 'Descend to Bamboo', 'description' => 'Retrace the route down through Machhapuchhre Base Camp and Deurali, then continue through the forest to Bamboo. The long descent is easier on altitude but still a full trekking day.'],
                ['title' => 'Trek from Bamboo to Jhinu Danda', 'description' => 'Climb back toward Chhomrong, then descend to Jhinu Danda. If time and energy allow, visit the nearby hot springs along the river for a classic post-trek recovery stop.'],
                ['title' => 'Walk out to Nayapul and return to Pokhara', 'description' => 'Follow lower village trails through Siwai and the Modi valley to the roadhead, then drive back to Pokhara. The afternoon is free for lakeside rest and a comfortable post-trek evening.'],
                ['title' => 'Return to Kathmandu', 'description' => 'Travel back to Kathmandu by road or short domestic flight connection, marking the end of the Annapurna Base Camp journey.'],
            ],
            'langtang-valley-trek' => [
                ['title' => 'Drive from Kathmandu to Syabrubesi', 'description' => 'Leave Kathmandu early and follow the Trishuli valley north to Syabrubesi, the traditional starting point for the Langtang Valley trek.'],
                ['title' => 'Trek from Syabrubesi to Lama Hotel', 'description' => 'Cross the river and enter Langtang National Park. The path climbs through oak, bamboo, and rhododendron forest beside the Langtang Khola to Lama Hotel.'],
                ['title' => 'Continue to Langtang Village', 'description' => 'Ascend through Ghodatabela as the forest opens into a wider glacial valley. Arrive at rebuilt Langtang Village with improved mountain views and stronger Tibetan-influenced culture.'],
                ['title' => 'Trek to Kyanjin Gompa', 'description' => 'A shorter acclimatization-friendly day leads across yak pastures and mani walls to Kyanjin Gompa, the high settlement at the head of the valley.'],
                ['title' => 'Acclimatization hike above Kyanjin', 'description' => 'Spend the day acclimatizing with an optional climb toward Kyanjin Ri or a viewpoint above the village for glacier panoramas and close views of Langtang Lirung.'],
                ['title' => 'Descend to Lama Hotel', 'description' => 'Follow the same valley trail down past Langtang Village and Ghodatabela, dropping back into the forest zone at Lama Hotel.'],
                ['title' => 'Walk back to Syabrubesi', 'description' => 'Continue descending along the river through bamboo forest and park checkpoints until you return to Syabrubesi.'],
                ['title' => 'Drive from Syabrubesi to Kathmandu', 'description' => 'Take the return drive back to Kathmandu, carrying one of Nepal\'s best short high-mountain valley experiences back to the city.'],
            ],
            'manaslu-circuit-trek' => [
                ['title' => 'Drive from Kathmandu to Machha Khola', 'description' => 'A long road transfer leaves Kathmandu and follows the lower Budhi Gandaki corridor to Machha Khola, the practical trailhead for the Manaslu circuit.'],
                ['title' => 'Trek from Machha Khola to Jagat', 'description' => 'Walk beside the Budhi Gandaki through small settlements, hot spring areas, and suspension bridges to Jagat, where the controlled-area trekking route properly begins.'],
                ['title' => 'Continue from Jagat to Deng', 'description' => 'Climb through terraced hillsides and mixed forest, passing villages like Philim and Ekle Bhatti before reaching Deng in a narrower gorge section.'],
                ['title' => 'Trek from Deng to Namrung', 'description' => 'The trail gains altitude gradually through forest and Tibetan-influenced settlements. As the valley broadens, stone houses and mountain views become more pronounced approaching Namrung.'],
                ['title' => 'Walk from Namrung to Samagaon', 'description' => 'A scenic day past Lho and Shyala brings some of the best first views of Manaslu. The route finishes in Samagaon, one of the circuit\'s key acclimatization villages.'],
                ['title' => 'Acclimatization day in Samagaon', 'description' => 'Use this day to rest and acclimatize, with optional side hikes toward Manaslu Base Camp, Birendra Lake, or nearby ridges above the village.'],
                ['title' => 'Trek from Samagaon to Samdo', 'description' => 'A shorter high-altitude day leads to Samdo across broad yak-grazing terrain. This controlled ascent helps prepare for the pass approach.'],
                ['title' => 'Move from Samdo to Dharamsala', 'description' => 'Continue upward on a shorter but steeper day to Dharamsala, the final staging point below Larkya La. Keep the pace easy and focus on hydration and rest.'],
                ['title' => 'Cross Larkya La and descend to Bimthang', 'description' => 'The circuit\'s biggest day starts before dawn. Climb to Larkya La Pass at 5,106 meters, then descend carefully across moraine and alpine terrain to Bimthang.'],
                ['title' => 'Descend from Bimthang to Tilije', 'description' => 'Leave the harsher high-altitude zone behind as the route drops into greener forest and river valley terrain on the Annapurna side of the pass.'],
                ['title' => 'Walk from Tilije to Dharapani', 'description' => 'A final trekking day follows mixed village paths and road sections to Dharapani, where the Manaslu circuit joins the Annapurna trail network.'],
                ['title' => 'Drive from Dharapani to Besisahar', 'description' => 'Take the jeep road down through the Marsyangdi valley to Besisahar, ending the remote trekking portion of the journey.'],
                ['title' => 'Return from Besisahar to Kathmandu', 'description' => 'Drive back to Kathmandu and enjoy a well-earned recovery after one of Nepal\'s great high passes and remote circuit treks.'],
                ['title' => 'Buffer and departure day', 'description' => 'Keep a flexible final day in Kathmandu for rest, sightseeing, or onward travel in case mountain weather or road delays affect the schedule.'],
            ],
            'ghorepani-poon-hill-trek' => [
                ['title' => 'Drive to Nayapul and trek to Ulleri', 'description' => 'Drive from Pokhara to Nayapul and begin walking through lower village paths and farmland to Ulleri, known for its long staircase climb and hillside lodges.'],
                ['title' => 'Trek from Ulleri to Ghorepani', 'description' => 'The route rises through rhododendron forest with frequent views back across the lower Annapurna hills before reaching Ghorepani.'],
                ['title' => 'Hike Poon Hill and continue to Tadapani', 'description' => 'Start early for sunrise at Poon Hill, then continue through forested ridge trails toward Tadapani with changing panoramas of the Annapurna and Dhaulagiri ranges.'],
                ['title' => 'Descend from Tadapani to Ghandruk', 'description' => 'Walk down through woodland and village trails to Ghandruk, one of the region\'s most attractive Gurung settlements.'],
                ['title' => 'Trek out and return to Pokhara', 'description' => 'Descend from Ghandruk to the roadhead and drive back to Pokhara, completing a short trek centered on classic viewpoint scenery.'],
            ],
            'upper-mustang-trek' => [
                ['title' => 'Drive from Kathmandu to Pokhara', 'description' => 'Travel west to Pokhara and spend the evening preparing for the mountain flight and restricted-area trail ahead.'],
                ['title' => 'Fly to Jomsom and trek to Kagbeni', 'description' => 'Take the short dramatic flight into the Kali Gandaki valley and walk onward to medieval Kagbeni, the gateway settlement to Upper Mustang.'],
                ['title' => 'Enter Upper Mustang and trek to Chele', 'description' => 'Complete permit formalities and begin walking through dry, wind-shaped terrain, cave-like cliffs, and the first villages of the old Mustang kingdom.'],
                ['title' => 'Trek from Chele to Syangboche', 'description' => 'Cross a series of ridges and passes, pass through small painted settlements, and gain a better sense of Mustang\'s distinctive arid landscape.'],
                ['title' => 'Continue from Syangboche to Ghami', 'description' => 'Walk across broad valleys, mani walls, and ochre cliffs to Ghami, one of the larger villages on the route to Lo Manthang.'],
                ['title' => 'Trek from Ghami to Tsarang', 'description' => 'Cross open country and descend into Tsarang, where whitewashed buildings, fortress-style architecture, and monasteries define the classic Upper Mustang atmosphere.'],
                ['title' => 'Reach Lo Manthang', 'description' => 'The route approaches the walled city of Lo Manthang, the historical capital of Mustang and the major cultural highlight of the trek.'],
                ['title' => 'Explore Lo Manthang', 'description' => 'Spend a full day in and around Lo Manthang visiting monasteries, palace areas, and optional side valleys such as Chhoser if logistics allow.'],
                ['title' => 'Trek from Lo Manthang to Drakmar', 'description' => 'Begin the return by taking an alternate route where possible, passing cave sites and red cliff landscapes that look different in the changing light.'],
                ['title' => 'Walk from Drakmar to Ghiling', 'description' => 'Descend through broad, dry valleys, small monasteries, and long traverses shaped by wind and exposed mountain weather.'],
                ['title' => 'Trek from Ghiling to Chhuksang', 'description' => 'Continue descending through the stark trans-Himalayan terrain toward lower Mustang villages and easier walking gradients.'],
                ['title' => 'Return from Chhuksang to Jomsom', 'description' => 'Pass back through Kagbeni and continue along the Kali Gandaki corridor to Jomsom, where the trekking portion effectively ends.'],
                ['title' => 'Fly from Jomsom to Pokhara', 'description' => 'Take the mountain flight back to Pokhara and enjoy a lower-altitude recovery day after the dusty high-desert trail.'],
                ['title' => 'Drive from Pokhara to Kathmandu', 'description' => 'Return to Kathmandu by road or domestic connection, bringing the core restricted-area itinerary to a close.'],
                ['title' => 'Reserve day in Kathmandu', 'description' => 'Keep a buffer day in Kathmandu because flights in and out of Jomsom are weather-sensitive and often require schedule flexibility.'],
                ['title' => 'Departure or onward travel', 'description' => 'Use the final day for departure or additional sightseeing, depending on flight timing and how the Mustang travel days unfolded.'],
                ['title' => 'Extra cultural and weather buffer', 'description' => 'This final day keeps the longer 17-day structure practical, absorbing any flight shifts while preserving the overall quality of the itinerary.'],
            ],
            'mardi-himal-trek' => [
                ['title' => 'Drive to Kande and trek to Forest Camp', 'description' => 'Leave Pokhara by road for Kande and begin walking via Australian Camp, Potana, and Deurali before turning onto the quieter forest trail to Forest Camp.'],
                ['title' => 'Trek from Forest Camp to Low Camp', 'description' => 'Climb through rhododendron and oak forest where the first clear views of Machhapuchhre begin to open through the trees.'],
                ['title' => 'Continue from Low Camp to High Camp', 'description' => 'The route leaves the denser forest and follows open ridge terrain with increasingly wide Annapurna South, Hiunchuli, and Machhapuchhre views.'],
                ['title' => 'Hike to Mardi Himal viewpoint and descend to Badal Danda', 'description' => 'Start early for the high viewpoint area below Mardi Himal, then descend carefully to Badal Danda for a more comfortable overnight at lower altitude.'],
                ['title' => 'Descend via Siding and drive to Pokhara', 'description' => 'Leave the ridge behind and descend to Siding village before taking transport back to Pokhara, ending a short but scenic Annapurna-side trek.'],
            ],
            'gokyo-lakes-trek' => [
                ['title' => 'Fly to Lukla and trek to Phakding', 'description' => 'Take the mountain flight into Lukla and begin the trek with an easier first day down the Dudh Koshi valley to Phakding.'],
                ['title' => 'Trek from Phakding to Namche Bazaar', 'description' => 'Cross multiple suspension bridges and climb steadily to Namche, the main Sherpa trading town and acclimatization hub of the Everest region.'],
                ['title' => 'Acclimatization day in Namche', 'description' => 'Use the day for active acclimatization with a short hike above Namche, often toward Everest View Hotel or Khumjung, before sleeping lower again.'],
                ['title' => 'Trek from Namche to Dole', 'description' => 'Leave the main Everest Base Camp route and branch toward the quieter Gokyo valley, climbing above forested terrain to Dole.'],
                ['title' => 'Continue from Dole to Machhermo', 'description' => 'Follow the broadening valley with increasingly alpine scenery and classic Khumbu mountain views while gaining altitude gradually.'],
                ['title' => 'Reach Gokyo', 'description' => 'Pass the first of the Gokyo Lakes and continue to the main settlement beside the turquoise lake system below Cho Oyu.'],
                ['title' => 'Hike Gokyo Ri and explore the lakes basin', 'description' => 'Climb Gokyo Ri for one of the great Everest-region panoramas, then spend the remainder of the day exploring the lake basin at a measured pace.'],
                ['title' => 'Optional walk toward the upper lakes and return to Gokyo', 'description' => 'A longer acclimatization-oriented day can visit the higher lakes or a viewpoint toward the Ngozumpa Glacier before returning to Gokyo.'],
                ['title' => 'Descend from Gokyo to Machhermo', 'description' => 'Leave the upper lake basin and retrace the route down to Machhermo, reducing altitude while keeping views across the glacier valley.'],
                ['title' => 'Trek from Machhermo to Namche', 'description' => 'Continue the descent through Dole and Mong La back to Namche, rejoining the busier central Khumbu trail.'],
                ['title' => 'Walk from Namche to Lukla', 'description' => 'Drop back through Monjo and Phakding, then finish the last climb to Lukla for the final overnight on the trail.'],
                ['title' => 'Fly from Lukla to Kathmandu', 'description' => 'Take the return mountain flight to Kathmandu and enjoy a lower-altitude recovery evening in the city.'],
                ['title' => 'Buffer and departure day', 'description' => 'Keep the final day available for flight delays or onward travel, as Everest-region air logistics often require flexibility.'],
            ],
            'kanchenjunga-base-camp-trek' => [
                ['title' => 'Fly to Bhadrapur and drive to Taplejung', 'description' => 'Begin with an internal flight to southeast Nepal and continue by road toward Taplejung, the gateway district for Kanchenjunga trekking.'],
                ['title' => 'Trek from Taplejung to Chirwa', 'description' => 'Start walking through cultivated hillsides and mixed ethnic villages on a lower, warmer section of the route.'],
                ['title' => 'Continue from Chirwa to Sekathum', 'description' => 'The trail enters a narrower river corridor with repeated climbs and descents before reaching Sekathum.'],
                ['title' => 'Trek from Sekathum to Amjilosa', 'description' => 'Cross suspension bridges and climb through bamboo forest into a more remote mountain environment.'],
                ['title' => 'Walk from Amjilosa to Gyabla', 'description' => 'Gain altitude gradually through steeper forest and village terrain as the route transitions toward Tibetan-influenced settlements.'],
                ['title' => 'Trek from Gyabla to Ghunsa', 'description' => 'A key cultural day leads into Ghunsa, one of the major settlements of the upper valley and an important acclimatization base.'],
                ['title' => 'Acclimatization day in Ghunsa', 'description' => 'Rest and acclimatize with short walks above the village while preparing for the colder, more remote upper section of the trek.'],
                ['title' => 'Continue from Ghunsa to Kambachen', 'description' => 'Follow the widening glacial valley beneath impressive peaks and moraine landscapes to Kambachen.'],
                ['title' => 'Acclimatization day in Kambachen', 'description' => 'Use the extra day to support altitude adaptation with a short side hike and recovery before continuing upward.'],
                ['title' => 'Trek from Kambachen to Lhonak', 'description' => 'Move into starker high-altitude terrain above the vegetation line, walking among glacial moraine and broad Himalayan views.'],
                ['title' => 'Visit Pangpema and return to Lhonak', 'description' => 'Make the demanding excursion to Pangpema, the Kanchenjunga North Base Camp viewpoint, then return to Lhonak for the night.'],
                ['title' => 'Descend from Lhonak to Ghunsa', 'description' => 'Drop back through Kambachen and continue to Ghunsa, losing altitude and leaving the harshest terrain behind.'],
                ['title' => 'Trek from Ghunsa to Sele Le', 'description' => 'Climb out of the main valley toward the high pass section linking the north and south sides of the Kanchenjunga area.'],
                ['title' => 'Cross the passes to Tseram', 'description' => 'A demanding day crosses the Sele Le area and descends into the quieter southern valley toward Tseram.'],
                ['title' => 'Trek from Tseram to Ramche', 'description' => 'Follow the upper valley toward the southern base camp approach through open alpine terrain and sparse high lodges.'],
                ['title' => 'Hike to Oktang and return', 'description' => 'Make the excursion toward the Kanchenjunga South Base Camp viewpoint at Oktang, then return to lower accommodation for the night.'],
                ['title' => 'Descend to Tortong', 'description' => 'Leave the southern upper valley and descend through forest and river terrain to Tortong.'],
                ['title' => 'Continue from Tortong to Yamphudin', 'description' => 'A mixed trail of climbs, descents, and village sections leads to Yamphudin, where the landscape softens again.'],
                ['title' => 'Trek from Yamphudin to Khebang', 'description' => 'Continue through cultivated mid-hill country and rural settlements as the route heads toward road access.'],
                ['title' => 'Drive onward and return to Kathmandu', 'description' => 'Complete the long exit journey by vehicle and flight connections back toward Kathmandu, concluding one of Nepal\'s longest and least crowded classic treks.'],
            ],
            'helambu-trek' => [
                ['title' => 'Drive to Sundarijal and trek to Chisapani', 'description' => 'Leave Kathmandu for Sundarijal, enter Shivapuri National Park, and climb through forest and hill settlements to Chisapani.'],
                ['title' => 'Trek from Chisapani to Kutumsang', 'description' => 'Follow wooded ridges and village paths with intermittent Himalayan views before reaching Kutumsang.'],
                ['title' => 'Continue from Kutumsang to Tharepati', 'description' => 'Gain height through fir, oak, and rhododendron forest to the high point of the trek at Tharepati.'],
                ['title' => 'Descend from Tharepati to Tarkeghyang', 'description' => 'Leave the ridge and descend into Helambu\'s cultural heartland, ending at Tarkeghyang, a prominent Hyolmo village with monastery traditions.'],
                ['title' => 'Walk from Tarkeghyang to Sermathang', 'description' => 'A gentler day traverses between villages, mani walls, and open viewpoints toward the Langtang and Jugal Himal ranges.'],
                ['title' => 'Descend to Melamchi Bazaar', 'description' => 'Continue downhill through village landscapes and lower-elevation forest toward Melamchi Bazaar.'],
                ['title' => 'Drive back to Kathmandu', 'description' => 'Take the road transfer back to Kathmandu, ending a short cultural trek close to the capital.'],
            ],
            'everest-base-camp-trek' => [
                ['title' => 'Fly to Lukla and trek to Phakding', 'description' => 'Start with the classic mountain flight to Lukla, then follow the Dudh Koshi valley on an easier first day to Phakding.'],
                ['title' => 'Trek from Phakding to Namche Bazaar', 'description' => 'Cross suspension bridges, enter Sagarmatha National Park, and climb the long hill to Namche Bazaar, the commercial hub of the Khumbu.'],
                ['title' => 'Acclimatization day in Namche', 'description' => 'Use the day for active acclimatization with a short climb to Everest View Hotel, Khumjung, or a nearby ridge before returning to Namche to sleep.'],
                ['title' => 'Trek from Namche to Tengboche', 'description' => 'Traverse above the valley with repeated Everest and Ama Dablam views, then descend to the river and climb to Tengboche Monastery.'],
                ['title' => 'Continue from Tengboche to Dingboche', 'description' => 'Walk through Deboche and Pangboche into the more open upper valley as tree cover thins and the high-altitude terrain becomes more severe.'],
                ['title' => 'Acclimatization day in Dingboche', 'description' => 'Take a rest and acclimatization hike above Dingboche, often toward Nangkartshang, to prepare safely for the route above 4,500 meters.'],
                ['title' => 'Trek from Dingboche to Lobuche', 'description' => 'Climb gradually past Dughla and the Everest memorial area before reaching Lobuche in a cold, stark glacial setting.'],
                ['title' => 'Reach Gorakshep and visit Everest Base Camp', 'description' => 'Walk to Gorakshep, leave heavy bags if desired, and continue to Everest Base Camp before returning to Gorakshep for the night.'],
                ['title' => 'Climb Kala Patthar and descend to Pheriche', 'description' => 'Start early for the best Everest sunrise views from Kala Patthar, then descend out of the harshest high-altitude zone toward Pheriche.'],
                ['title' => 'Continue from Pheriche to Namche', 'description' => 'Follow the long descent through Pangboche and Tengboche before finishing the day back in Namche with warmer air and fuller facilities.'],
                ['title' => 'Walk from Namche to Lukla', 'description' => 'Descend the Dudh Koshi valley and finish the final climb to Lukla for the last night on the trek.'],
                ['title' => 'Fly from Lukla to Kathmandu', 'description' => 'Take the return mountain flight to Kathmandu and enjoy recovery time after the main trekking effort.'],
                ['title' => 'Weather buffer day in Kathmandu', 'description' => 'Keep a spare day in the city because Lukla flights are weather-dependent and often affect the exact timing of the return.'],
                ['title' => 'Departure or extra city time', 'description' => 'Use the final day for departure or a last free day in Kathmandu, completing the full 14-day Everest Base Camp structure.'],
            ],
            'annapurna-circuit-trek' => [
                ['title' => 'Drive from Kathmandu to Chame', 'description' => 'A long road transfer follows the Marsyangdi valley toward Chame, where the high mountain section of the Annapurna Circuit begins.'],
                ['title' => 'Trek from Chame to Pisang', 'description' => 'Walk through pine forest, river gorges, and increasingly dramatic valley walls on the first full trekking day of the circuit.'],
                ['title' => 'Continue from Pisang to Manang', 'description' => 'Take a higher or lower route depending on conditions and arrive in Manang, the major acclimatization settlement before the pass.'],
                ['title' => 'Acclimatization day in Manang', 'description' => 'Use the day for a side hike to a viewpoint or lake above the village, then return to Manang to sleep lower and support altitude adaptation.'],
                ['title' => 'Trek from Manang to Yak Kharka', 'description' => 'Gradually leave the village zone and move into high grazing country where the air is thinner and the pace must slow.'],
                ['title' => 'Continue from Yak Kharka to Thorong Phedi', 'description' => 'A shorter day positions you below Thorong La while preserving energy for the circuit\'s most demanding stage.'],
                ['title' => 'Cross Thorong La and descend to Muktinath', 'description' => 'Start before dawn to cross Thorong La Pass at 5,416 meters, then descend carefully to the pilgrimage town of Muktinath.'],
                ['title' => 'Travel from Muktinath to Jomsom', 'description' => 'Continue down the dry Kali Gandaki corridor by trek or road transfer to Jomsom, the transport hub of lower Mustang.'],
                ['title' => 'Move from Jomsom to Pokhara', 'description' => 'Fly or drive back to Pokhara after leaving the high trail network behind.'],
                ['title' => 'Rest day in Pokhara', 'description' => 'Use the day for recovery by Phewa Lake, repacking, and a break after the circuit\'s long pass crossing and travel transitions.'],
                ['title' => 'Return from Pokhara to Kathmandu', 'description' => 'Travel back to Kathmandu, completing the classic cross-region outline of the Annapurna Circuit.'],
                ['title' => 'Flexible reserve day', 'description' => 'Keep one spare day for weather, road conditions, or extra rest after the circuit.'],
                ['title' => 'Departure or sightseeing day', 'description' => 'Use the second buffer day for departure or additional city sightseeing depending on overall travel timing.'],
                ['title' => 'Trip close and onward travel', 'description' => 'The final day completes the 14-day structure with enough flexibility for a realistic mountain travel schedule.'],
            ],
        ];
    }
}


