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
    private HotelGalleryService $hotelGalleryService;

    public function __construct(HotelGalleryService $hotelGalleryService)
    {
        $this->hotelGalleryService = $hotelGalleryService;
    }

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
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'description' => $validated['description'],
                'booking_policy' => $validated['booking_policy'] ?? null,
                'status' => 'pending',
            ];

            $hotel = Hotel::query()->create($payload);
            $this->hotelGalleryService->syncHeroImage($request, $payload, $hotel);
            $this->hotelGalleryService->syncGallery($request, $hotel);
            $this->syncRooms($hotel, $request->input('rooms', []));

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
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'description' => $validated['description'],
                'booking_policy' => $validated['booking_policy'] ?? null,
                // STATUS CHANGE: Once active, stay active after edits.
                'status' => $hotel->status, 
            ];

            $this->hotelGalleryService->syncHeroImage($request, $payload, $hotel);

            $hotel->update($payload);
            $this->hotelGalleryService->syncGallery($request, $hotel);
            $this->syncRooms($hotel, $request->input('rooms', []));

            return $hotel;
        });
    }

    /**
     * Yo method le hotel ko rooms data sync garcha (Create/Update/Delete).
     */
    private function syncRooms(Hotel $hotel, array $roomsData): void
    {
        // Get IDs of rooms that should remain
        $submittedIds = collect($roomsData)->pluck('id')->filter()->toArray();
        
        // Remove rooms that are not in the submitted list
        $hotel->rooms()->whereNotIn('id', $submittedIds)->delete();

        // Update or create rooms
        foreach ($roomsData as $room) {
            $hotel->rooms()->updateOrCreate(
                ['id' => $room['id'] ?? null],
                [
                    'room_type' => $room['room_type'],
                    'price_per_night' => $room['price_per_night'],
                    'total_rooms' => $room['total_rooms'],
                ]
            );
        }
    }
}
