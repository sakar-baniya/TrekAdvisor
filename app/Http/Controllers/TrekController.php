<?php

namespace App\Http\Controllers;

use App\Models\Trek;
use Illuminate\Http\Request;

class TrekController extends Controller
{
    /**
     * Display a listing of the treks.
     */
    public function index(Request $request)
    {
        $query = Trek::where('status', 'Active');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->string('location')->toString();
            $query->where(function ($q) use ($location) {
                // Location isn't a dedicated column; use title/description match to keep the filter useful.
                $q->where('title', 'like', "%{$location}%")
                    ->orWhere('description', 'like', "%{$location}%");
            });
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->input('max_price'));
        }

        $treks = $query->latest()->paginate(9)->withQueryString();

        return view('treks.index', compact('treks'));
    }

    /**
     * Display the specified trek.
     */
    public function show($slug)
    {
        $trek = Trek::with(['itineraries' => function($q) {
            $q->orderBy('day_number', 'asc');
        }, 'departures' => function($q) {
            $q->where('status', 'Available')
              ->where('start_date', '>', now())
              ->orderBy('start_date', 'asc');
        }])->where('slug', $slug)->firstOrFail();

        // Load reviews separately to show them at the bottom
        $reviews = $trek->reviews()->with('user')->latest()->get();
        $reviewCount = $trek->reviews()->count();
        $avgRating = $reviewCount > 0 ? round($trek->reviews()->avg('rating'), 1) : null;

        return view('treks.show', compact('trek', 'reviews', 'reviewCount', 'avgRating'));
    }
}
