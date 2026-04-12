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
                    <h1>Trek Booking</h1>
                    <p>Reference {{ $booking->booking_reference }}</p>
                </div>
            </div>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h2>
                        <p>Departure details and pricing summary.</p>
                    </div>
                    <span class="account-status {{ $statusClass }}">{{ $booking->status }}</span>
                </div>

                <div class="account-meta-grid">
                    <span><i class="fas fa-calendar"></i> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
                    <span><i class="fas fa-users"></i> {{ $booking->total_passengers }} Passengers</span>
                    <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                    <span><i class="fas fa-money-bill"></i> NPR {{ number_format($booking->total_price, 2) }}</span>
                </div>

                <div class="account-actions">
                    <a href="{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}" class="market-button">View Trek</a>
                    <a href="{{ route('customer.dashboard') }}" class="account-outline-button">Back to Dashboard</a>
                </div>
            </section>
        </div>
    </section>
</x-app-layout>
