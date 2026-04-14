<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrekBookingReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TrekBooking $booking,
        public ?Payment $payment,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt - ' . $this->booking->booking_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trek-booking-receipt',
            with: [
                'booking' => $this->booking,
                'payment' => $this->payment,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
