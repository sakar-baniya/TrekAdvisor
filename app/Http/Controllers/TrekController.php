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

        // Optional filtering by difficulty or price range could go here
        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $treks = $query->latest()->paginate(9);

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

        return view('treks.show', compact('trek', 'reviews'));
    }
}
