<?php

namespace App\Services\Booking;

use App\Models\Passenger;
use App\Models\Payment;
use App\Models\TrekBooking;
use App\Services\Payment\EsewaCheckoutWorkflowService;
use App\Services\Payment\StripeCheckoutWorkflowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;


/**
 * Yo CreateTrekBookingService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class CreateTrekBookingService
{
    public function __construct(
        protected StripeCheckoutWorkflowService $stripeCheckoutWorkflowService,
        protected EsewaCheckoutWorkflowService $esewaCheckoutWorkflowService,
        protected BookingSessionService $bookingSessionService
    ) {}

    /**
     * Yo method le handle related business flow execute garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function handle(int $userId, array $bookingData, array $passengers): array
    {
        $departureId = (int) $bookingData['departure_id'];
        $totalPassengers = (int) $bookingData['total_passengers'];
        $pricePerPerson = (float) $bookingData['price_per_person'];
        $subtotal = $pricePerPerson * $totalPassengers;

        $discountPercent = 0;
        if ($totalPassengers >= 10) {
            $discountPercent = 15;
        } elseif ($totalPassengers >= 6) {
            $discountPercent = 10;
        } elseif ($totalPassengers >= 3) {
            $discountPercent = 5;
        }

        $discountAmount = ($subtotal * $discountPercent) / 100;
        $totalPrice = $subtotal - $discountAmount;

        $paymentMethod = in_array(($bookingData['payment_method'] ?? 'stripe'), ['stripe', 'esewa'], true)
            ? $bookingData['payment_method']
            : 'stripe';

        [$booking, $payment] = DB::transaction(function () use ($userId, $departureId, $totalPassengers, $pricePerPerson, $subtotal, $discountPercent, $discountAmount, $totalPrice, $passengers, $paymentMethod) {
            $booking = TrekBooking::query()->create([
                'user_id' => $userId,
                'departure_id' => $departureId,
                'booking_reference' => 'TB-' . strtoupper(Str::random(8)),
                'total_passengers' => $totalPassengers,
                'price_per_person' => $pricePerPerson,
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            foreach ($passengers as $passenger) {
                Passenger::query()->create([
                    'trek_booking_id' => $booking->id,
                    'full_name' => $passenger['full_name'],
                    'passport_number' => $passenger['passport_number'],
                    'age' => $passenger['age'],
                ]);
            }

            $payment = Payment::query()->create([
                'user_id' => $userId,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'amount' => $totalPrice,
                'currency' => 'NPR',
                'payable_type' => 'trek',
                'payable_id' => $booking->id,
                'gateway' => $paymentMethod,
                'status' => 'pending',
            ]);

            return [$booking, $payment];
        });

        try {
            $checkoutUrl = $paymentMethod === 'esewa'
                ? $this->esewaCheckoutWorkflowService->createRetryUrl($payment)
                : $this->stripeCheckoutWorkflowService->createRetrySession($payment);

            $this->bookingSessionService->clear();

            return [
                'payment' => $payment,
                'checkout_url' => $checkoutUrl,
                'error_message' => null,
            ];
        } catch (Throwable) {
            $this->bookingSessionService->clear();

            return [
                'payment' => $payment,
                'checkout_url' => null,
                'error_message' => 'We saved your booking, but could not start checkout. Please try again.',
            ];
        }
    }
}







