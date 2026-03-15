<x-app-layout>
    <div class="booking-flow-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Booking Confirmed!</h1>
            <p class="success-msg">Congratulations! Your trek to the mountains is officially booked. Get ready for the adventure of a lifetime.</p>
            
            <div class="booking-info">
                <div class="info-row">
                    <span>Reference Number:</span>
                    <strong>{{ $booking->booking_reference }}</strong>
                </div>
                <div class="info-row">
                    <span>Arrival Date:</span>
                    <strong>{{ $booking->departure->start_date->format('M d, Y') }}</strong>
                </div>
                <div class="info-row">
                    <span>Total Paid:</span>
                    <strong>${{ number_format($booking->total_price) }}</strong>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('customer.dashboard') }}" class="btn-dashboard">View My Bookings</a>
                <a href="{{ route('treks.index') }}" class="btn-home">Back to Gallery</a>
            </div>
        </div>
    </div>

</x-app-layout>
