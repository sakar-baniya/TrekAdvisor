<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Hotel Owner Room Controller: Hotel owner le aafno hotel ko kothaharu (rooms) setup garne thau.
 *
 * Function:
 * Kun thau ma kati ota kotha chhan ra price kati ho bhanne manage garchha.
 */
class RoomController extends Controller
{
    /**
     * Room List (Index): Hotel ko sabai add gareka room types dekhaune.
     */
    public function index(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.room-list', [
            'hotel' => $hotel,
            'rooms' => $hotel->rooms()->latest()->paginate(10),
        ]);
    }

    /**
     * Create Room Form: Naya category ko room thapne form dekhaune (e.g. Deluxe, Standard).
     */
    public function create(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.create-room', [
            'hotel' => $hotel,
            'room' => new HotelRoom(),
        ]);
    }

    /**
     * Save Room (Store): Room ko capacity ra price check garera save garne.
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
     * Edit Room Form: Purano room ko details (e.g., price badauna) edit garne.
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
     * Update Room (Update): Form ma hareko new detail validate gari update garne.
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



