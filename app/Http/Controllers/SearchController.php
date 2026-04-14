<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Yo SearchController controller le search controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class SearchController extends Controller
{
    /**
     * Yo function le invoke ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->get('q', ''));
        $user = $request->user();

        $treks = collect();
        $hotels = collect();
        $users = collect();

        if ($query !== '') {
            $treks = Trek::query()
                ->where('title', 'like', "%{$query}%")
                ->limit(8)
                ->get();

            $hotelsQuery = Hotel::query()->where('name', 'like', "%{$query}%");
            if ($user->role === 'hotel_owner') {
                $hotelsQuery->where('owner_id', $user->id);
            }
            $hotels = $hotelsQuery->limit(8)->get();

            if (in_array($user->role, ['admin', 'staff'], true)) {
                $users = User::query()
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->limit(8)
                    ->get();
            }
        }

        return view('search.search-results', [
            'query' => $query,
            'treks' => $treks,
            'hotels' => $hotels,
            'users' => $users,
        ]);
    }
}



