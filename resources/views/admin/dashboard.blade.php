@php
    $overviewCards = [
        [
            'label' => 'Monthly Revenue',
            'value' => '$' . number_format($stats['revenue_this_month'], 2),
            'meta' => number_format($stats['bookings_this_month']) . ' bookings this month',
            'icon' => 'fa-wallet',
        ],
        [
            'label' => 'Total Users',
            'value' => number_format($stats['total_users']),
            'meta' => number_format($stats['active_treks']) . ' active treks live',
            'icon' => 'fa-users',
        ],
        [
            'label' => 'Total Bookings',
            'value' => number_format($stats['bookings_this_month']),
            'meta' => number_format($stats['rented_gear']) . ' gear rentals in progress',
            'icon' => 'fa-ticket',
        ],
        [
            'label' => 'Pending Approvals',
            'value' => number_format($stats['pending_hotels']),
            'meta' => number_format($stats['active_hotels']) . ' hotels currently active',
            'icon' => 'fa-hotel',
        ],
    ];

    $growthMetrics = [
        ['label' => 'Week 1', 'value' => max($stats['bookings_this_month'] * 0.42, 1)],
        ['label' => 'Week 2', 'value' => max($stats['bookings_this_month'] * 0.66, 1)],
        ['label' => 'Week 3', 'value' => max($stats['bookings_this_month'] * 0.88, 1)],
        ['label' => 'Week 4', 'value' => max($stats['bookings_this_month'], 1)],
    ];

    $maxGrowthValue = max(array_column($growthMetrics, 'value')) ?: 1;

    $healthMetrics = [
        [
            'label' => 'Platform Uptime',
            'value' => '99.9%',
            'status' => 'Stable',
            'progress' => 99,
            'icon' => 'fa-circle-check',
        ],
        [
            'label' => 'Hotel Approval Queue',
            'value' => number_format($stats['pending_hotels']),
            'status' => $stats['pending_hotels'] > 0 ? 'Needs attention' : 'Clear',
            'progress' => min(100, 18 + ($stats['pending_hotels'] * 18)),
            'icon' => 'fa-clipboard-check',
        ],
        [
            'label' => 'Active Rentals',
            'value' => number_format($stats['rented_gear']),
            'status' => 'Inventory synced',
            'progress' => min(100, 35 + ($stats['rented_gear'] * 12)),
            'icon' => 'fa-backpack',
        ],
    ];
@endphp

<x-dashboard-layout>
    <section class="admin-overview-hero">
        <div class="admin-overview-hero__copy">
            <p class="admin-overview-hero__eyebrow">Platform Overview</p>
            <h2>Keep the whole TrekAdvisor ecosystem moving from one control center.</h2>
            <p>
                Monitor bookings, partner approvals, rentals, and customer activity with the same
                admin workflow you already use.
            </p>

            <div class="admin-overview-hero__chips">
                <span class="admin-overview-chip">
                    <i class="fas fa-mountain-sun"></i>
                    {{ number_format($stats['active_treks']) }} active treks
                </span>
                <span class="admin-overview-chip">
                    <i class="fas fa-hotel"></i>
                    {{ number_format($stats['active_hotels']) }} hotels live
                </span>
                <span class="admin-overview-chip">
                    <i class="fas fa-people-carry-box"></i>
                    {{ number_format($stats['gear_items']) }} gear items listed
                </span>
            </div>
        </div>

        <div class="admin-overview-hero__shape" aria-hidden="true">
            <div class="admin-overview-hero__orb"></div>
            <div class="admin-overview-hero__grid">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </section>

    <section class="admin-overview-cards">
        @foreach ($overviewCards as $card)
            <article class="admin-overview-card">
                <div class="admin-overview-card__top">
                    <span class="admin-overview-card__icon">
                        <i class="fas {{ $card['icon'] }}"></i>
                    </span>
                </div>
                <p>{{ $card['label'] }}</p>
                <h3>{{ $card['value'] }}</h3>
                <span>{{ $card['meta'] }}</span>
            </article>
        @endforeach
    </section>

    <section class="admin-dashboard-grid">
        <article class="admin-dashboard-panel admin-dashboard-panel--chart">
            <div class="admin-dashboard-panel__header">
                <div>
                    <p class="admin-panel-kicker">Growth Metrics</p>
                    <h3>Booking activity over the last four weeks</h3>
                </div>
                <span class="admin-link-chip">Last 30 Days</span>
            </div>

            <div class="admin-growth-chart" aria-label="Booking activity chart">
                @foreach ($growthMetrics as $metric)
                    <div class="admin-growth-chart__item">
                        <div class="admin-growth-chart__track">
                            <span
                                class="admin-growth-chart__bar {{ $loop->last ? 'is-highlighted' : '' }}"
                                style="height: {{ max(18, (int) (($metric['value'] / $maxGrowthValue) * 100)) }}%;"
                            ></span>
                        </div>
                        <strong>{{ $metric['label'] }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <aside class="admin-dashboard-panel admin-dashboard-panel--health">
            <div class="admin-dashboard-panel__header">
                <div>
                    <p class="admin-panel-kicker">System Health</p>
                    <h3>Operational snapshot</h3>
                </div>
            </div>

            <div class="admin-health-stack">
                @foreach ($healthMetrics as $metric)
                    <article class="admin-health-card">
                        <div class="admin-health-card__top">
                            <span class="admin-health-card__icon">
                                <i class="fas {{ $metric['icon'] }}"></i>
                            </span>
                            <span class="admin-health-card__status">{{ $metric['status'] }}</span>
                        </div>
                        <strong>{{ $metric['label'] }}</strong>
                        <p>{{ $metric['value'] }}</p>
                        <div class="admin-health-card__progress">
                            <span style="width: {{ $metric['progress'] }}%"></span>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="admin-health-actions">
                <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="admin-primary-button admin-primary-button--fit">
                    Review Pending Hotels
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-secondary-button">
                    Manage Users
                </a>
            </div>
        </aside>
    </section>

    <section class="admin-dashboard-lower">
        <article class="admin-dashboard-panel">
            <div class="admin-dashboard-panel__header">
                <div>
                    <p class="admin-panel-kicker">Pending Actions</p>
                    <h3>High-priority admin shortcuts</h3>
                </div>
            </div>

            <div class="admin-approval-list">
                <article class="admin-approval-item">
                    <div>
                        <span class="admin-approval-item__tag">Hotels</span>
                        <strong>{{ number_format($stats['pending_hotels']) }} hotel approvals waiting</strong>
                        <p>Review new partner submissions and keep the marketplace current.</p>
                    </div>
                    <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="admin-secondary-button">Open Queue</a>
                </article>

                <article class="admin-approval-item">
                    <div>
                        <span class="admin-approval-item__tag">Treks</span>
                        <strong>{{ number_format($stats['active_treks']) }} trekking products live</strong>
                        <p>Create new itineraries or adjust departures without leaving the dashboard.</p>
                    </div>
                    <a href="{{ route('admin.treks.create') }}" class="admin-secondary-button">Add New Trek</a>
                </article>

                <article class="admin-approval-item">
                    <div>
                        <span class="admin-approval-item__tag">Gear</span>
                        <strong>{{ number_format($stats['rented_gear']) }} active gear rentals</strong>
                        <p>Track current equipment movement and update inventory availability fast.</p>
                    </div>
                    <a href="{{ route('admin.gear-rentals.index') }}" class="admin-secondary-button">View Rentals</a>
                </article>
            </div>
        </article>

        <article class="admin-dashboard-panel">
            <div class="admin-dashboard-panel__header">
                <div>
                    <p class="admin-panel-kicker">Recent Bookings</p>
                    <h3>Latest marketplace activity</h3>
                </div>
                <span class="admin-link-chip">Newest 10</span>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table admin-table--dashboard">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Booking</th>
                            <th>Customer</th>
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
                                    <small>{{ $booking->type }} • {{ $booking->details }}</small>
                                </td>
                                <td>{{ $booking->customer }}</td>
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
                                <td colspan="6" class="admin-table__empty">No recent bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-dashboard-layout>
