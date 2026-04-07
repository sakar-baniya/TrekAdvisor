<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Trek;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrekController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
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

            $treks = $query
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->with('images')
                ->paginate(9);

            return response()->json([
                'data' => $treks->items(),
                'meta' => [
                    'current_page' => $treks->currentPage(),
                    'last_page' => $treks->lastPage(),
                    'per_page' => $treks->perPage(),
                    'total' => $treks->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}