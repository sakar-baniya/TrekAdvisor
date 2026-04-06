<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
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
            ['question' => 'Do you provide equipment?', 'answer' => 'Basic trekking equipment recommendations are provided with each booking. You are encouraged to bring your own gear or rent locally in Pokhara or Kathmandu.'],
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

    public function hotels(Request $request): View
    {
        $query = Hotel::query()
            ->where('status', 'active')
            ->with(['rooms', 'images', 'gallery'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withMin('rooms', 'price_per_night');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->string('location')->toString();
            $query->where('location', 'like', "%{$location}%");
        }

        $hotels = $query->latest()->paginate(9)->withQueryString();

        return view('hotels.index', compact('hotels'));
    }

    public function hotelShow(Hotel $hotel): View
    {
        $hotel->load(['rooms', 'reviews.user', 'gallery', 'images']);
        $hotel->loadCount('reviews');
        $hotel->loadAvg('reviews', 'rating');

        return view('hotels.show', compact('hotel'));
    }

}

