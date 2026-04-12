<x-dashboard-layout>
    <!-- 1️⃣ Header -->
    <div class="page-header">
        <div class="page-header__content">
            <h1>Staff Console</h1>
            <p>Track trek booking activity and handle requests.</p>
        </div>
        <div class="page-header__actions">
            <a href="{{ route('staff.trek-bookings.index') }}" class="btn btn-primary">
                <i class="fas fa-mountain-sun"></i> Trek bookings
            </a>
        </div>
    </div>

    <!-- 2️⃣ Stats -->
    <section class="stat-grid">
        <x-dashboard.stat-card 
            label="Today's Trek Bookings"
            value="{{ number_format($stats['today_trek_bookings']) }}"
            icon="fa-mountain-sun"
        />
        
        <x-dashboard.stat-card 
            label="Pending Bookings"
            value="{{ number_format($stats['pending_trek_bookings']) }}"
            icon="fa-clock"
        />
        
        <x-dashboard.stat-card 
            label="Cancellation Requests"
            value="{{ number_format($stats['cancellation_requests']) }}"
            icon="fa-triangle-exclamation"
        />
    </section>

    <!-- 3️⃣ Main Analytics -->
    <div class="dashboard-grid">
        <div class="card">
            <div class="card__header" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 class="card__title text-navy">Booking Activity</h3>
                    <p class="text-muted mt-1">Trek bookings created over the last 7 days.</p>
                </div>
            </div>
            <div class="chart-container" style="height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <div class="card" style="padding: 1.25rem;">
            <div class="card__header" style="margin-bottom: 1rem;">
                <div>
                    <h3 class="card__title text-navy">Recent Trek Bookings</h3>
                    <p class="text-muted mt-1">Latest requests from customers.</p>
                </div>
                <a href="{{ route('staff.trek-bookings.index') }}" class="btn btn-secondary">View all</a>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ref#</th>
                            <th>Trek</th>
                            <th>Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTrekBookings as $booking)
                            <tr>
                                <td class="admin-table__ref">{{ $booking->booking_reference }}</td>
                                <td>
                                    <strong>{{ $booking->departure?->trek?->title ?? 'Unknown Trek' }}</strong>
                                    <small>{{ optional($booking->departure?->start_date)->format('M d, Y') }} departure</small>
                                </td>
                                <td>
                                    <strong>{{ $booking->user?->name ?? 'Unknown customer' }}</strong>
                                    <small>{{ $booking->user?->email }}</small>
                                </td>
                                <td>
                                    <span class="admin-badge {{ $booking->status === 'confirmed' ? 'is-success' : ($booking->status === 'pending' ? 'is-warning' : 'is-muted') }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="admin-table__empty">No trek bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxActivity = document.getElementById('activityChart').getContext('2d');
            const dataSet = {!! json_encode($charts['activity']) !!};

            new Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: dataSet.map(d => d.label),
                    datasets: [{
                        label: 'Volume',
                        data: dataSet.map(d => d.count),
                        borderColor: '#0f172a',
                        backgroundColor: 'rgba(15, 23, 42, 0.03)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#94a3b8', font: { size: 10 } } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 10 } } 
                        }
                    }
                }
            });
        });
    </script>
</x-dashboard-layout>
