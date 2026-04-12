<section class="account-panel account-panel--summary">
    <div class="account-panel__head">
        <div>
            <h2>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}</h2>
            <p>{{ $booking->hotelRoom?->room_type ? $booking->hotelRoom->room_type : 'Room details' }}</p>
        </div>
    </div>

    <div class="account-summary-grid">
        <div class="account-summary-card">
            <div class="account-summary-row">
                <span class="account-summary-label">Check-in</span>
                <span>{{ optional($booking->check_in)->format('F d, Y') }}</span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Check-out</span>
                <span>{{ optional($booking->check_out)->format('F d, Y') }}</span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Stay</span>
                <span>{{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
            </div>
        </div>
        <div class="account-summary-card">
            <div class="account-summary-row">
                <span class="account-summary-label">Payment status</span>
                <span><x-account.status-badge :status="$paymentStatus" /></span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Total price</span>
                <span class="account-summary-price">${{ number_format($booking->total_price, 2) }}</span>
            </div>
            @if ($payment)
                <div class="account-summary-row">
                    <span class="account-summary-label">Payment method</span>
                    <span>{{ ucfirst($payment->gateway) }}</span>
                </div>
                <div class="account-summary-row">
                    <span class="account-summary-label">Paid at</span>
                    <span>{{ optional($payment->paid_at)->format('F d, Y') ?? 'Pending' }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
