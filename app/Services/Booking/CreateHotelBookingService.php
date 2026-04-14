<?php

namespace App\Services\Booking;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CreateHotelBookingService
{
    /**
     * Create hotel booking without online payment (manual confirmation flow).
     */
    public function handle(User $user, Hotel $hotel, array $payload): HotelBooking
    {
        return DB::transaction(function () use ($user, $hotel, $payload) {
            $room = $hotel->rooms()->lockForUpdate()->find($payload['hotel_room_id']);
            if (! $room) {
                throw new RuntimeException('Selected room does not belong to this hotel.');
            }

            $checkIn = Carbon::parse($payload['check_in'])->startOfDay();
            $checkOut = Carbon::parse($payload['check_out'])->startOfDay();
            $numNights = $checkIn->diffInDays($checkOut);

            if ($numNights < 1) {
                throw new RuntimeException('Check-out date must be after check-in date.');
            }

            $requestedRooms = (int) $payload['num_rooms'];
            $availableRooms = $this->availableRooms($room->id, $checkIn, $checkOut, (int) $room->total_rooms);

            if ($requestedRooms > $availableRooms) {
                throw new RuntimeException("Only {$availableRooms} room(s) are available for selected dates.");
            }

            $pricePerNight = (float) $room->price_per_night;

            return HotelBooking::query()->create([
                'user_id' => $user->id,
                'hotel_room_id' => $room->id,
                'booking_reference' => $this->generateReference(),
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'num_rooms' => $requestedRooms,
                'num_nights' => $numNights,
                'price_per_night' => $pricePerNight,
                'total_price' => $pricePerNight * $requestedRooms * $numNights,
                'status' => 'pending',
            ]);
        });
    }

    private function availableRooms(int $roomId, Carbon $checkIn, Carbon $checkOut, int $totalRooms): int
    {
        $reserved = (int) HotelBooking::query()
            ->where('hotel_room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
            ->whereDate('check_in', '<', $checkOut->toDateString())
            ->whereDate('check_out', '>', $checkIn->toDateString())
            ->sum('num_rooms');

        return max(0, $totalRooms - $reserved);
    }

    private function generateReference(): string
    {
        do {
            $reference = 'HB-' . strtoupper(Str::random(8));
        } while (HotelBooking::query()->where('booking_reference', $reference)->exists());

        return $reference;
    }
}
