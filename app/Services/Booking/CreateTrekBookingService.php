<?php

namespace App\Services\Booking;

use App\DTOs\Booking\BookingCheckoutResult;
use App\DTOs\Booking\CreateTrekBookingData;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\TrekBookingRepositoryInterface;
use App\Services\Payment\StripeCheckoutWorkflowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CreateTrekBookingService
{
    public function __construct(
        private readonly TrekBookingRepositoryInterface $trekBookings,
        private readonly PaymentRepositoryInterface $payments,
        private readonly StripeCheckoutWorkflowService $stripeCheckoutWorkflowService,
        private readonly BookingSessionService $bookingSessionService,
    ) {
    }

    public function handle(CreateTrekBookingData $data): BookingCheckoutResult
    {
        [$booking, $payment] = DB::transaction(function () use ($data) {
            $booking = $this->trekBookings->create([
                'user_id' => $data->userId,
                'departure_id' => $data->departureId,
                'booking_reference' => 'TB-' . strtoupper(Str::random(8)),
                'total_passengers' => $data->totalPassengers,
                'price_per_person' => $data->pricePerPerson,
                'subtotal' => $data->totalPrice(),
                'total_price' => $data->totalPrice(),
                'status' => 'pending',
            ]);

            $this->trekBookings->createPassengers($booking, $data->passengers);

            $payment = $this->payments->create([
                'user_id' => $data->userId,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'amount' => $data->totalPrice(),
                'currency' => 'USD',
                'payable_type' => 'trek',
                'payable_id' => $booking->id,
                'gateway' => 'stripe',
                'status' => 'pending',
            ]);

            return [$booking, $payment];
        });

        try {
            $checkoutUrl = $this->stripeCheckoutWorkflowService->createRetrySession($payment);

            $this->bookingSessionService->clear();

            return new BookingCheckoutResult(
                payment: $payment,
                checkoutUrl: $checkoutUrl,
            );
        } catch (Throwable) {
            $this->bookingSessionService->clear();

            return new BookingCheckoutResult(
                payment: $payment,
                checkoutUrl: null,
                errorMessage: 'We saved your booking, but could not start Stripe checkout. Please try again.',
            );
        }
    }
}

