<?php

namespace App\Http\Controllers;

use App\Models\GearItem;
use App\Models\Hotel;
use App\Models\Trek;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredTreks = Trek::query()
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        $featuredHotels = Hotel::query()
            ->where('status', 'active')
            ->withMin('rooms', 'price_per_night')
            ->latest()
            ->take(4)
            ->get();

        $featuredGearItems = GearItem::query()
            ->where('total_stock', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('featuredTreks', 'featuredHotels', 'featuredGearItems'));
    }
}

