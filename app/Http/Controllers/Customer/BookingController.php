<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\ConfirmBookingRequest;
use App\Http\Requests\Booking\StoreHotelBookingRequest;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\Departure;
use App\Models\Hotel;
use App\Services\Booking\CreateHotelBookingService;
use App\Services\Booking\BookingSessionService;
use App\Services\Booking\CreateTrekBookingService;
use App\Services\Booking\StartTrekBookingService;
use App\Models\HotelBooking;
use App\Models\TrekBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

/**
 * Customer Checkout Booking Controller: Naya booking banaune checkout process herya thau.
 *
 * Function:
 * Step 1 bata Step 4 samma ko payment checkout ra backend API calling (Stripe/SSession) handle garcha.
 */
class BookingController extends Controller
{
    public function __construct(
        protected StartTrekBookingService $startTrekBookingService,
        protected CreateTrekBookingService $createTrekBookingService,
        protected CreateHotelBookingService $createHotelBookingService,
        protected BookingSessionService $bookingSessionService,
    ) {
    }

    /**
     * Step 1: Initialize booking for a specific departure.
     */
    public function create(Departure $departure): View
    {
        return view('bookings.booking-start', [
            'departure' => $this->startTrekBookingService->loadDeparture($departure),
        ]);
    }

    /**
     * Step 2: Store basic info and move to passenger details.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        try {
            $this->startTrekBookingService->handle(
                (int) $request->validated('departure_id'),
                (int) $request->validated('total_passengers')
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('bookings.passengers');
    }

    /**
     * Step 3: Show passenger details form.
     */
    public function passengers(): View|RedirectResponse
    {
        $bookingData = $this->bookingSessionService->get();

        if (! $bookingData) {
            return redirect()->route('treks.index');
        }

        return view('bookings.passenger-details', compact('bookingData'));
    }

    /**
     * Step 4: Finalize booking and payment.
     */
    public function confirm(ConfirmBookingRequest $request): RedirectResponse
    {
        $bookingData = $this->bookingSessionService->get();

        if (! $bookingData) {
            return redirect()->route('treks.index');
        }

        $result = $this->createTrekBookingService->handle(
            (int) $request->user()->id,
            array_merge($bookingData, [
                'payment_method' => $request->validated('payment_method'),
            ]),
            $request->validated('passengers', [])
        );

        if (! filled($result['checkout_url'])) {
            return redirect()
                ->route('stripe.cancel', $result['payment'])
                ->with('error', $result['error_message']);
        }

        return redirect()->away($result['checkout_url']);
    }

    /**
     * Show Trek Booking Payment Status: Checkout pass bhayepachi booking herne.
     */
    public function showTrekBooking(TrekBooking $trekBooking): View
    {
        abort_unless($trekBooking->user_id === auth()->id(), 403);

        return view('customer.trek-booking-show', [
            'booking' => $trekBooking->load(['departure.trek']),
        ]);
    }

    /**
     * Show Hotel Booking Payment Status: Checkout pass bhayepachi hotel booking herne.
     */
    public function showHotelBooking(HotelBooking $hotelBooking): View
    {
        abort_unless($hotelBooking->user_id === auth()->id(), 403);

        return view('customer.hotel-booking-show', [
            'booking' => $hotelBooking->load(['hotelRoom.hotel']),
        ]);
    }

    /**
     * Create a hotel booking without online payment checkout.
     */
    public function storeHotelBooking(StoreHotelBookingRequest $request, Hotel $hotel): RedirectResponse
    {
        $this->authorizeCreate(HotelBooking::class);

        try {
            $booking = $this->createHotelBookingService->handle(
                $request->user(),
                $hotel,
                $request->validated()
            );
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('account.bookings.hotels.show', $booking)
            ->with('success', 'Hotel booking request submitted. We will confirm it shortly.');
    }
}



