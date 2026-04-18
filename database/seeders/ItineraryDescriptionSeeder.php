<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use App\Models\Trek;
use Illuminate\Database\Seeder;

/**
 * Seeds detailed, day-by-day itinerary descriptions for all treks.
 * Replaces all existing itinerary rows with complete, accurate data.
 */
class ItineraryDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->data() as $trekId => $days) {
            $trek = Trek::find($trekId);
            if (! $trek) {
                $this->command->warn("Trek ID {$trekId} not found, skipping.");
                continue;
            }

            $trek->itineraries()->delete();

            foreach ($days as $index => $day) {
                Itinerary::create([
                    'trek_id'     => $trekId,
                    'day_number'  => $index + 1,
                    'title'       => $day['title'],
                    'description' => $day['description'],
                ]);
            }

            $this->command->info("Trek [{$trek->title}] — " . count($days) . ' days seeded.');
        }
    }

    private function data(): array
    {
        return [

            // ─── TREK 1 (EBC / Everest Base Camp) ───────────────────────────────────
            1 => [
                [
                    'title'       => 'Fly to Lukla, trek to Phakding',
                    'description' => 'The 35-minute flight from Kathmandu to Lukla (2,840m) is one of the most dramatic in the world, landing on a short uphill runway carved into the mountainside. From Lukla the trail descends through pine forest alongside the Dudh Koshi river to Phakding (2,610m). A gentle first day that lets lungs and legs find their rhythm.',
                ],
                [
                    'title'       => 'Trek to Namche Bazaar',
                    'description' => 'After several prayer-flag-draped suspension bridges, the trail climbs steeply for two hours through rhododendron forest to the Sherpa capital of Namche Bazaar (3,440m). The Saturday market draws traders from across the Khumbu. First partial views of Everest appear on the final approach to town.',
                ],
                [
                    'title'       => 'Acclimatisation day in Namche',
                    'description' => 'A hike to the Everest View Hotel ridge (3,900m) delivers the first clear panorama of Everest, Lhotse, Nuptse, and Ama Dablam before returning to Namche for the night. The altitude gain and descent stimulates red blood cell production without over-stressing the body. Visit the Sherpa Culture Museum in the afternoon.',
                ],
                [
                    'title'       => 'Trek to Tengboche',
                    'description' => 'The trail contours high above the Dudh Koshi gorge with continuous mountain views, drops to the river crossing at Phungi Thanka, then climbs through silver birch and bamboo to Tengboche Monastery (3,867m). Evening puja at the monastery — the low rumble of horns echoing against Ama Dablam — is a moment few trekkers forget.',
                ],
                [
                    'title'       => 'Trek to Dingboche',
                    'description' => 'Leaving the treeline behind, the trail enters the high-altitude desert that defines the upper Khumbu. Dingboche (4,410m) sits in a wide valley ringed by ancient stone field walls. One of the highest permanently inhabited settlements in the world. Spend the afternoon resting; the air here carries noticeably less oxygen.',
                ],
                [
                    'title'       => 'Acclimatisation hike above Dingboche',
                    'description' => 'A hike to the Nangkartshang ridge (5,083m) brings close views of Makalu, Cho Oyu, and the Island Peak massif. The extra altitude gained today materially reduces the risk of altitude sickness higher on the route. Return to Dingboche for a warm meal and early night.',
                ],
                [
                    'title'       => 'Trek to Lobuche',
                    'description' => 'The valley narrows as the trail follows the lateral moraine of the Khumbu Glacier north. Stone monuments mark the memorial area for climbers lost on Everest — a sobering and moving place. Lobuche (4,940m) is a collection of basic lodges at the foot of the glacier; winds often howl by late afternoon.',
                ],
                [
                    'title'       => 'Trek to Gorak Shep, visit Everest Base Camp',
                    'description' => 'The walk to Gorak Shep (5,164m) crosses rocky glacier moraine — tiring and uneven underfoot. After dropping packs at the lodge, the trail continues for three to four hours across boulders to Everest Base Camp (5,364m). The scale and silence of the Khumbu Icefall above is extraordinary. Return to Gorak Shep for the night.',
                ],
                [
                    'title'       => 'Sunrise from Kala Patthar, descend to Pheriche',
                    'description' => 'A pre-dawn start for the climb to Kala Patthar (5,545m) gives the clearest skies and the most celebrated sunrise view in trekking — the south face of the world\'s highest mountain lit gold above a sea of jagged peaks. Descend through Lobuche to Pheriche (4,240m), where altitude clinics operate during the main seasons.',
                ],
                [
                    'title'       => 'Trek to Namche Bazaar',
                    'description' => 'The long descent retraces the familiar trail south through Tengboche and back to Namche. The warmer, greener air below the treeline arrives quickly. Treat yourself to a real coffee and a hot shower in Namche — both feel earned.',
                ],
                [
                    'title'       => 'Trek to Lukla',
                    'description' => 'The final full walking day retraces the lower Khumbu trail through Phakding and back to Lukla. Take time on the suspension bridges and in the rhododendron forest one last time before the flight. Celebrate over dinner in Lukla.',
                ],
                [
                    'title'       => 'Fly back to Kathmandu',
                    'description' => 'An early check-in at Tenzing-Hillary Airport for the flight back to Kathmandu. On arrival, transfer to the hotel, shower, and enjoy the warmth of the city. The afternoon is free for shopping in Thamel, sightseeing at Boudhanath, or simply sleeping.',
                ],
            ],

            // ─── TREK 2 (Annapurna Circuit) ──────────────────────────────────────────
            2 => [
                [
                    'title'       => 'Drive from Kathmandu to Chame',
                    'description' => 'An early jeep departure heads north along the Prithvi Highway, turning up the Marsyangdi river valley. The road is rough beyond Besisahar and passes through rice terraces, then pine forest as elevation climbs. Chame (2,710m) is the district headquarters of Manang with warm lodges and first clear views of Lamjung Himal.',
                ],
                [
                    'title'       => 'Trek from Chame to Pisang',
                    'description' => 'The trail follows the Marsyangdi through a narrow gorge carved into overhanging cliffs, crosses to the right bank, then opens out dramatically. A remarkable natural rock wall — over a kilometre long and hundreds of metres high — defines the left side. Upper Pisang (3,300m) has clear sightlines to Annapurna II and III.',
                ],
                [
                    'title'       => 'Continue from Pisang to Manang',
                    'description' => 'The high route via Ghyaru and Ngawal traverses the north flank at around 3,700m with continuous panoramas of the entire Annapurna massif. Vegetation gives way to sparse scrub and juniper as the trail descends to Manang (3,519m), a well-equipped village with bakeries and an altitude clinic.',
                ],
                [
                    'title'       => 'Acclimatization day in Manang',
                    'description' => 'The Himalayan Rescue Association runs an altitude awareness lecture in Manang each afternoon — strongly recommended. For an acclimatization hike, climb to the moraine lake at Gangapurna (3,900m) or push to Ice Lake (4,600m) for panoramic views of Gangapurna, Tarke Kang, and the Chulu peaks. Never sleep higher than you hiked.',
                ],
                [
                    'title'       => 'Trek from Manang to Yak Kharka',
                    'description' => 'Above Manang the valley opens into high plateau cropped short by grazing yaks. The trail climbs gradually through a lunar landscape of boulders and thin scrub. Yak Kharka (4,018m) is a cluster of stone lodges used primarily by herders. The night sky at this elevation, away from all light pollution, is extraordinary.',
                ],
                [
                    'title'       => 'Continue from Yak Kharka to Thorong Phedi',
                    'description' => 'A short but steep climb to Thorong Phedi (4,450m), the staging point for the pass. The lodges fill early, so arriving by early afternoon matters. Spend the time resting, drinking water, and eating a carbohydrate-rich meal. Most groups set an alarm for 4am; the pass must be crossed before afternoon winds build.',
                ],
                [
                    'title'       => 'Cross Thorong La, descend to Muktinath',
                    'description' => 'The pre-dawn start in sub-zero temperatures is part of the experience. The switchback climb to Thorong La (5,416m) takes four to five hours with slow, deliberate steps. The descent on the Mustang side drops almost 1,700m to the sacred pilgrimage town of Muktinath (3,800m), with its ancient flame temple and 108 water spouts.',
                ],
                [
                    'title'       => 'Travel from Muktinath to Jomsom',
                    'description' => 'Most trekkers take a jeep down the Kali Gandaki valley to Jomsom (2,720m) after the demanding pass crossing. The valley runs between Dhaulagiri and Annapurna — the deepest gorge in the world. Jomsom has a functioning airfield and the last proper infrastructure before the road continues south.',
                ],
                [
                    'title'       => 'Move from Jomsom to Pokhara',
                    'description' => 'A short flight or long jeep ride brings you back to Pokhara (820m). The sudden drop in altitude makes the warm, humid air feel almost tropical. Phewa Lake in afternoon sun, with Machapuchare reflected on the water, is one of Nepal\'s most-photographed scenes.',
                ],
                [
                    'title'       => 'Rest day in Pokhara',
                    'description' => 'Pokhara deserves a full day. Take a rowing boat on Phewa Lake to the Barahi temple island, visit the International Mountain Museum, or simply sit at a lakeside cafe watching paragliders drift down from Sarangkot. A professional massage after two weeks on trail is strongly recommended.',
                ],
                [
                    'title'       => 'Return from Pokhara to Kathmandu',
                    'description' => 'A 25-minute flight or 6-7 hour tourist bus returns you to Kathmandu. The bus follows the Trishuli river through rice-terraced landscape. On arrival, Thamel awaits with its shops and restaurants.',
                ],
                [
                    'title'       => 'Flexible reserve day',
                    'description' => 'Mountain itineraries rarely go perfectly to schedule. Weather delays on the Jomsom flight, extra acclimatization, or a rest day forced by illness are all common. This buffer day protects your international flight connection.',
                ],
                [
                    'title'       => 'Departure or sightseeing day',
                    'description' => 'Depending on flight time, there may be a final morning for last-minute souvenir shopping in Thamel or a farewell lunch of momos. Most international flights depart late at night, so the day is typically relaxed.',
                ],
                [
                    'title'       => 'Trip close and onward travel',
                    'description' => 'Transfer to Tribhuvan International Airport for the homeward flight. The Annapurna Circuit has been walked by mountaineers and traders for centuries and remains one of the finest long-distance mountain journeys anywhere in the world.',
                ],
            ],

            // ─── TREK 3 (Annapurna Base Camp) ────────────────────────────────────────
            3 => [
                [
                    'title'       => 'Drive to Nayapul, trek to Tikhedhunga',
                    'description' => 'A 90-minute drive west of Pokhara drops you at the roadhead of Nayapul (1,070m). The trail crosses the Modi Khola on a suspension bridge and follows the river upstream through terraced farmland and small bazaars. Tikhedhunga (1,540m) sits above a dramatic waterfall.',
                ],
                [
                    'title'       => 'Trek to Ghorepani',
                    'description' => 'The famous stone staircase out of Tikhedhunga — more than 3,000 steps — delivers you into magnificent rhododendron forest that blazes red and pink in spring. After passing through Ulleri the trail reaches the ridge at Ghorepani (2,874m), where views of Dhaulagiri announce real altitude gained.',
                ],
                [
                    'title'       => 'Sunrise at Poon Hill, trek to Tadapani',
                    'description' => 'Alarm at 5am for the short climb to Poon Hill (3,210m). Annapurna South, Machapuchare, Dhaulagiri, and dozens of other peaks light up at sunrise in one of the Himalaya\'s great spectacles. Back for breakfast in Ghorepani, then the trail rolls east through rhododendron and oak forest to Tadapani (2,630m).',
                ],
                [
                    'title'       => 'Trek to Chhomrong',
                    'description' => 'A long descent leads to the river crossing at Kimrong Khola, then a steep climb to Chhomrong (2,170m) — the last permanent village before the sanctuary. Stone-paved streets, varied guesthouses, and the first clear views of Annapurna South and Hiunchuli make this a highlight of the approach.',
                ],
                [
                    'title'       => 'Trek to Dovan',
                    'description' => 'Beyond Chhomrong the trail descends steeply to the river, then climbs through bamboo and rhododendron forest with multiple river crossings on wooden bridges. The vegetation grows denser and more primeval as altitude rises. Dovan (2,600m) sits in a narrow gorge with only a handful of teahouses.',
                ],
                [
                    'title'       => 'Trek to Machapuchare Base Camp',
                    'description' => 'The bamboo belt gives way to alpine scrub as you climb past Himalaya Hotel to Deurali (3,230m). Above Deurali the valley opens dramatically — sheer ice-plastered rock walls close in on both sides as you enter the inner sanctuary. Machapuchare Base Camp (3,700m) stares up at the unclimbed fish-tail peak looming almost vertically above.',
                ],
                [
                    'title'       => 'Trek to Annapurna Base Camp',
                    'description' => 'The final approach to Annapurna Base Camp (4,130m) crosses open glacier moraines under a 360-degree amphitheatre: Annapurna I (8,091m), Annapurna South, Hiunchuli, Machapuchare, Gangapurna, and Glacier Dome form a complete circle of high Himalaya. The feeling of being enclosed inside this natural arena is unlike anywhere else on the trekking circuit.',
                ],
                [
                    'title'       => 'Descend to Bamboo',
                    'description' => 'Wake early for sunrise colours on the peaks before beginning the long descent. Altitude drops quickly — from above 4,000m back below the treeline and into bamboo forest. Take your time on the steeper sections to protect your knees. Bamboo Lodge (2,310m) is a comfortable overnight stop.',
                ],
                [
                    'title'       => 'Trek to Jhinu Danda',
                    'description' => 'A shorter walking day whose reward is earned: Jhinu Danda has a natural hot spring 20 minutes below the village on the bank of the Modi Khola. After a week on high trail, soaking in the warm sulphur water is genuinely restorative. The village sits on a ridge with views back toward the white mountains just returned from.',
                ],
                [
                    'title'       => 'Trek to Nayapul, drive to Pokhara',
                    'description' => 'The final walking day follows the lower Modi valley to the road at Nayapul. A waiting vehicle returns you to Pokhara in about 90 minutes. Freshen up at the hotel, then enjoy an evening lakeside — Phewa Lake reflects the last light on Machapuchare in a near-perfect mirror image.',
                ],
            ],

            // ─── TREK 4 (Langtang Valley) ─────────────────────────────────────────────
            4 => [
                [
                    'title'       => 'Drive from Kathmandu to Syabrubesi',
                    'description' => 'A 7-8 hour drive north of Kathmandu on winding mountain roads reaches Syabrubesi (1,503m), the gateway to Langtang. The route passes Dhunche and the Langtang National Park checkpoint. The Trishuli river gorge drops away dramatically to the left, with granite cliffs and roadside waterfalls throughout.',
                ],
                [
                    'title'       => 'Trek to Lama Hotel',
                    'description' => 'The trail enters the Langtang river valley alongside a fast glacial river. Dense subtropical forest — ferns, giant trees, and hanging moss — creates a tunnel along the lower trail. Langur monkeys and red pandas inhabit this section. Lama Hotel (2,380m) is a cluster of teahouses in a forest clearing.',
                ],
                [
                    'title'       => 'Trek to Langtang Village',
                    'description' => 'The forest thins and the valley opens as the trail climbs toward high country. Yak pastures and mani walls begin to appear alongside the path. The village was devastated in the 2015 earthquake and has been extensively rebuilt by the Tamang community who call it home. Views of Langtang Lirung (7,227m) grow dramatically more imposing.',
                ],
                [
                    'title'       => 'Trek to Kyanjin Gompa',
                    'description' => 'A relatively short but rewarding walk delivers you to Kyanjin Gompa (3,870m), the highest settlement in the valley. A working cheese factory produces excellent hard cheese and yak-butter tea. The old gompa is still active. Grassy meadows surround the village and views upstream toward the glacier and Langtang Lirung are stunning.',
                ],
                [
                    'title'       => 'Acclimatisation hike to Kyanjin Ri',
                    'description' => 'The steep climb from Kyanjin Gompa to Kyanjin Ri (4,773m) rewards with the finest 360-degree view in the valley: Langtang Lirung close enough to hear ice calving, Gangchenpo to the east, the full length of the Langtang Glacier below, and on clear days the distant Ganesh Himal range. Two to three hours up, one hour down.',
                ],
                [
                    'title'       => 'Descend to Lama Hotel',
                    'description' => 'The return journey descends faster and kinder on the lungs, though demanding on the knees. The trail retraces through Langtang village and into the forest below, where birdsong and warm air feel almost startling after days at altitude. Lama Hotel provides good food and the satisfaction of a full valley crossing completed.',
                ],
                [
                    'title'       => 'Trek to Syabrubesi, drive to Kathmandu',
                    'description' => 'The final morning walk descends through forest back to Syabrubesi, crossing the river on a suspension bridge. A vehicle waits for the return drive to Kathmandu (7-8 hours), which can be broken with a lunch stop in Dhunche.',
                ],
            ],

            // ─── TREK 5 (Manaslu Circuit) ─────────────────────────────────────────────
            5 => [
                [
                    'title'       => 'Drive from Kathmandu to Machha Khola',
                    'description' => 'An early jeep drive to Machha Khola (870m), the usual start point for the Manaslu Circuit. The route passes through Arughat along the Budhi Gandaki river on rough roads taking 8-10 hours. The landscape shifts from Kathmandu urban sprawl to deep river gorges and dense forested ridges.',
                ],
                [
                    'title'       => 'Trek to Jagat',
                    'description' => 'The trail follows the Budhi Gandaki through a subtropical gorge of exceptional wildness. Waterfalls pour from every side valley, the river roars through narrow channels, and bamboo overhangs the path. Jagat (1,340m) is the last village before the restricted area boundary.',
                ],
                [
                    'title'       => 'Trek to Deng',
                    'description' => 'Beyond Jagat the restricted area begins and the trail becomes noticeably emptier and more remote. The gorge scenery is increasingly dramatic with sheer rock walls and thundering cataracts. Deng (1,804m) is a Tibetan-influenced village with prayer flags and mani walls marking the cultural transition underway.',
                ],
                [
                    'title'       => 'Trek to Namrung',
                    'description' => 'The valley opens and the landscape becomes more Himalayan in character. Pine and rhododendron replace the tropical vegetation of the lower gorge. Namrung (2,630m) offers the first proper views of Manaslu (8,163m) — the eighth highest mountain in the world — rearing its summit pyramid above surrounding ridges.',
                ],
                [
                    'title'       => 'Trek to Samagaon',
                    'description' => 'The Buddhist village of Samagaon (3,526m) sits in a wide valley immediately below the south face of Manaslu. The Himalayan Rescue Association maintains a post here during trek season. The Pungyen Gompa, a 20-minute walk above the village, gives outstanding views of the mountain and glacier below it.',
                ],
                [
                    'title'       => 'Acclimatisation day at Samagaon',
                    'description' => 'A well-spent rest day. The recommended acclimatization hike climbs to Manaslu Base Camp (4,800m), providing a close-up of the massive south face and the expedition camp used during climbing seasons. The extra altitude materially prepares the body for Larkya La ahead.',
                ],
                [
                    'title'       => 'Trek to Samdo',
                    'description' => 'A short stage allowing further adjustment to high altitude. Samdo (3,860m) sits close to the Tibetan border; on clear days trade caravans are visible along the ridgeline to the north. The village is home to Tibetan refugees and the flat-roofed stone houses and elaborate chortens reflect that heritage.',
                ],
                [
                    'title'       => 'Trek to Dharmasala',
                    'description' => 'A gradual climb to Dharmasala (also called Larkya Phedi, 4,460m), the high camp below Larkya La. Accommodation is basic and warmth comes from yak-dung stoves. Dinner should be substantial; tomorrow is the hardest day of the circuit. Sleep as early as possible.',
                ],
                [
                    'title'       => 'Cross Larkya La, descend to Bimthang',
                    'description' => 'A 3-4am start is standard. The climb to Larkya La (5,160m) takes four to five hours in frozen conditions. The pass is a wide, wind-raked col with one of the finest mountain panoramas in the Himalaya: Himlung, Cheo Himal, Annapurna II, and the full Manaslu massif. The descent to Bimthang (3,590m) through boulder fields and meadows takes a further three hours.',
                ],
                [
                    'title'       => 'Trek to Gho',
                    'description' => 'A pleasant descending day through alpine pasture and rhododendron forest. Vegetation thickens rapidly as altitude drops and temperature rises noticeably. Gho (2,515m) is a small village in a cultivated valley that feels almost lush after the high desert of the circuit.',
                ],
                [
                    'title'       => 'Trek to Dharapani, drive to Besisahar',
                    'description' => 'The trail drops to Dharapani on the Annapurna Circuit route, where a vehicle can be arranged to Besisahar or Kathmandu. The jeep road follows the Marsyangdi valley through terraced fields. Besisahar has proper hotels and bus connections to Kathmandu.',
                ],
                [
                    'title'       => 'Drive back to Kathmandu',
                    'description' => 'The final drive to Kathmandu takes 5-6 hours on the Prithvi Highway following the Trishuli river south. Arrival by afternoon gives time for a hot shower and a proper meal — a quiet celebration of completing one of Nepal\'s most demanding and rewarding circuits.',
                ],
            ],

            // ─── TREK 6 (Ghorepani Poon Hill) ────────────────────────────────────────
            6 => [
                [
                    'title'       => 'Drive to Nayapul, trek to Tikhedhunga',
                    'description' => 'A 90-minute drive from Pokhara to Nayapul (1,070m) marks the start of the trail. The path crosses the Modi Khola and follows the river upstream through terraced farmland. Tikhedhunga (1,540m) sits above a dramatic cascade and is a comfortable first-day destination.',
                ],
                [
                    'title'       => 'Trek to Ghorepani',
                    'description' => 'The climb begins with the famous stone staircase — a relentless but beautiful ascent through dense rhododendron forest blazing red and pink in spring. The trail passes Ulleri before easing onto a forested ridge walk to Ghorepani (2,874m). Views of Dhaulagiri to the north announce real altitude.',
                ],
                [
                    'title'       => 'Sunrise at Poon Hill, trek to Ghandruk',
                    'description' => 'Wake before 5am for the 45-minute walk to Poon Hill (3,210m). As the sun crests the ridge, Dhaulagiri, the Annapurnas, and Machapuchare are lit in alpenglow — one of the great spectacles of Himalayan trekking. After breakfast the trail descends east to the Gurung village of Ghandruk (1,940m), famous for its stone houses and Gurung Heritage Museum.',
                ],
                [
                    'title'       => 'Trek to Nayapul, drive to Pokhara',
                    'description' => 'A gentle descent along the lower Modi Khola valley returns you to Nayapul for the vehicle back to Pokhara. The short walk through farmland and orchards is a calm finale. Back in Pokhara by midday for a lakeside lunch and final views of the mountains from Phewa Lake.',
                ],
            ],

            // ─── TREK 7 (Upper Mustang) ──────────────────────────────────────────────
            7 => [
                [
                    'title'       => 'Fly from Pokhara to Jomsom',
                    'description' => 'An early morning flight from Pokhara in a small aircraft takes about 20 minutes, delivering you to Jomsom (2,720m) in the Kali Gandaki valley. Afternoon winds frequently close the airstrip, so all flights depart in the morning. The flight gives close views of the Annapurna and Dhaulagiri massifs.',
                ],
                [
                    'title'       => 'Trek to Kagbeni',
                    'description' => 'Kagbeni (2,810m) is the checkpoint where your Restricted Area Permit is verified for the first time. The village sits at the confluence of two rivers — a tightly packed cluster of mud-brick houses, chortens, and a monastery that feels genuinely medieval. Beyond Kagbeni, the landscape becomes the Tibetan plateau terrain of Upper Mustang.',
                ],
                [
                    'title'       => 'Trek to Chele',
                    'description' => 'The trail crosses the Kali Gandaki and climbs through extraordinary red, ochre, and grey badland formations. Wind is the dominant feature of Mustang — it can reach gale force by midday and walking is always best done in the morning. Chele (3,050m) is a traditional Mustangi village with white-painted houses and a small monastery.',
                ],
                [
                    'title'       => 'Trek to Syangboche',
                    'description' => 'A long day on high ridges with views across the plateau to the peaks of the Tibetan border. The landscape is treeless — all fuel is yak dung — and settlements are sparse and self-contained. Syangboche (3,800m) is a small overnight stop on the plateau.',
                ],
                [
                    'title'       => 'Trek to Lo Manthang',
                    'description' => 'The walled city of Lo Manthang (3,840m) is the cultural and historical capital of the former Mustang kingdom. Inside the walls sit four large monasteries containing centuries-old murals and thangka paintings, the royal palace, and a community of Loba people whose culture remains predominantly Tibetan. Walking through the gates for the first time is genuinely arresting.',
                ],
                [
                    'title'       => 'Exploration day in Lo Manthang',
                    'description' => 'A full day to explore Lo Manthang monasteries with a local guide. The Thubchen Gompa and Jampa Gompa contain remarkable 15th-century frescoes being restored by conservation teams. The cave monastery of Ghar Gompa above the city is worth the extra climb. Evening puja in one of the active monasteries is an atmospheric end to the day.',
                ],
                [
                    'title'       => 'Trek to Drakmar',
                    'description' => 'The return route takes a different southern path, passing through the dramatic coloured cliffs of the Chhoser area. Ancient cave dwellings — visible at implausible heights in the cliff face — dot this section. Drakmar (3,810m) means "red earth" and the surrounding cliffs are a vivid terracotta.',
                ],
                [
                    'title'       => 'Trek to Kagbeni',
                    'description' => 'The descent off the plateau retraces the Kali Gandaki valley south. Wind is generally at your back coming down — a small mercy after the headwinds of the journey up. Kagbeni is a welcome return to slightly thicker air.',
                ],
                [
                    'title'       => 'Trek to Jomsom, fly to Pokhara',
                    'description' => 'A short walk back to Jomsom for the morning flight to Pokhara before the afternoon winds arrive. From Pokhara, most travellers return to Kathmandu by flight or tourist bus the same afternoon or following morning.',
                ],
            ],

            // ─── TREK 8 (Mardi Himal) ────────────────────────────────────────────────
            8 => [
                [
                    'title'       => 'Drive to Kande, trek to Forest Camp',
                    'description' => 'A 30-minute drive from Pokhara to Kande (1,770m) begins the Mardi Himal approach. The trail climbs immediately through terraced farmland and then dense rhododendron and oak forest. Forest Camp (2,520m) is a small cluster of teahouses in a quiet clearing — cool and smelling of damp moss.',
                ],
                [
                    'title'       => 'Trek to Low Camp',
                    'description' => 'The forest steepens and the rhododendrons grow taller as elevation increases. In spring the branches are laden with blooms and the forest floor is carpeted with fallen petals. Low Camp (2,985m) emerges onto a ridge with first views of Machapuchare\'s distinctive double summit directly ahead.',
                ],
                [
                    'title'       => 'Trek to High Camp',
                    'description' => 'The trail breaks free of the treeline onto open ridgeline below Mardi Himal\'s long south ridge. High Camp (3,580m) provides the best sustained mountain views on the trek — Annapurna South, Hiunchuli, Machapuchare, and the main Annapurna range arranged in a dramatic arc. Cloud often fills the valley far below.',
                ],
                [
                    'title'       => 'Hike to View Point, descend to Sidhing',
                    'description' => 'An early morning hike from High Camp to the Mardi Himal viewpoint (4,500m) delivers the closest approach to Machapuchare available on any established trek. The fish-tail summit rises almost vertically above, flanked by hanging glaciers. Return to High Camp for breakfast, then descend the Sidhing ridge through rhododendron forest to the farming community of Sidhing (1,700m).',
                ],
                [
                    'title'       => 'Trek to Lwang, drive to Pokhara',
                    'description' => 'A final morning walk through terraced fields and bamboo groves reaches Lwang village, where vehicles depart for Pokhara. The Mardi Himal trek is compact but delivers high-quality mountain scenery with fewer crowds than the Annapurna Base Camp route — ideal for trekkers wanting a shorter, wilder experience.',
                ],
            ],

            // ─── TREK 9 (Gokyo Lakes) ────────────────────────────────────────────────
            9 => [
                [
                    'title'       => 'Fly to Lukla, trek to Phakding',
                    'description' => 'The 35-minute Kathmandu-Lukla flight is the gateway to the Khumbu. From Lukla (2,840m) the trail descends through pine forest to the Dudh Koshi river and follows it upstream to Phakding (2,610m). A gentle first day that lets lungs and legs find their pace.',
                ],
                [
                    'title'       => 'Trek to Namche Bazaar',
                    'description' => 'After several prayer-flag-draped suspension bridges, the trail climbs steeply for two hours to Namche Bazaar (3,440m). The Saturday market draws traders from across the Khumbu. First partial views of Everest appear on the final approach.',
                ],
                [
                    'title'       => 'Acclimatisation day at Namche',
                    'description' => 'Mandatory rest. The hike to the Everest View Hotel ridge (3,900m) delivers a clear panorama of Everest, Lhotse, Nuptse, and Ama Dablam. The altitude gain and descent stimulates acclimatization without overstressing the body.',
                ],
                [
                    'title'       => 'Trek to Dole via Mong La',
                    'description' => 'The Gokyo route branches west from Namche, climbing past Khumjung and over the Mong La ridge (3,970m) before descending to Phortse Thanka and climbing again through alpine meadows to Dole (4,038m). This valley is quieter than the Everest route; yaks outnumber trekkers for most of the day.',
                ],
                [
                    'title'       => 'Trek to Machhermo',
                    'description' => 'The valley narrows as the trail climbs to Machhermo (4,470m). The Himalayan Rescue Association maintains a post here during trek season. From the meadows above, the first views of the Ngozumpa Glacier — the longest in the Himalaya outside the polar regions — become visible.',
                ],
                [
                    'title'       => 'Trek to Gokyo',
                    'description' => 'The final approach to Gokyo (4,790m) follows the eastern moraine of the Ngozumpa Glacier. The emerald Gokyo Lake appears suddenly as you crest the moraine — stunningly turquoise against grey rock and white peaks. The village has good teahouses with a direct view across the lake to Cho Oyu (8,188m).',
                ],
                [
                    'title'       => 'Sunrise hike to Gokyo Ri',
                    'description' => 'Gokyo Ri (5,357m) is one of the finest viewpoints in the entire Khumbu. From the summit you see Everest, Makalu, Lhotse, Cho Oyu, and the full length of the Ngozumpa Glacier. Many consider this panorama superior to Kala Patthar. The return trip takes three to four hours from the village.',
                ],
                [
                    'title'       => 'Explore upper lakes, descend to Dole',
                    'description' => 'A morning walk north along the glacier moraine to the third and fourth lakes gives the remotest, quietest walking of the entire route. The glacier below is a vast wasteland of ice and boulders. Return to Gokyo for lunch, then begin the descent south through Machhermo back to Dole.',
                ],
                [
                    'title'       => 'Trek to Namche Bazaar',
                    'description' => 'A long descent day retracing the Bhote Koshi valley back to Namche. The altitude drop from almost 5,000m to 3,440m is dramatic and the lungs feel the benefit immediately. Namche feels almost urban after a week in the high Gokyo valley.',
                ],
                [
                    'title'       => 'Trek to Lukla',
                    'description' => 'The final full day retraces the lower Khumbu trail through Phakding back to Lukla. The pace is brisk — everyone is keen to reach the end. Celebrate over dinner and a Star beer before the morning flight.',
                ],
                [
                    'title'       => 'Fly to Kathmandu',
                    'description' => 'An early flight returns you to Kathmandu in 35 minutes. The rest of the day is free for final shopping, sightseeing at Boudhanath or Swayambhunath, or simply relaxing before onward travel.',
                ],
            ],

            // ─── TREK 10 (Kanchenjunga Base Camp) ────────────────────────────────────
            10 => [
                [
                    'title'       => 'Fly to Taplejung, trek to Mitlung',
                    'description' => 'A short flight from Kathmandu to Suketar airport above Taplejung (2,420m) begins one of Nepal\'s most remote treks. The trail descends steeply into the Tamur river valley to Mitlung (1,000m) through terraced farmland and subtropical forest. The enormous ridges and deep valleys immediately signal the scale of what lies ahead.',
                ],
                [
                    'title'       => 'Trek to Chirwa',
                    'description' => 'Following the Tamur river upstream through a valley of exceptional biodiversity. Orchids, giant ferns, and bamboo create a lush corridor. The lower Kanchenjunga region is one of Nepal\'s richest ecological zones. The population is predominantly Rai and Limbu, with architecture and customs distinct from the Sherpa communities of the Khumbu.',
                ],
                [
                    'title'       => 'Trek to Sekathum',
                    'description' => 'The Tamur valley narrows and the trail climbs more seriously. Sekathum (1,640m) sits at the confluence of the Tamur and Ghunsa rivers — the branching point between the north and south base camp routes. A rest stop here lets you orient yourself in this complex topography.',
                ],
                [
                    'title'       => 'Trek to Amjilosa',
                    'description' => 'The Ghunsa Khola valley becomes the main route north. The trail climbs through extraordinary old-growth forest — one of the finest stands of temperate forest in the eastern Himalaya — with regular sightings of langur monkeys, giant squirrels, and abundant birdlife. Amjilosa (2,490m) is a small Tibetan community with simple lodges.',
                ],
                [
                    'title'       => 'Trek to Ghunsa',
                    'description' => 'Ghunsa (3,595m) is the main village of the upper valley — a prosperous Tibetan settlement with an active monastery and the first clear views of high peaks including Jannu (7,710m), one of the most dramatic mountains in the world and a worthy objective in its own right.',
                ],
                [
                    'title'       => 'Acclimatisation day in Ghunsa',
                    'description' => 'A rest day before the demanding high sections ahead. A hike up the ridge above the village (to around 4,200m) delivers views of Kanchenjunga\'s north side and the Jannu massif. The monastery above Ghunsa is worth visiting — the resident lamas maintain one of the more active Buddhist communities in the eastern Himalaya.',
                ],
                [
                    'title'       => 'Trek to Khambachen',
                    'description' => 'The valley becomes wilder and emptier as the trail climbs north. Khambachen (4,050m) is the last permanent settlement on this approach with basic but adequate teahouses. Kanchenjunga was visible from Ghunsa; from Khambachen the full north face is revealed in increasingly close detail.',
                ],
                [
                    'title'       => 'Trek to Lhonak',
                    'description' => 'Above Khambachen the trail crosses glacial moraine and open meadow. Lhonak (4,790m) is a high plateau campsite at the foot of the north-face approach. The remoteness here is absolute — no phone signal, no generator hum, just wind and peaks. Kanchenjunga North Base Camp is visible in the valley above.',
                ],
                [
                    'title'       => 'Hike to Kanchenjunga North Base Camp',
                    'description' => 'The day hike from Lhonak to Kanchenjunga North Base Camp (5,143m) crosses the outer moraine for the most dramatic close-up views of the world\'s third highest mountain. The serac walls above are immense; the silence is broken only by the occasional crack and rumble of ice shifts. Return to Lhonak by afternoon.',
                ],
                [
                    'title'       => 'Return to Ghunsa',
                    'description' => 'A long descent from Lhonak through Khambachen back to Ghunsa covers significant altitude. The valley is familiar now but no less beautiful on the return. The lodges in Ghunsa feel almost palatial after nights at high camp.',
                ],
                [
                    'title'       => 'Trek via Sele La to Tseram',
                    'description' => 'The most demanding single day: crossing Sele La (4,290m) and Sinion La (4,646m) to reach the Yalung valley on the south side of Kanchenjunga. Both passes are serious — snow patches possible year-round and cloud can close conditions quickly. Tseram (3,870m) on the southern approach has basic teahouses and is a welcome end to a long effort.',
                ],
                [
                    'title'       => 'Hike to Kanchenjunga South Base Camp',
                    'description' => 'A day hike from Tseram via Ramche to Kanchenjunga South Base Camp (4,940m) gives a completely different perspective — the south face hanging glaciers and icefalls visible from directly below. Return to Tseram for the night.',
                ],
                [
                    'title'       => 'Trek to Yamphudin',
                    'description' => 'The trail descends rapidly through rhododendron and magnolia forest. Yamphudin (2,080m) is a Limbu village with a notably different atmosphere from the Tibetan high country — colourful dress, local rakshi, and warm hospitality. A remarkable cultural transition within a single day\'s walking.',
                ],
                [
                    'title'       => 'Trek to Mamankhe, drive or fly out',
                    'description' => 'The final walking day descends through terraced farming landscape to Mamankhe or Taplejung, where a jeep road connects to Suketar airport or continues to the Terai. The Kanchenjunga circuit is rightly considered one of the most challenging and rewarding treks in Nepal, combining extreme remoteness, cultural richness, and mountain scenery of the highest order.',
                ],
            ],

            // ─── TREK 11 (Helambu) ───────────────────────────────────────────────────
            11 => [
                [
                    'title'       => 'Drive to Sundarijal, trek to Chisapani',
                    'description' => 'A 45-minute drive from Kathmandu to Sundarijal (1,360m) begins the Helambu circuit at the edge of Shivapuri Nagarjun National Park. The trail climbs steeply through temperate forest — excellent for birdwatching — to the ridge at Chisapani (2,215m). The sunset view across Kathmandu valley to the white Himalayan skyline is one of the finest accessible from the capital.',
                ],
                [
                    'title'       => 'Trek to Kutumsang',
                    'description' => 'A long ridge walk north through rhododendron and oak forest passes through several small Tamang settlements. The trail undulates along the Shivapuri ridge — never extremely high but consistently involving. Kutumsang (2,470m) is a larger Tamang village with decent lodges and views north toward the Langtang peaks.',
                ],
                [
                    'title'       => 'Trek to Tharipati',
                    'description' => 'The forest transitions from oak and rhododendron to silver fir and bamboo as elevation increases. Tharipati (3,490m) is the high point of the Helambu route — a windswept ridge with clear views of Dorje Lakpa, Ganesh Himal, and the Langtang range. Cloud permitting, this is the best mountain panorama on the entire circuit.',
                ],
                [
                    'title'       => 'Trek to Melamchi Gaon',
                    'description' => 'The trail descends north into the warm Sherpa village of Melamchi Gaon (2,530m). The Sherpa population in Helambu is long-established and maintains its own dialect and customs. The village has a working monastery and several family-run guesthouses with notably good home cooking.',
                ],
                [
                    'title'       => 'Trek to Tarke Ghyang',
                    'description' => 'The largest and most prosperous village on the circuit. Tarke Ghyang (2,591m) has a population of several hundred and a beautiful old monastery perched above the village containing valuable thangka paintings and a large Buddha statue. Local Sherpa guides are available for day excursions to surrounding ridges.',
                ],
                [
                    'title'       => 'Trek to Sermathang, drive to Kathmandu',
                    'description' => 'A final morning walk to Sermathang (2,620m) where a jeep road connects to the main highway. The drive to Kathmandu takes two to three hours through Melamchi and the Indrawati river valley. The Helambu circuit is an excellent introduction to Himalayan trekking — accessible from Kathmandu, culturally rich, and varied in terrain without requiring extreme altitude or complex logistics.',
                ],
            ],

        ];
    }
}
