<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Trek;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function home(): View
    {
        $featuredTreks = Trek::query()
            ->where('status', 'active')
            ->with('images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(4)
            ->get();

        $featuredHotels = Hotel::query()
            ->where('status', 'active')
            ->with('images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withMin('rooms', 'price_per_night')
            ->latest()
            ->take(4)
            ->get();

        $testimonials = Review::query()
            ->with(['user', 'reviewable'])
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredTreks', 'featuredHotels', 'testimonials'));
    }

    /**
     * About page.
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Contact page.
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * FAQ page.
     */
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

    /**
     * Blog index page.
     */
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
}
