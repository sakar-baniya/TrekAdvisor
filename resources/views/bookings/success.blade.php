<x-app-layout>
    <div class="container booking-wrap">
        <div class="booking-progress booking-progress--step-3">
            <span>1. Info</span>
            <span>2. Passengers</span>
            <span class="is-active">3. Confirm</span>
        </div>

        <div class="booking-success-card">
            <div class="booking-success-card__icon"><i class="fas fa-check-circle"></i></div>
            <h1>Booking Confirmed!</h1>
            <p>Your trek booking is now secured. We’ll use this record as the base for your future payment and travel details.</p>

            <div class="booking-price-box booking-price-box--success">
                <div><span>Reference Number</span><strong>{{ $booking->booking_reference }}</strong></div>
                <div><span>Departure Date</span><strong>{{ $booking->departure->start_date->format('M d, Y') }}</strong></div>
                <div class="total"><span>Total</span><strong>${{ number_format($booking->total_price, 0) }}</strong></div>
            </div>

            <div class="booking-success-actions">
                <a href="{{ route('customer.dashboard') }}" class="market-search-btn market-search-btn--full">View My Bookings</a>
                <a href="{{ route('treks.index') }}" class="market-link market-link--center">Back to Treks</a>
            </div>
        </div>
    </div>
</x-app-layout>
