<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
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

        return view('search.index', [
            'query' => $query,
            'treks' => $treks,
            'hotels' => $hotels,
            'users' => $users,
        ]);
    }
}
