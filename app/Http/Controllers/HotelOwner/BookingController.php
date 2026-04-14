<?php

namespace App\Http\Controllers\HotelOwner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Services\Booking\HotelBookingStatusNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class BookingController extends Controller
{
    public function __construct(
        private readonly HotelBookingStatusNotifier $hotelBookingStatusNotifier,
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $status = trim($request->string('status')->toString());
        $hotelId = trim($request->string('hotel_id')->toString());

        $bookings = HotelBooking::query()
            ->with(['user', 'hotelRoom.hotel'])
            ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $request->user()->id))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('booking_reference', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($hotelId !== '', fn ($query) => $query->whereHas('hotelRoom', fn ($roomQuery) => $roomQuery->where('hotel_id', $hotelId)))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $hotels = Hotel::query()
            ->where('owner_id', $request->user()->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('hotels.owner.bookings.booking-list', [
            'bookings' => $bookings,
            'hotels' => $hotels,
            'search' => $search,
            'selectedStatus' => $status,
            'selectedHotel' => $hotelId,
        ]);
    }

    public function show(Request $request, HotelBooking $hotelBooking): View
    {
        $booking = $this->ownerBooking($request, $hotelBooking);
        $booking->load(['user', 'hotelRoom.hotel', 'payments']);

        return view('hotels.owner.bookings.booking-details', [
            'booking' => $booking,
        ]);
    }

    public function updateStatus(Request $request, HotelBooking $hotelBooking): RedirectResponse
    {
        $booking = $this->ownerBooking($request, $hotelBooking);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancellation_requested', 'completed', 'cancelled'])],
        ]);

        $oldStatus = (string) $booking->status;
        $newStatus = (string) $validated['status'];

        try {
            DB::transaction(function () use ($booking, $newStatus): void {
                if ($newStatus === 'confirmed') {
                    $this->guardInventoryForConfirmation($booking);
                }

                $booking->update([
                    'status' => $newStatus,
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $booking->refresh()->loadMissing(['user', 'hotelRoom.hotel']);
        $this->hotelBookingStatusNotifier->notifyStatusChange($booking, $oldStatus, $newStatus);

        return redirect()
            ->route('hotel_owner.bookings.show', $booking)
            ->with('success', 'Booking status updated.');
    }

    private function guardInventoryForConfirmation(HotelBooking $booking): void
    {
        $room = $booking->hotelRoom()->lockForUpdate()->first();
        if (! $room) {
            throw new RuntimeException('Room record was not found for this booking.');
        }

        $alreadyReserved = (int) HotelBooking::query()
            ->where('hotel_room_id', $booking->hotel_room_id)
            ->whereKeyNot($booking->id)
            ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
            ->whereDate('check_in', '<', $booking->check_out)
            ->whereDate('check_out', '>', $booking->check_in)
            ->sum('num_rooms');

        $remaining = (int) $room->total_rooms - $alreadyReserved;

        if ((int) $booking->num_rooms > $remaining) {
            throw new RuntimeException("Cannot confirm. Only {$remaining} room(s) remain for the selected dates.");
        }
    }

    private function ownerBooking(Request $request, HotelBooking $booking): HotelBooking
    {
        abort_unless((int) $booking->hotelRoom?->hotel?->owner_id === (int) $request->user()->id, 403);

        return $booking;
    }
}
