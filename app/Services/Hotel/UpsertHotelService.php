<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Yo UpsertHotelService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class UpsertHotelService
{
    /**
     * Yo method le create related business flow execute garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function create(Request $request, User $owner): Hotel
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated, $owner) {
            $payload = [
                'owner_id' => $owner->id,
                'name' => $validated['name'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'status' => 'pending',
            ];

            $this->hotelGalleryService->syncHeroImage($request, $payload);

            $hotel = Hotel::query()->create($payload);
            $this->hotelGalleryService->syncGallery($request, $hotel);

            return $hotel;
        });
    }

    /**
     * Yo method le update related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function update(Request $request, Hotel $hotel): Hotel
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated, $hotel) {
            $payload = [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'status' => $hotel->status === 'active' ? 'pending' : $hotel->status,
            ];

            $this->hotelGalleryService->syncHeroImage($request, $payload, $hotel);

            $hotel->update($payload);
            $this->hotelGalleryService->syncGallery($request, $hotel);

            return $hotel;
        });
    }
}






