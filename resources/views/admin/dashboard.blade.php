<x-dashboard-layout>
    <!-- 1️⃣  Key Statistics Row -->
    <section class="dashboard-stats-grid">
        <x-dashboard.stat-card 
            label="Total Booked"
            value="${{ number_format($stats['revenue_this_month'], 0) }}"
            meta="{{ number_format($stats['bookings_this_month']) }} bookings this month"
            icon="fa-wallet"
            trend="+12.5%"
        />
        
        <x-dashboard.stat-card 
            label="30 Days Revenue"
            value="${{ number_format($stats['revenue_this_month'] * 0.8, 0) }}"
            meta="Total income in last 30 days"
            icon="fa-chart-line"
            trend="-2.5%"
            trendDirection="down"
        />
        
        <x-dashboard.stat-card 
            label="Total Customers"
            value="{{ number_format($stats['total_users']) }}"
            meta="{{ number_format($stats['active_treks']) }} active treks"
            icon="fa-users"
            trend="+8.2%"
        />
        
        <x-dashboard.stat-card 
            label="Tour Packages"
            value="{{ number_format($stats['active_treks']) }}"
            meta="{{ number_format($stats['active_hotels']) }} hotels live"
            icon="fa-mountain-sun"
            trend="+5.1%"
        />
    </section>

    <!-- 2️⃣  Pending Tasks Section -->
    <section class="dashboard-pending-section">
        <h3 class="dashboard-section-title">Pending Task</h3>
        
        <div class="dashboard-pending-grid">
            <x-dashboard.pending-task
                title="{{ number_format($stats['pending_hotels']) }} Hotel Approvals Waiting"
                amount="High Priority"
                description="Review new partner submissions and keep the marketplace current."
                icon="fa-hotel"
                tag="Hotels"
                actionLabel="Open Queue"
                actionUrl="{{ route('admin.hotels.index', ['status' => 'Pending']) }}"
            />



            <x-dashboard.pending-task
                title="{{ number_format($stats['active_treks']) }} Trekking Products Live"
                amount="Active"
                description="Create new itineraries or adjust departures without leaving the dashboard."
                icon="fa-mountain-sun"
                tag="Treks"
                actionLabel="Add New Trek"
                actionUrl="{{ route('admin.treks.create') }}"
            />
        </div>
    </section>

    <!-- 3️⃣  Recent Bookings Table -->
    <section class="dashboard-activity-section">
        <div class="dashboard-activity-header">
            <div>
                <h3>Recent Booking</h3>
                <p>Latest marketplace activity</p>
            </div>
            <span class="dashboard-badge">Newest 10</span>
        </div>

        @if($recentBookings->count() > 0)
            <div class="dashboard-activity-table">
                <table>
                    <thead>
                        <tr>
                            <th>Package Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings->take(8) as $booking)
                            <tr>
                                <td>
                                    <div class="table-booking-info">
                                        <strong>{{ $booking->title }}</strong>
                                        <small>{{ $booking->details }}</small>
                                    </div>
                                </td>
                                <td><span class="table-type-badge">{{ $booking->type }}</span></td>
                                <td><strong>${{ number_format($booking->amount, 2) }}</strong></td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($booking->status)) {
                                            'confirmed', 'success', 'active' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            default => 'badge-muted',
                                        };
                                    @endphp
                                    <span class="table-status-badge {{ $statusClass }}">{{ $booking->status }}</span>
                                </td>
                                <td>
                                    <button class="table-action-btn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="dashboard-empty-state">
                <p><i class="fas fa-inbox"></i> No recent bookings yet</p>
            </div>
        @endif
    </section>
</x-dashboard-layout>

