<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\TrekBooking;
use App\Repositories\Contracts\TrekBookingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrekPaymentService
{
    public function __construct(
        private readonly TrekBookingRepositoryInterface $trekBookings,
    ) {
    }

    public function getCheckoutBooking(Payment $payment): TrekBooking
    {
        $booking = $this->trekBookings->findForCheckoutById((int) $payment->payable_id);

        if (! $booking) {
            throw new NotFoundHttpException();
        }

        return $booking;
    }

    public function getDisplayBooking(Payment $payment): TrekBooking
    {
        $booking = $this->trekBookings->findForDisplayById((int) $payment->payable_id);

        if (! $booking) {
            throw new NotFoundHttpException();
        }

        return $booking;
    }

    public function confirmBooking(Payment $payment): ?TrekBooking
    {
        if ($payment->payable_type !== 'trek') {
            return null;
        }

        $booking = $this->trekBookings->findForDisplayById((int) $payment->payable_id);

        if (! $booking) {
            return null;
        }

        return $this->trekBookings->markConfirmedAndIncrementSeats($booking);
    }
}

