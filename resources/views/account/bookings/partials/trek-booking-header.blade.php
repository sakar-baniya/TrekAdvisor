<div class="account-booking-header">
    <div>
        <p class="market-kicker">My Bookings</p>
        <h1>Trek Booking Details</h1>
        <div class="account-booking-header__meta">
            <span>Reference {{ $booking->booking_reference }}</span>
            <x-account.status-badge :status="$booking->status" />
        </div>
    </div>
    <a href="{{ route('account.bookings.index') }}" class="account-outline-button">Back to My Bookings</a>
</div>
