<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\TrekBooking;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrekPaymentService
{
    public function getCheckoutBooking(Payment $payment): TrekBooking
    {
        $booking = TrekBooking::query()
            ->with('departure.trek', 'user')
            ->find((int) $payment->payable_id);

        if (! $booking) {
            throw new NotFoundHttpException();
        }

        return $booking;
    }

    public function getDisplayBooking(Payment $payment): TrekBooking
    {
        $booking = TrekBooking::query()
            ->with('departure')
            ->find((int) $payment->payable_id);

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

        $booking = TrekBooking::query()
            ->with('departure')
            ->find((int) $payment->payable_id);

        if (! $booking) {
            return null;
        }

        if ($booking->status !== 'confirmed') {
            $booking->status = 'confirmed';
            $booking->save();

            if ($booking->departure) {
                $booking->departure->increment('booked_seats', $booking->total_passengers);
            }
        }

        return $booking;
    }
}

