<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Yo RoomController controller le room controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class RoomController extends Controller
{
    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.room-list', [
            'hotel' => $hotel,
            'rooms' => $hotel->rooms()->latest()->paginate(10),
        ]);
    }

    /**
     * Yo function le create ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function create(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.create-room', [
            'hotel' => $hotel,
            'room' => new HotelRoom(),
        ]);
    }

    /**
     * Yo function le store ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'room_type' => ['required', 'string', 'max:120'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'total_rooms' => ['required', 'integer', 'min:1'],
        ]);

        $hotel->rooms()->create($validated);

        return redirect()->route('hotel_owner.hotels.rooms.index', $hotel)
            ->with('success', 'Room added successfully.');
    }

    /**
     * Yo function le edit ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function edit(Hotel $hotel, HotelRoom $room): View
    {
        abort_unless((int) $room->hotel_id === (int) $hotel->id, 404);

        return view('hotels.owner.rooms.edit-room', [
            'hotel' => $hotel,
            'room' => $room,
        ]);
    }

    /**
     * Yo function le update ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function update(Request $request, Hotel $hotel, HotelRoom $room): RedirectResponse
    {
        abort_unless((int) $room->hotel_id === (int) $hotel->id, 404);

        $validated = $request->validate([
            'room_type' => ['required', 'string', 'max:120'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'total_rooms' => ['required', 'integer', 'min:1'],
        ]);

        $room->update($validated);

        return redirect()->route('hotel_owner.hotels.rooms.index', $hotel)
            ->with('success', 'Room updated successfully.');
    }
}



