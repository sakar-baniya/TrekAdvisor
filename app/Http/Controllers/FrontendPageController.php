<?php

namespace App\Http\Controllers;

use App\Models\GearItem;
use App\Models\Hotel;
use Illuminate\View\View;

class FrontendPageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function faq(): View
    {
        $faqs = [
            ['question' => 'What is included in the trek price?', 'answer' => 'Most treks include guide support, itinerary planning, and the booking structure defined for that route.'],
            ['question' => 'How physically fit do I need to be?', 'answer' => 'Fitness needs vary by route difficulty, duration, and altitude. Each trek page helps set expectations clearly.'],
            ['question' => 'Can I reschedule my booking?', 'answer' => 'Rescheduling depends on availability and the booking policy for the selected departure.'],
            ['question' => 'Do you provide equipment?', 'answer' => 'Some essentials can be arranged through the gear rental section when inventory is available.'],
            ['question' => 'What happens if I get altitude sickness?', 'answer' => 'Trek planning should always include acclimatization, pacing, and proper safety guidance.'],
            ['question' => 'Do I need travel insurance?', 'answer' => 'Insurance is strongly recommended for trekking and high-altitude travel.'],
        ];

        return view('pages.faq', compact('faqs'));
    }

    public function blog(): View
    {
        $posts = collect([
            [
                'category' => 'Hiking Tips',
                'title' => 'How to prepare for your first Himalayan trek',
                'excerpt' => 'Build confidence with a simple training rhythm, thoughtful packing, and a realistic route choice.',
                'author' => 'TrekAdvisor Team',
                'date' => 'March 2026',
                'reading_time' => '5 min read',
            ],
            [
                'category' => 'Gear Reviews',
                'title' => 'Choosing the right backpack for multi-day routes',
                'excerpt' => 'A good backpack should balance fit, weight distribution, and carrying comfort on long days.',
                'author' => 'Field Notes',
                'date' => 'March 2026',
                'reading_time' => '6 min read',
            ],
            [
                'category' => 'Trek Stories',
                'title' => 'Sunrise moments that make mountain journeys unforgettable',
                'excerpt' => 'The best treks are often remembered through small quiet moments, not just summit milestones.',
                'author' => 'Expedition Journal',
                'date' => 'February 2026',
                'reading_time' => '4 min read',
            ],
        ]);

        return view('pages.blog', compact('posts'));
    }

    public function hotels(): View
    {
        $hotels = Hotel::query()
            ->where('status', 'Active')
            ->with(['rooms', 'reviews'])
            ->withMin('rooms', 'price_per_night')
            ->latest()
            ->paginate(9);

        return view('hotels.index', compact('hotels'));
    }

    public function hotelShow(Hotel $hotel): View
    {
        $hotel->load(['rooms', 'reviews.user']);

        return view('hotels.show', compact('hotel'));
    }

    public function gear(): View
    {
        $gearItems = GearItem::query()
            ->with('reviews')
            ->latest()
            ->paginate(12);

        return view('gear.index', compact('gearItems'));
    }

    public function gearShow(GearItem $gearItem): View
    {
        $gearItem->load('reviews.user');

        return view('gear.show', compact('gearItem'));
    }
}
