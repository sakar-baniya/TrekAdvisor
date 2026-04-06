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
                <article class="account-stat-card is-cyan">
                    <div class="account-stat-card__icon"><i class="fas fa-mountain"></i></div>
                    <div>
                        <strong>{{ $stats['trek_bookings'] }}</strong>
                        <span>Trek Bookings</span>
                    </div>
                </article>
                <article class="account-stat-card is-green">
                    <div class="account-stat-card__icon"><i class="fas fa-hotel"></i></div>
                    <div>
                        <strong>{{ $stats['hotel_bookings'] }}</strong>
                        <span>Hotel Bookings</span>
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
                        <article class="account-booking-card">
                            <div class="account-booking-card__top">
                                <div>
                                    <h3>{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h3>
                                    <div class="account-meta-grid">
                                        <span><i class="fas fa-calendar"></i> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-users"></i> {{ $booking->total_passengers }} Passengers</span>
                                        <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                                        <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    </div>
                                </div>
                                <span class="account-status {{ strtolower($booking->status) === 'confirmed' ? 'is-success' : 'is-warning' }}">{{ $booking->status }}</span>
                            </div>
                            <div class="account-actions">
                                <a href="{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}" class="market-button">View Trek</a>
                                <a href="{{ route('profile.edit') }}" class="account-outline-button">Manage Profile</a>
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
                        <article class="account-booking-card">
                            <div class="account-booking-card__top">
                                <div>
                                    <h3>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}{{ $booking->hotelRoom?->room_type ? ' - ' . $booking->hotelRoom->room_type : '' }}</h3>
                                    <div class="account-meta-grid">
                                        <span><i class="fas fa-calendar-check"></i> Check-in: {{ optional($booking->check_in)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-calendar-times"></i> Check-out: {{ optional($booking->check_out)->format('F d, Y') }}</span>
                                        <span><i class="fas fa-bed"></i> {{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
                                        <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    </div>
                                </div>
                                <span class="account-status {{ strtolower($booking->status) === 'confirmed' ? 'is-success' : 'is-warning' }}">{{ $booking->status }}</span>
                            </div>
                        </article>
                    @empty
                        <p class="empty-note">You do not have any hotel bookings yet.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </section>
</x-app-layout>

