<?php

namespace App\Services\Payment;

use App\Mail\TrekBookingConfirmedMail;
use App\Mail\TrekBookingReceiptMail;
use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Yo TrekPaymentService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class TrekPaymentService
{
    /**
     * Yo method le checkout bela use hune trek booking load garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
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

    /**
     * Yo method le payment result page ko lagi booking detail fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
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

    /**
     * Yo method le successful payment pachi booking confirm garera seat count update garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
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

            $booking->loadMissing('user', 'departure.trek');

            if (filled($booking->user?->email)) {
                Mail::to($booking->user->email)->send(new TrekBookingConfirmedMail($booking));
                Mail::to($booking->user->email)->send(new TrekBookingReceiptMail($booking, $payment));
            }
        }

        return $booking;
    }
}






