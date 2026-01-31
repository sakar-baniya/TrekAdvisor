<?php

namespace App\Http\Controllers;

use App\Models\Departure;
use App\Models\TrekBooking;
use App\Models\Passenger;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Step 1: Initialize booking for a specific departure.
     */
    public function create(Departure $departure)
    {
        $departure->load('trek');
        return view('bookings.create', compact('departure'));
    }

    /**
     * Step 2: Store basic info and move to passenger details.
     */
    public function store(Request $request)
    {
        $request->validate([
            'departure_id' => 'required|exists:departures,id',
            'total_passengers' => 'required|integer|min:1|max:10',
        ]);

        $departure = Departure::findOrFail($request->departure_id);
        
        // Check availability
        if ($departure->booked_seats + $request->total_passengers > $departure->capacity) {
            return back()->with('error', 'Not enough slots available for this departure.');
        }

        // Store basic booking info in session to carry over to passenger step
        session(['booking_data' => [
            'departure_id' => $departure->id,
            'total_passengers' => $request->total_passengers,
            'price_per_person' => $departure->price,
        ]]);

        return redirect()->route('bookings.passengers');
    }

    /**
     * Step 3: Show passenger details form.
     */
    public function passengers()
    {
        $bookingData = session('booking_data');
        if (!$bookingData) return redirect()->route('treks.index');

        return view('bookings.passengers', compact('bookingData'));
    }

    /**
     * Step 4: Finalize booking and payment.
     */
    public function confirm(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) return redirect()->route('treks.index');

        $request->validate([
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.passport_no' => 'required|string|max:50',
            'passengers.*.age' => 'required|integer|min:1|max:120',
        ]);

        $totalPrice = $bookingData['price_per_person'] * $bookingData['total_passengers'];

        // Create the booking record
        $booking = TrekBooking::create([
            'user_id' => Auth::id(),
            'departure_id' => $bookingData['departure_id'],
            'booking_reference' => 'TB-' . strtoupper(Str::random(8)),
            'total_passengers' => $bookingData['total_passengers'],
            'price_per_person' => $bookingData['price_per_person'],
            'subtotal' => $totalPrice,
            'total_price' => $totalPrice,
            'status' => 'Confirmed', // We'll auto-confirm for now
        ]);

        // Create passenger records
        foreach ($request->passengers as $pData) {
            Passenger::create([
                'trek_booking_id' => $booking->id,
                'name' => $pData['name'],
                'passport_no' => $pData['passport_no'],
                'age' => $pData['age'],
            ]);
        }

        // Create payment record
        Payment::create([
            'user_id' => Auth::id(),
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'amount' => $totalPrice,
            'payment_for' => 'trek',
            'reference_id' => $booking->id,
            'status' => 'Success',
        ]);

        // Update departure booked seats
        $departure = Departure::find($bookingData['departure_id']);
        $departure->increment('booked_seats', $bookingData['total_passengers']);

        // Clear session
        session()->forget('booking_data');

        return view('bookings.success', compact('booking'));
    }
}
