<?php

namespace App\Mail;

use App\Models\TrekBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrekBookingConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TrekBooking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmed - ' . $this->booking->booking_reference,
        );
    }

    public function content(): Content
    {
        $supportNumber = preg_replace('/\D+/', '', (string) config('services.whatsapp.support_number', ''));

        return new Content(
            view: 'emails.trek-booking-confirmed',
            with: [
                'booking' => $this->booking,
                'supportNumber' => $supportNumber,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
