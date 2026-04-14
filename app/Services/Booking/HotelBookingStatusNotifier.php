<?php

namespace App\Services\Booking;

use App\Mail\HotelBookingStatusUpdatedMail;
use App\Models\HotelBooking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HotelBookingStatusNotifier
{
    public function notifyStatusChange(HotelBooking $booking, string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        $booking->loadMissing(['user', 'hotelRoom.hotel']);

        $this->sendEmail($booking, $oldStatus, $newStatus);
        $this->sendSms($booking, $newStatus);
    }

    private function sendEmail(HotelBooking $booking, string $oldStatus, string $newStatus): void
    {
        if (! filled($booking->user?->email)) {
            return;
        }

        Mail::to($booking->user->email)->send(
            new HotelBookingStatusUpdatedMail($booking, $oldStatus, $newStatus)
        );
    }

    private function sendSms(HotelBooking $booking, string $newStatus): void
    {
        $phone = $this->normalizePhone((string) ($booking->user?->phone ?? ''));
        if ($phone === '') {
            return;
        }

        $endpoint = (string) config('services.sms.endpoint', '');
        if ($endpoint === '') {
            Log::info('Hotel booking SMS skipped: endpoint not configured', [
                'booking_id' => $booking->id,
                'to' => $phone,
                'status' => $newStatus,
            ]);
            return;
        }

        $message = sprintf(
            'TrekAdvisor: Your hotel booking %s is now %s.',
            $booking->booking_reference,
            strtoupper(str_replace('_', ' ', $newStatus))
        );

        try {
            Http::asJson()->post($endpoint, [
                'to' => $phone,
                'message' => $message,
                'from' => (string) config('services.sms.from', 'TrekAdvisor'),
                'token' => (string) config('services.sms.token', ''),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Hotel booking SMS failed', [
                'booking_id' => $booking->id,
                'to' => $phone,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function normalizePhone(string $phone): string
    {
        $normalized = preg_replace('/\D+/', '', $phone) ?? '';
        return trim($normalized);
    }
}
