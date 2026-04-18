<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Services\Booking\HotelBookingStatusNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Staff Hotel Booking Controller: Staff le hotel bookings ko management garne thau.
 *
 * Function:
 * Sabai hotel bookings herne, search garne, ra status (Stay active/cancelled) update garne.
 */
class HotelBookingController extends Controller
{
    public function __construct(
        private readonly HotelBookingStatusNotifier $hotelBookingStatusNotifier,
    ) {
    }

    /**
     * Hotel Booking List (Index): Paginated list of all hotel bookings for staff.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $hotelId = $request->string('hotel_id')->toString();
        $status = $request->string('status')->toString();

        $bookings = HotelBooking::query()
            ->with(['user', 'hotelRoom.hotel'])
            ->when($search !== '', function ($query) use ($search) {
                // Match booking reference or customer identity.
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('booking_reference', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($customerQuery) => $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($hotelId !== '', fn ($query) => $query->whereHas('hotelRoom', fn ($roomQuery) => $roomQuery->where('hotel_id', $hotelId)))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('staff.hotel-bookings.hotel-booking-list', [
            'bookings' => $bookings,
            'hotels' => Hotel::query()->orderBy('name')->get(['id', 'name']),
            'search' => $search,
            'selectedHotel' => $hotelId,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Booking Details (Show): Detail view of a single hotel reservation.
     */
    public function show(HotelBooking $hotelBooking): View
    {
        $hotelBooking->load(['user', 'hotelRoom.hotel', 'payments']);

        return view('staff.hotel-bookings.hotel-booking-details', [
            'booking' => $hotelBooking,
        ]);
    }

    /**
     * Update Booking Status: Staff le manual status update garne functionality.
     */
    public function updateStatus(Request $request, HotelBooking $hotelBooking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancellation_requested', 'completed', 'cancelled'])],
        ]);

        $oldStatus = (string) $hotelBooking->status;
        $newStatus = (string) $validated['status'];

        $hotelBooking->update([
            'status' => $newStatus,
        ]);

        $hotelBooking->refresh()->loadMissing(['user', 'hotelRoom.hotel']);
        $this->hotelBookingStatusNotifier->notifyStatusChange($hotelBooking, $oldStatus, $newStatus);

        return redirect()
            ->route('staff.hotel-bookings.show', $hotelBooking)
            ->with('success', 'Hotel booking updated.');
    }
}

