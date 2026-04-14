<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Collection;


/**
 * Yo HotelOwnerQueryService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class HotelOwnerQueryService
{
    /**
     * Yo method le listForOwner related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function listForOwner(User $user): Collection
    {
        return Hotel::query()
            ->withCount(['rooms', 'gallery'])
            ->where('owner_id', $user->id)
            ->latest()
            ->get();
    }

    /**
     * Yo method le makeDraft related data prepare/fetch garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function makeDraft(): Hotel
    {
        return new Hotel();
    }

    /**
     * Yo method le loadForEdit related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function loadForEdit(Hotel $hotel): Hotel
    {
        $hotel->load('gallery');

        return $hotel;
    }
}






