<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\TrekBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Yo AccountBookingsController controller le account bookings controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
 */
class AccountBookingsController extends Controller
{
    /**
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $upcomingStatuses = ['pending', 'confirmed', 'cancellation_requested'];
        $pastStatuses = ['completed', 'cancelled'];

        $trekUpcoming = TrekBooking::query()
            ->with(['departure.trek', 'payments'])
            ->where('user_id', $user->id)
            ->whereIn('status', $upcomingStatuses)
            ->latest()
            ->get();

        $trekPast = TrekBooking::query()
            ->with(['departure.trek', 'payments'])
            ->where('user_id', $user->id)
            ->whereIn('status', $pastStatuses)
            ->latest()
            ->get();

        $hotelUpcoming = HotelBooking::query()
            ->with(['hotelRoom.hotel', 'payments'])
            ->where('user_id', $user->id)
            ->whereIn('status', $upcomingStatuses)
            ->latest()
            ->get();

        $hotelPast = HotelBooking::query()
            ->with(['hotelRoom.hotel', 'payments'])
            ->where('user_id', $user->id)
            ->whereIn('status', $pastStatuses)
            ->latest()
            ->get();

        $trekReviewMap = Review::query()
            ->where('user_id', $user->id)
            ->where('reviewable_type', \App\Models\Trek::class)
            ->get()
            ->keyBy('reviewable_id');

        $hotelReviewMap = Review::query()
            ->where('user_id', $user->id)
            ->where('reviewable_type', \App\Models\Hotel::class)
            ->get()
            ->keyBy('reviewable_id');

        return view('account.bookings.bookings-list', compact(
            'trekUpcoming',
            'trekPast',
            'hotelUpcoming',
            'hotelPast',
            'trekReviewMap',
            'hotelReviewMap'
        ));
    }

    /**
     * Yo function le show trek ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function showTrek(TrekBooking $trekBooking): View
    {
        $this->authorize('view', $trekBooking);
        $trekBooking->load(['departure.trek', 'payments', 'passengers']);

        $payment = $this->latestPayment($trekBooking->payments);
        $review = Review::query()
            ->where('user_id', auth()->id())
            ->where('reviewable_type', TrekBooking::class)
            ->where('reviewable_id', $trekBooking->id)
            ->first();

        return view('account.bookings.trek-booking-details', [
            'booking' => $trekBooking,
            'payment' => $payment,
            'review' => $review,
        ]);
    }

    /**
     * Yo function le update passengers ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function updatePassengers(Request $request, TrekBooking $trekBooking): RedirectResponse
    {
        $this->authorize('view', $trekBooking);

        if (in_array($trekBooking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Passenger details cannot be edited for this booking.');
        }

        $validated = $request->validate([
            'passengers' => ['required', 'array'],
            'passengers.*.id' => ['required', 'integer'],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'passengers.*.passport_number' => ['nullable', 'string', 'max:50'],
        ]);

        $passengers = $trekBooking->passengers->keyBy('id');
        foreach ($validated['passengers'] as $payload) {
            $passenger = $passengers->get($payload['id']);
            if (! $passenger) {
                continue;
            }
            $passenger->update([
                'full_name' => $payload['full_name'],
                'age' => $payload['age'],
                'passport_number' => $payload['passport_number'],
            ]);
        }

        return back()->with('success', 'Passenger details updated.');
    }

    /**
     * Yo function le show hotel ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function showHotel(HotelBooking $hotelBooking): View
    {
        $this->authorize('view', $hotelBooking);
        $hotelBooking->load(['hotelRoom.hotel', 'payments']);

        $payment = $this->latestPayment($hotelBooking->payments);
        $review = Review::query()
            ->where('user_id', auth()->id())
            ->where('reviewable_type', HotelBooking::class)
            ->where('reviewable_id', $hotelBooking->id)
            ->first();

        return view('account.bookings.hotel-booking-details', [
            'booking' => $hotelBooking,
            'payment' => $payment,
            'review' => $review,
        ]);
    }

    /**
     * Yo function le cancel trek ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function cancelTrek(TrekBooking $trekBooking): RedirectResponse
    {
        $this->authorize('view', $trekBooking);

        if (in_array($trekBooking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'This booking can no longer be cancelled.');
        }

        if ($trekBooking->status === 'cancellation_requested') {
            return back()->with('error', 'Cancellation has already been requested for this booking.');
        }

        $trekBooking->update(['status' => 'cancellation_requested']);

        return back()->with('success', 'Cancellation request submitted.');
    }

    /**
     * Yo function le withdraw trek cancellation ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function withdrawTrekCancellation(TrekBooking $trekBooking): RedirectResponse
    {
        $this->authorize('view', $trekBooking);

        if ($trekBooking->status !== 'cancellation_requested') {
            return back()->with('error', 'This booking does not have a cancellation request.');
        }

        $trekBooking->update(['status' => 'confirmed']);

        return back()->with('success', 'Cancellation request withdrawn.');
    }

    /**
     * Yo function le cancel hotel ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function cancelHotel(HotelBooking $hotelBooking): RedirectResponse
    {
        $this->authorize('view', $hotelBooking);

        if (in_array($hotelBooking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'This booking can no longer be cancelled.');
        }

        if ($hotelBooking->status === 'cancellation_requested') {
            return back()->with('error', 'Cancellation has already been requested for this booking.');
        }

        $hotelBooking->update(['status' => 'cancellation_requested']);

        return back()->with('success', 'Cancellation request submitted.');
    }

    /**
     * Yo function le withdraw hotel cancellation ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function withdrawHotelCancellation(HotelBooking $hotelBooking): RedirectResponse
    {
        $this->authorize('view', $hotelBooking);

        if ($hotelBooking->status !== 'cancellation_requested') {
            return back()->with('error', 'This booking does not have a cancellation request.');
        }

        $hotelBooking->update(['status' => 'confirmed']);

        return back()->with('success', 'Cancellation request withdrawn.');
    }

    /**
     * Yo function le trek receipt ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function trekReceipt(TrekBooking $trekBooking): View
    {
        $this->authorize('view', $trekBooking);
        $trekBooking->load(['departure.trek', 'payments', 'user', 'passengers']);

        return view('account.receipts.trek', [
            'booking' => $trekBooking,
            'payment' => $this->latestPayment($trekBooking->payments),
        ]);
    }

    /**
     * Yo function le hotel receipt ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function hotelReceipt(HotelBooking $hotelBooking): View
    {
        $this->authorize('view', $hotelBooking);
        $hotelBooking->load(['hotelRoom.hotel', 'payments', 'user']);

        return view('account.receipts.hotel', [
            'booking' => $hotelBooking,
            'payment' => $this->latestPayment($hotelBooking->payments),
        ]);
    }

    private function latestPayment($payments): ?Payment
    {
        return $payments->sortByDesc(fn ($payment) => $payment->paid_at ?? $payment->created_at)->first();
    }
}



