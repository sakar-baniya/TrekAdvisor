<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <h2 class="admin-page-title">Booking {{ $booking->booking_reference }}</h2>
            </div>
            <a href="{{ route('hotel_owner.bookings.index') }}" class="admin-secondary-button">
                <span>Back to Bookings</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Booking Details</h3>
                    <p>Customer and stay information</p>
                </div>
            </div>

            <div class="admin-info-list">
                <div><span>Customer</span><strong>{{ $booking->user?->name }} ({{ $booking->user?->email }})</strong></div>
                <div><span>Hotel</span><strong>{{ $booking->hotelRoom?->hotel?->name }}</strong></div>
                <div><span>Room Type</span><strong>{{ $booking->hotelRoom?->room_type }}</strong></div>
                <div><span>Stay</span><strong>{{ optional($booking->check_in)->format('M d') }} - {{ optional($booking->check_out)->format('M d, Y') }}</strong></div>
                <div><span>Rooms</span><strong>{{ $booking->num_rooms }}</strong></div>
                <div><span>Status</span><strong>{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</strong></div>
            </div>

            @if (filled($booking->hotelRoom?->hotel?->booking_policy))
                <div class="admin-review-full" style="margin-top: 1rem;">
                    <strong style="display: block; margin-bottom: 0.4rem;">Booking Policy</strong>
                    {!! nl2br(e($booking->hotelRoom->hotel->booking_policy)) !!}
                </div>
            @endif
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Pricing</h3>
                        <p>Booking amount breakdown</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Price per Night</span><strong>NPR {{ number_format($booking->price_per_night, 2) }}</strong></div>
                    <div><span>Number of Nights</span><strong>{{ $booking->num_nights }}</strong></div>
                    <div><span>Total</span><strong>NPR {{ number_format($booking->total_price, 2) }}</strong></div>
                    <div><span>Payment</span><strong>Not required online (manual settlement)</strong></div>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Status</h3>
                        <p>Update booking status</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('hotel_owner.bookings.status', $booking) }}" class="admin-status-form">
                    @csrf
                    @method('PATCH')

                    <label class="admin-field">
                        <span>Booking Status</span>
                        <select name="status" class="admin-input">
                            @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancellation_requested' => 'Cancellation Requested', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <button type="submit" class="admin-primary-button admin-primary-button--fit">
                        <span>Save Status</span>
                    </button>
                </form>
            </section>
        </aside>
    </section>
</x-dashboard-layout>
