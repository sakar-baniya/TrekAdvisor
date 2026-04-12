<x-app-layout>
    @php
        $formatPayment = function ($payments) {
            $latest = $payments?->sortByDesc(fn ($payment) => $payment->paid_at ?? $payment->created_at)->first();
            if (! $latest) {
                return ['label' => 'Unpaid', 'status' => 'is-warning'];
            }
            return match ($latest->status) {
                'success' => ['label' => 'Paid', 'status' => 'is-success'],
                'pending' => ['label' => 'Pending', 'status' => 'is-warning'],
                'failed' => ['label' => 'Failed', 'status' => 'is-danger'],
                default => ['label' => ucfirst($latest->status), 'status' => 'is-info'],
            };
        };
    @endphp

    <section class="account-shell">
        <div class="container">
            <div class="account-header">
                <div>
                    <p class="market-kicker">My Account</p>
                    <h1>My Bookings</h1>
                    <p>Manage upcoming and past trek or hotel bookings in one place.</p>
                </div>
            </div>

            <div class="account-tabs" data-account-tabs>
                <button class="account-tab is-active" type="button" data-tab="upcoming">Upcoming</button>
                <button class="account-tab" type="button" data-tab="past">Past</button>
            </div>

            <section class="account-tab-panel is-active" data-tab-panel="upcoming">
                <section class="account-panel">
                    <div class="account-panel__head">
                        <div>
                            <h2>Trek Bookings</h2>
                            <p>Your upcoming trek departures and pending confirmations.</p>
                        </div>
                    </div>

                    <div class="account-card-stack">
                        @forelse ($trekUpcoming as $booking)
                            @php
                                $payment = $formatPayment($booking->payments);
                            @endphp
                            <x-account.booking-card :title="$booking->departure?->trek?->title ?? 'Trek Booking'" :status="$booking->status">
                                <x-slot name="meta">
                                    <span><i class="fas fa-calendar"></i> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-users"></i> {{ $booking->total_passengers }} Passengers</span>
                                    <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                                    <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    <span><i class="fas fa-credit-card"></i> {{ $payment['label'] }}</span>
                                </x-slot>
                                <x-slot name="actions">
                                    <a href="{{ route('account.bookings.treks.show', $booking) }}" class="market-button">View Booking</a>
                                    <a href="{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}" class="account-outline-button">View Trek</a>
                                </x-slot>
                            </x-account.booking-card>
                        @empty
                            <div class="account-empty">
                                <p>You have no upcoming trek bookings.</p>
                                <a href="{{ route('treks.index') }}" class="market-button">Browse Treks</a>
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="account-panel">
                    <div class="account-panel__head">
                        <div>
                            <h2>Hotel Bookings</h2>
                            <p>Upcoming stays and reservations awaiting confirmation.</p>
                        </div>
                    </div>

                    <div class="account-card-stack">
                        @forelse ($hotelUpcoming as $booking)
                            @php
                                $payment = $formatPayment($booking->payments);
                            @endphp
                            <x-account.booking-card :title="$booking->hotelRoom?->hotel?->name ?? 'Hotel Booking'" :status="$booking->status">
                                <x-slot name="meta">
                                    <span><i class="fas fa-calendar-check"></i> Check-in: {{ optional($booking->check_in)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-calendar-times"></i> Check-out: {{ optional($booking->check_out)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-bed"></i> {{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
                                    <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    <span><i class="fas fa-credit-card"></i> {{ $payment['label'] }}</span>
                                </x-slot>
                                <x-slot name="actions">
                                    <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="market-button">View Booking</a>
                                    <a href="{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}" class="account-outline-button">View Hotel</a>
                                </x-slot>
                            </x-account.booking-card>
                        @empty
                            <div class="account-empty">
                                <p>You have no upcoming hotel bookings.</p>
                                <a href="{{ route('hotels.index') }}" class="market-button">Browse Hotels</a>
                            </div>
                        @endforelse
                    </div>
                </section>
            </section>

            <section class="account-tab-panel" data-tab-panel="past">
                <section class="account-panel">
                    <div class="account-panel__head">
                        <div>
                            <h2>Past Trek Bookings</h2>
                            <p>Review completed or cancelled treks.</p>
                        </div>
                    </div>

                    <div class="account-card-stack">
                        @forelse ($trekPast as $booking)
                            @php
                                $payment = $formatPayment($booking->payments);
                                $trek = $booking->departure?->trek;
                                $review = $trek?->id ? ($trekReviewMap[$trek->id] ?? null) : null;
                            @endphp
                            <x-account.booking-card :title="$trek?->title ?? 'Trek Booking'" :status="$booking->status">
                                <x-slot name="meta">
                                    <span><i class="fas fa-calendar"></i> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-users"></i> {{ $booking->total_passengers }} Passengers</span>
                                    <span><i class="fas fa-hashtag"></i> {{ $booking->booking_reference }}</span>
                                    <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    <span><i class="fas fa-credit-card"></i> {{ $payment['label'] }}</span>
                                </x-slot>
                                <x-slot name="actions">
                                    <a href="{{ route('account.bookings.treks.show', $booking) }}" class="market-button">View Booking</a>
                                    <a href="{{ route('treks.show', $trek?->slug ?? '#') }}" class="account-outline-button">View Trek</a>
                                </x-slot>
                                <x-slot name="review">
                                    @if ($booking->status === 'completed')
                                        @if ($review)
                                            <a href="{{ route('account.bookings.treks.show', $booking) }}#review" class="account-outline-button">Edit Review</a>
                                        @else
                                            <a href="{{ route('account.bookings.treks.show', $booking) }}#review" class="account-outline-button">Write Review</a>
                                        @endif
                                    @else
                                        <span class="account-review-note">Review available after completion.</span>
                                    @endif
                                </x-slot>
                            </x-account.booking-card>
                        @empty
                            <div class="account-empty">
                                <p>No past trek bookings yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="account-panel">
                    <div class="account-panel__head">
                        <div>
                            <h2>Past Hotel Bookings</h2>
                            <p>Your completed or cancelled hotel stays.</p>
                        </div>
                    </div>

                    <div class="account-card-stack">
                        @forelse ($hotelPast as $booking)
                            @php
                                $payment = $formatPayment($booking->payments);
                                $hotel = $booking->hotelRoom?->hotel;
                                $review = $hotel?->id ? ($hotelReviewMap[$hotel->id] ?? null) : null;
                            @endphp
                            <x-account.booking-card :title="$hotel?->name ?? 'Hotel Booking'" :status="$booking->status">
                                <x-slot name="meta">
                                    <span><i class="fas fa-calendar-check"></i> Check-in: {{ optional($booking->check_in)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-calendar-times"></i> Check-out: {{ optional($booking->check_out)->format('F d, Y') }}</span>
                                    <span><i class="fas fa-bed"></i> {{ $booking->num_rooms }} Rooms x {{ $booking->num_nights }} Nights</span>
                                    <span><i class="fas fa-money-bill"></i> ${{ number_format($booking->total_price, 2) }}</span>
                                    <span><i class="fas fa-credit-card"></i> {{ $payment['label'] }}</span>
                                </x-slot>
                                <x-slot name="actions">
                                    <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="market-button">View Booking</a>
                                    <a href="{{ route('hotels.show', $hotel ?? '#') }}" class="account-outline-button">View Hotel</a>
                                </x-slot>
                                <x-slot name="review">
                                    @if ($booking->status === 'completed')
                                        @if ($review)
                                            <a href="{{ route('account.bookings.hotels.show', $booking) }}#review" class="account-outline-button">Edit Review</a>
                                        @else
                                            <a href="{{ route('account.bookings.hotels.show', $booking) }}#review" class="account-outline-button">Write Review</a>
                                        @endif
                                    @else
                                        <span class="account-review-note">Review available after completion.</span>
                                    @endif
                                </x-slot>
                            </x-account.booking-card>
                        @empty
                            <div class="account-empty">
                                <p>No past hotel bookings yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </section>
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-account-tabs]').forEach((tabs) => {
            const buttons = tabs.querySelectorAll('[data-tab]');
            const panels = document.querySelectorAll('[data-tab-panel]');
            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    buttons.forEach((btn) => btn.classList.remove('is-active'));
                    panels.forEach((panel) => panel.classList.remove('is-active'));
                    button.classList.add('is-active');
                    document.querySelector(`[data-tab-panel="${button.dataset.tab}"]`)?.classList.add('is-active');
                });
            });
        });
    </script>
</x-app-layout>
