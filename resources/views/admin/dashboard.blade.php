<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Dashboard Overview</p>
                <h2 class="admin-page-title">Track bookings, revenue, and approvals in one place</h2>
            </div>
            <div class="admin-page-heading__meta">
                <span class="admin-date-chip">
                    <i class="fas fa-calendar-days"></i>
                    {{ now()->format('M d, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-blue"><i class="fas fa-chart-column"></i></div>
            <div>
                <p>Bookings</p>
                <h3>{{ number_format($stats['bookings_this_month']) }}</h3>
                <span>This month</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-green"><i class="fas fa-wallet"></i></div>
            <div>
                <p>Revenue</p>
                <h3>${{ number_format($stats['revenue_this_month'], 2) }}</h3>
                <span>This month</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-violet"><i class="fas fa-users"></i></div>
            <div>
                <p>Users</p>
                <h3>{{ number_format($stats['total_users']) }}</h3>
                <span>Total accounts</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-amber"><i class="fas fa-mountain-sun"></i></div>
            <div>
                <p>Treks</p>
                <h3>{{ number_format($stats['active_treks']) }}</h3>
                <span>Active treks</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-slate"><i class="fas fa-hotel"></i></div>
            <div>
                <p>Hotels</p>
                <h3>{{ number_format($stats['active_hotels']) }}</h3>
                <span>{{ $stats['pending_hotels'] }} pending approval</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-rose"><i class="fas fa-people-carry-box"></i></div>
            <div>
                <p>Gear</p>
                <h3>{{ number_format($stats['gear_items']) }}</h3>
                <span>{{ $stats['rented_gear'] }} active rentals</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Recent Bookings</h3>
                <p>Latest activity across treks, hotels, and gear rentals</p>
            </div>
            <span class="admin-link-chip">Newest 10</span>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td><span class="admin-table__ref">{{ $booking->reference }}</span></td>
                            <td>
                                <strong>{{ $booking->title }}</strong>
                                <small>{{ $booking->type }}</small>
                            </td>
                            <td>{{ $booking->customer }}</td>
                            <td>{{ $booking->details }}</td>
                            <td>${{ number_format($booking->amount, 2) }}</td>
                            <td>
                                <span class="admin-badge {{ strtolower($booking->status) === 'confirmed' || strtolower($booking->status) === 'success' || strtolower($booking->status) === 'active' ? 'is-success' : (strtolower($booking->status) === 'pending' ? 'is-warning' : 'is-muted') }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td>{{ $booking->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No recent bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-quick-grid">
        <div class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Quick Actions</h3>
                    <p>Shortcuts for common admin tasks</p>
                </div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.treks.create') }}" class="admin-primary-button">
                    <i class="fas fa-plus"></i>
                    <span>Add New Trek</span>
                </a>
                <a href="{{ route('admin.treks.index') }}" class="admin-secondary-button">
                    <i class="fas fa-route"></i>
                    <span>Manage Treks</span>
                </a>
                <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="admin-secondary-button">
                    <i class="fas fa-circle-check"></i>
                    <span>Approve Hotels ({{ $stats['pending_hotels'] }})</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-secondary-button">
                    <i class="fas fa-user-shield"></i>
                    <span>Manage Users</span>
                </a>
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>System Notes</h3>
                    <p>Useful operational snapshots</p>
                </div>
            </div>
            <div class="admin-note-stack">
                <article class="admin-note-card">
                    <strong>{{ $stats['pending_hotels'] }} hotels are waiting for review</strong>
                    <span>Use the hotel management page to approve or deactivate listings.</span>
                </article>
                <article class="admin-note-card">
                    <strong>{{ $stats['rented_gear'] }} gear rentals need tracking</strong>
                    <span>Once gear pages are wired in, return handling will update stock automatically.</span>
                </article>
                <article class="admin-note-card">
                    <strong>{{ $stats['bookings_this_month'] }} bookings recorded this month</strong>
                    <span>The dashboard is ready to support the next admin pages.</span>
                </article>
            </div>
        </div>
    </section>
</x-dashboard-layout>
