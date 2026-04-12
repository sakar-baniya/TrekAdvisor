<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.index', [
            'hotel' => $hotel,
            'rooms' => $hotel->rooms()->latest()->paginate(10),
        ]);
    }

    public function create(Hotel $hotel): View
    {
        return view('hotels.owner.rooms.create', [
            'hotel' => $hotel,
            'room' => new HotelRoom(),
        ]);
    }

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

    public function edit(Hotel $hotel, HotelRoom $room): View
    {
        abort_unless((int) $room->hotel_id === (int) $hotel->id, 404);

        return view('hotels.owner.rooms.edit', [
            'hotel' => $hotel,
            'room' => $room,
        ]);
    }

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
