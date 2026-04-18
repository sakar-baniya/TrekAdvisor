<?php

namespace App\Http\Controllers;

use App\Models\Trek;
use App\Models\TrekBooking;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * Yo TrekController controller le trek controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class TrekController extends Controller
{
    /**
     * Display a listing of the treks.
     */
    public function index(Request $request)
    {
        $query = Trek::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });

            // Prioritize title matches
            $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 WHEN title LIKE ? THEN 2 ELSE 3 END ASC", [
                "{$search}%",
                "%{$search}%"
            ]);
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

        if (!$request->filled('search')) {
            $sortBy = $request->input('sort', 'popularity');
            
            switch ($sortBy) {
                case 'price_low':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'rating':
                    $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                    break;
                case 'duration':
                    $query->orderBy('duration_days', 'asc');
                    break;
                case 'popularity':
                default:
                    $query->withCount('reviews')->orderByDesc('reviews_count');
                    break;
            }
        }

        $treks = $query
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('images')
            ->paginate(9)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $treks->items(),
                'meta' => [
                    'current_page' => $treks->currentPage(),
                    'last_page' => $treks->lastPage(),
                    'per_page' => $treks->perPage(),
                    'total' => $treks->total(),
                ],
            ]);
        }

        return view('treks.trek-list', compact('treks'));
    }

    /**
     * Display the specified trek.
     */
    public function show(Trek $trek)
    {
        $trek->load(['itineraries' => function($q) {
            $q->orderBy('day_number', 'asc');
        }, 'departures' => function($q) {
            $q->where('status', 'available')
              ->where('start_date', '>', now())
              ->orderBy('start_date', 'asc');
        }, 'gallery']);

        // Load reviews separately to show them at the bottom
        $reviews = $trek->reviews()->with('user')->latest()->get();
        $reviewCount = $trek->reviews()->count();
        $avgRating = $reviewCount > 0 ? round($trek->reviews()->avg('rating'), 1) : null;

        $canReview = false;
        $userReview = null;

        if (auth()->check()) {
            $canReview = TrekBooking::query()
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereHas('departure', fn($q) => $q->where('trek_id', $trek->id))
                ->exists();

            if ($canReview) {
                $userReview = Review::query()
                    ->where('user_id', auth()->id())
                    ->where('reviewable_type', Trek::class)
                    ->where('reviewable_id', $trek->id)
                    ->first();
            }
        }

        return view('treks.trek-details', compact('trek', 'reviews', 'reviewCount', 'avgRating', 'canReview', 'userReview'));
    }
}

