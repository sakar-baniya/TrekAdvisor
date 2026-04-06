<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Trek;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
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

        $testimonials = \App\Models\Review::query()
            ->with(['user', 'reviewable'])
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredTreks', 'featuredHotels', 'testimonials'));
    }
}

