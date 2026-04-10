<x-app-layout>
    @php
        $statusClass = match (strtolower($booking->status)) {
            'confirmed' => 'is-success',
            'pending' => 'is-warning',
            'cancelled' => 'is-danger',
            default => 'is-info',
        };
    @endphp

    <section class="account-shell">
        <div class="container">
            <div class="account-header">
                <div>
                    <p class="market-kicker">My Bookings</p>
                    <h1>Hotel Booking</h1>
                    <p>Reference {{ $booking->booking_reference ?? 'Hotel stay' }}</p>
                </div>
            </div>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}</h2>
                        <p>{{ $booking->hotelRoom?->room_type ? $booking->hotelRoom->room_type : 'Room details' }}</p>
                    </div>
                    <span class="account-status {{ $statusClass }}">{{ $booking->status }}</span>
                </div>

                <div class="account-meta-grid">
                    <span><i class="fas fa-calendar-check"></i> Check-in: {{ optional($booking->check_in)->format('F d, Y') }}</span>
                    <span><i class="fas fa-calendar-times"></i> Check-out: {{ optional($booking->check_out)->format('F d, Y') }}</span>
                    <span><i class="fas fa-bed"></i> {{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
                    <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                </div>

                <div class="account-actions">
                    <a href="{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}" class="market-button">View Hotel</a>
                    <a href="{{ route('customer.dashboard') }}" class="account-outline-button">Back to Dashboard</a>
                </div>
            </section>
        </div>
    </section>
</x-app-layout>
