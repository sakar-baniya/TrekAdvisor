<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Collection;

class HotelOwnerQueryService
{
    public function listForOwner(User $user): Collection
    {
        return Hotel::query()
            ->withCount(['rooms', 'gallery'])
            ->where('owner_id', $user->id)
            ->latest()
            ->get();
    }

    public function makeDraft(): Hotel
    {
        return new Hotel();
    }

    public function loadForEdit(Hotel $hotel): Hotel
    {
        $hotel->load('gallery');

        return $hotel;
    }
}
