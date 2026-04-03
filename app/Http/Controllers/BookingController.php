<?php

namespace App\Http\Controllers;

use App\DTOs\CreateTrekBookingData;
use App\Http\Requests\ConfirmBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Departure;
use App\Services\Booking\BookingSessionService;
use App\Services\Booking\CreateTrekBookingService;
use App\Services\Booking\StartTrekBookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class BookingController extends Controller
{
    public function __construct(
        protected StartTrekBookingService $startTrekBookingService,
        protected CreateTrekBookingService $createTrekBookingService,
        protected BookingSessionService $bookingSessionService,
    ) {
    }

    /**
     * Step 1: Initialize booking for a specific departure.
     */
    public function create(Departure $departure): View
    {
        return view('bookings.create', [
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

        return view('bookings.passengers', compact('bookingData'));
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
            CreateTrekBookingData::fromRequest($request, $bookingData)
        );

        if (! $result->checkoutStarted()) {
            return redirect()
                ->route('stripe.cancel', $result->payment)
                ->with('error', $result->errorMessage);
        }

        return redirect()->away($result->checkoutUrl);
    }
}
