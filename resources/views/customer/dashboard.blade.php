<x-app-layout>
    <section class="account-shell">
        <div class="container">
            <div class="account-header">
                <div>
                    <p class="market-kicker">My Account</p>
                    <h1>Welcome back, {{ auth()->user()->name }}!</h1>
                    <p>Track your treks and hotel stays from one dashboard.</p>
                </div>
            </div>

            <div class="account-stat-grid">
                <article class="account-stat-card">
                    <div class="account-stat-card__icon"><i class="fas fa-mountain"></i></div>
                    <div class="account-stat-card__body">
                        <strong>{{ $stats['trek_bookings'] }}</strong>
                        <span>Trek Bookings</span>
                    </div>
                </article>
                <article class="account-stat-card">
                    <div class="account-stat-card__icon"><i class="fas fa-hotel"></i></div>
                    <div class="account-stat-card__body">
                        <strong>{{ $stats['hotel_bookings'] }}</strong>
                        <span>Hotel Bookings</span>
                    </div>
                </article>
                <article class="account-stat-card">
                    <div class="account-stat-card__icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="account-stat-card__body">
                        <strong>{{ $stats['upcoming_trips'] }}</strong>
                        <span>Upcoming Trips</span>
                    </div>
                </article>
            </div>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>My Trek Bookings</h2>
                        <p>Your latest trek reservations and departures.</p>
                    </div>
                </div>

                <div class="account-card-stack">
                    @forelse ($trekBookings as $booking)
                        @php
                            $statusClass = match (strtolower($booking->status)) {
                                'confirmed' => 'is-success',
                                'pending' => 'is-warning',
                                'cancelled' => 'is-danger',
                                default => 'is-info',
                            };
                        @endphp
                        <article class="account-booking-card">
                            <div class="account-booking-card__top">
                                <div>
                                    <h3>{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h3>
                                    <div class="account-meta-grid">
                                        <span><i class="fas fa-calendar"></i> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-users"></i> {{ $booking->total_passengers }} Passengers</span>
                                        <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                                        <span><i class="fas fa-money-bill"></i> NPR {{ number_format($booking->total_price, 2) }}</span>
                                    </div>
                                </div>
                                <span class="account-status {{ $statusClass }}">{{ $booking->status }}</span>
                            </div>
                            <div class="account-actions">
                                <a href="{{ route('account.bookings.treks.show', $booking) }}" class="market-button">View Booking</a>
                                <a href="{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}" class="account-outline-button">View Trek</a>
                            </div>
                        </article>
                    @empty
                        <p class="empty-note">You do not have any trek bookings yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>My Hotel Bookings</h2>
                        <p>Recent room stays and check-in plans.</p>
                    </div>
                </div>

                <div class="account-card-stack">
                    @forelse ($hotelBookings as $booking)
                        @php
                            $statusClass = match (strtolower($booking->status)) {
                                'confirmed' => 'is-success',
                                'pending' => 'is-warning',
                                'cancelled' => 'is-danger',
                                default => 'is-info',
                            };
                        @endphp
                        <article class="account-booking-card">
                            <div class="account-booking-card__top">
                                <div>
                                    <h3>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}{{ $booking->hotelRoom?->room_type ? ' - ' . $booking->hotelRoom->room_type : '' }}</h3>
                                    <div class="account-meta-grid">
                                        <span><i class="fas fa-calendar-check"></i> Check-in: {{ optional($booking->check_in)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-calendar-times"></i> Check-out: {{ optional($booking->check_out)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-bed"></i> {{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
                                        <span><i class="fas fa-money-bill"></i> NPR {{ number_format($booking->total_price, 2) }}</span>
                                        @if(!empty($booking->booking_reference))
                                            <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="account-status {{ $statusClass }}">{{ $booking->status }}</span>
                            </div>
                            <div class="account-actions">
                                <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="market-button">View Booking</a>
                                <a href="{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}" class="account-outline-button">View Hotel</a>
                            </div>
                        </article>
                    @empty
                        <div class="account-empty">
                            <p>You do not have any hotel bookings yet.</p>
                            <a href="{{ route('hotels.index') }}" class="market-button">Browse Hotels</a>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </section>
</x-app-layout>

