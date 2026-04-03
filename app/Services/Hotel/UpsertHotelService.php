<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpsertHotelService
{
    public function __construct(
        private readonly HotelGalleryService $hotelGalleryService,
    ) {
    }

    public function create(Request $request, User $owner): Hotel
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated, $owner) {
            $payload = [
                'owner_id' => $owner->id,
                'name' => $validated['name'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'status' => 'Pending',
            ];

            $this->hotelGalleryService->syncHeroImage($request, $payload);

            $hotel = Hotel::query()->create($payload);
            $this->hotelGalleryService->syncGallery($request, $hotel);

            return $hotel;
        });
    }

    public function update(Request $request, Hotel $hotel): Hotel
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated, $hotel) {
            $payload = [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'status' => $hotel->status === 'Active' ? 'Pending' : $hotel->status,
            ];

            $this->hotelGalleryService->syncHeroImage($request, $payload, $hotel);

            $hotel->update($payload);
            $this->hotelGalleryService->syncGallery($request, $hotel);

            return $hotel;
        });
    }
}
