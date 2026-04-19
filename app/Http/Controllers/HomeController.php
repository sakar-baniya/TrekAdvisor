<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Trek;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Home Controller: Website ko main landing pages (Home, About, etc.) handle garchha.
 */
class HomeController extends Controller
{
    /**
     * Home Page: Trending treks ra hotels haru front page ma dekhaune.
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
     * About Page: Company ko info page.
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Contact Page: Contact form vako screen.
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Travel Guide Page: Yatri lai darsan dine guides haru.
     */
    public function travelGuide(): View
    {
        return view('pages.travel-guide');
    }
}
