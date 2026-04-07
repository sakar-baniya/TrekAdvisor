<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * API index for hotels.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = \App\DTOs\Hotel\HotelFilterData::fromRequest($request);

            $query = Hotel::query()
                ->where('status', 'active')
                ->with(['rooms', 'images', 'gallery'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->withMin('rooms', 'price_per_night');

            if ($filters->search) {
                $search = $filters->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });

                // Prioritize name matches
                $query->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END ASC", [
                    "{$search}%",
                    "%{$search}%"
                ]);
            }

            if ($filters->location) {
                $query->where('location', 'like', "%{$filters->location}%");
            }

            if ($filters->minPrice !== null) {
                $query->whereHas('rooms', function ($q) use ($filters) {
                    $q->where('price_per_night', '>=', $filters->minPrice);
                });
            }

            if ($filters->maxPrice !== null) {
                $query->whereHas('rooms', function ($q) use ($filters) {
                    $q->where('price_per_night', '<=', $filters->maxPrice);
                });
            }

            if (!$filters->search) {
                switch ($filters->sortBy) {
                    case 'price_low':
                        $query->orderBy('rooms_min_price_per_night', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('rooms_min_price_per_night', 'desc');
                        break;
                    case 'rating':
                        $query->orderByDesc('reviews_avg_rating');
                        break;
                    case 'popularity':
                    default:
                        $query->orderByDesc('reviews_count');
                        break;
                }
            }

            $hotels = $query->paginate(9);

            return response()->json([
                'data' => $hotels->items(),
                'meta' => [
                    'current_page' => $hotels->currentPage(),
                    'last_page' => $hotels->lastPage(),
                    'per_page' => $hotels->perPage(),
                    'total' => $hotels->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
