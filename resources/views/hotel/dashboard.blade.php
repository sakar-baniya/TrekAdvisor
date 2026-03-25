<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Hotel Owner</p>
                <h2 class="admin-page-title">Manage your hotel listings and booking activity</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-amber"><i class="fas fa-hotel"></i></div>
            <div>
                <p>Hotels</p>
                <h3>{{ number_format($stats['hotels']) }}</h3>
                <span>Your active listings</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-blue"><i class="fas fa-bed"></i></div>
            <div>
                <p>Rooms</p>
                <h3>{{ number_format($stats['rooms']) }}</h3>
                <span>Total room types</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-green"><i class="fas fa-calendar-check"></i></div>
            <div>
                <p>Bookings</p>
                <h3>{{ number_format($stats['bookings']) }}</h3>
                <span>Total booking records</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>My Hotels</h3>
                <p>Overview of the hotels registered under your account.</p>
            </div>
        </div>
        <div class="admin-note-stack">
            @forelse ($hotels as $hotel)
                <article class="admin-note-card">
                    <strong>{{ $hotel->name }}</strong>
                    <span>{{ $hotel->location }} | {{ $hotel->rooms_count }} room types | {{ $hotel->status }}</span>
                </article>
            @empty
                <p class="admin-table__empty">No hotels have been added to your account yet.</p>
            @endforelse
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Recent Hotel Bookings</h3>
                <p>Latest reservations attached to your hotel listings.</p>
            </div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Hotel</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Rooms</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hotelBookings as $booking)
                        <tr>
                            <td><span class="admin-table__ref">{{ $booking->booking_reference }}</span></td>
                            <td>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}</td>
                            <td>{{ optional($booking->check_in)->format('M d, Y') }}</td>
                            <td>{{ optional($booking->check_out)->format('M d, Y') }}</td>
                            <td>{{ $booking->num_rooms }}</td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="admin-badge {{ strtolower($booking->status) === 'confirmed' ? 'is-success' : 'is-warning' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No hotel bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-dashboard-layout>
