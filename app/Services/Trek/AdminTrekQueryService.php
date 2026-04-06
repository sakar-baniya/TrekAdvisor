<?php

namespace App\Services\Trek;

use App\Models\Trek;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminTrekQueryService
{
    public function paginate(Request $request): array
    {
        $search = $request->string('search')->toString();
        $difficulty = $request->string('difficulty')->toString();
        $status = $request->string('status')->toString();

        $treks = Trek::query()
            ->withCount('departures')
            ->withSum('departures as total_booked_seats', 'booked_seats')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($difficulty !== '', fn ($query) => $query->where('difficulty', $difficulty))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return [
            'treks' => $treks,
            'search' => $search,
            'difficulty' => $difficulty,
            'status' => $status,
        ];
    }

    public function makeDraft(): Trek
    {
        $trek = new Trek();
        $trek->setRelation('itineraries', collect());
        $trek->setRelation('images', collect());

        return $trek;
    }

    public function loadForShow(Trek $trek): Trek
    {
        $trek->load(['itineraries', 'departures', 'images']);

        return $trek;
    }

    public function loadForEdit(Trek $trek): Trek
    {
        $trek->load(['itineraries', 'images']);

        return $trek;
    }
}
