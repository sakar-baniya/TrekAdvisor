<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <h2 class="admin-page-title">Hotel Owner Dashboard</h2>
                <p class="admin-eyebrow">Track requests, confirm stays, and manage room inventory</p>
            </div>
            <div class="admin-actions-row" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('hotel_owner.bookings.index') }}" class="admin-secondary-button"><span>Manage Bookings</span></a>
                <a href="{{ route('hotel_owner.hotels.index') }}" class="admin-secondary-button"><span>My Hotels</span></a>
                <a href="{{ route('hotel_owner.hotels.create') }}" class="admin-primary-button"><span>Add Hotel</span></a>
            </div>
        </div>
    </x-slot>

    <section class="stat-grid">
        <x-dashboard.stat-card label="Projected Revenue" value="NPR {{ number_format($stats['revenue_this_month'], 0) }}" meta="Confirmed/completed check-ins this month" icon="fa-wallet" />
        <x-dashboard.stat-card label="Active Bookings" value="{{ number_format($stats['active_bookings']) }}" meta="Pending + confirmed stays" icon="fa-calendar-check" />
        <x-dashboard.stat-card label="Pending Requests" value="{{ number_format($stats['pending_requests']) }}" meta="Needs your confirmation" icon="fa-hourglass-half" />
        <x-dashboard.stat-card label="Upcoming Check-ins" value="{{ number_format($stats['upcoming_checkins']) }}" meta="Next 7 days" icon="fa-door-open" />
    </section>

    <section class="admin-show-grid" style="margin-top: 1rem;">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Weekly Revenue Trend</h3>
                    <p>Based on confirmed/completed check-ins</p>
                </div>
            </div>
            <div style="height: 260px;">
                <canvas id="hotelRevenueChart"></canvas>
            </div>
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Booking Pipeline</h3>
                        <p>Current request and stay status</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Pending</span><strong>{{ $statusBreakdown['pending'] }}</strong></div>
                    <div><span>Confirmed</span><strong>{{ $statusBreakdown['confirmed'] }}</strong></div>
                    <div><span>Cancellation Requested</span><strong>{{ $statusBreakdown['cancellation_requested'] }}</strong></div>
                    <div><span>Completed</span><strong>{{ $statusBreakdown['completed'] }}</strong></div>
                    <div><span>Cancelled</span><strong>{{ $statusBreakdown['cancelled'] }}</strong></div>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Inventory Snapshot</h3>
                        <p>Properties you currently manage</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Total Hotels</span><strong>{{ $stats['hotels'] }}</strong></div>
                    <div><span>Total Room Types</span><strong>{{ $stats['rooms'] }}</strong></div>
                    <div><span>Bookings This Month</span><strong>{{ $stats['bookings_this_month'] }}</strong></div>
                    <div><span>Cancellation Requests</span><strong>{{ $stats['cancellation_requests'] }}</strong></div>
                </div>
            </section>
        </aside>
    </section>

    <section class="admin-panel" style="margin-top: 1rem;">
        <div class="admin-panel__header">
            <div>
                <h3>Recent Booking Requests</h3>
                <p>Open and latest stays across all your hotels</p>
            </div>
            <a href="{{ route('hotel_owner.bookings.index') }}" class="admin-link-button">View All</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Hotel / Room</th>
                        <th>Customer</th>
                        <th>Stay</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hotelBookings as $booking)
                        <tr>
                            <td class="admin-table__ref">{{ $booking->booking_reference }}</td>
                            <td>
                                <strong>{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel' }}</strong>
                                <small>{{ $booking->hotelRoom?->room_type ?? 'Room' }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->user?->name ?? 'Customer' }}</strong>
                                <small>{{ $booking->user?->email }}</small>
                            </td>
                            <td>{{ optional($booking->check_in)->format('M d') }} - {{ optional($booking->check_out)->format('M d, Y') }}</td>
                            <td>NPR {{ number_format($booking->total_price, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</td>
                            <td>
                                <a href="{{ route('hotel_owner.bookings.show', $booking) }}" class="admin-secondary-button"><span>Open</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No booking requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('hotelRevenueChart');
            if (!canvas) {
                return;
            }

            const ctx = canvas.getContext('2d');
            const dataRow = {!! json_encode($charts['revenue']) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataRow.map(item => item.label),
                    datasets: [{
                        label: 'Revenue',
                        data: dataRow.map(item => item.revenue),
                        backgroundColor: '#1e293b',
                        borderRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'NPR ' + Number(value).toLocaleString();
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
</x-dashboard-layout>
