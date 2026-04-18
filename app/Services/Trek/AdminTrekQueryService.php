<?php

namespace App\Services\Trek;

use App\Models\Trek;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


/**
 * Yo AdminTrekQueryService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class AdminTrekQueryService
{
    /**
     * Yo method le paginate related data prepare/fetch garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
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
            ->paginate(9)
            ->withQueryString();

        return [
            'treks' => $treks,
            'search' => $search,
            'difficulty' => $difficulty,
            'status' => $status,
        ];
    }

    /**
     * Yo method le makeDraft related data prepare/fetch garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function makeDraft(): Trek
    {
        $trek = new Trek();
        $trek->setRelation('itineraries', collect());
        $trek->setRelation('images', collect());

        return $trek;
    }

    /**
     * Yo method le loadForShow related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function loadForShow(Trek $trek): Trek
    {
        $trek->load(['itineraries', 'departures', 'images']);

        return $trek;
    }

    /**
     * Yo method le loadForEdit related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function loadForEdit(Trek $trek): Trek
    {
        $trek->load(['itineraries', 'images']);

        return $trek;
    }
}






