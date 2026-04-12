<x-dashboard-layout>
    <!-- 1️⃣ Header -->
    <div class="page-header">
        <div class="page-header__content">
            <h1>Hotel Partner Hub</h1>
            <p>Manage your bookings and track earnings.</p>
        </div>
        <div class="page-header__actions">
            <a href="{{ route('hotel_owner.hotels.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Hotel Entry
            </a>
        </div>
    </div>

    <!-- 2️⃣ Stats -->
    <section class="stat-grid">
        <x-dashboard.stat-card 
            label="Monthly Revenue"
            value="${{ number_format($stats['revenue_this_month'], 0) }}"
            meta="This month"
            icon="fa-wallet"
            trend="+15%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="Total Bookings"
            value="{{ number_format($stats['bookings_this_month']) }}"
            icon="fa-calendar-check"
            trend="+10%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="My Hotels"
            value="{{ number_format($stats['hotels']) }}"
            meta="{{ number_format($stats['rooms']) }} rooms"
            icon="fa-hotel"
        />

        <x-dashboard.stat-card 
            label="Average Rating"
            value="4.8/5"
            icon="fa-star"
            trend="+0.2"
            trendDirection="up"
        />
    </section>

    <!-- 3️⃣ Main Analytics -->
    <div class="dashboard-grid">
        <div class="card">
            <div class="card__header" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 class="card__title text-navy">Monthly Revenue</h3>
                    <p class="text-muted mt-1">Total payout trend over the last 4 weeks.</p>
                </div>
            </div>
            <div class="chart-container" style="height: 280px;">
                <canvas id="hotelRevenueChart"></canvas>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card" style="padding: 1.25rem;">
                <h3 class="card__title mb-1 text-navy">Partner Support</h3>
                <p class="text-muted mb-3" style="font-size: 0.75rem;">Get help with your properties or bookings.</p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="#" class="btn btn-secondary justify-content-start border-0" style="background: var(--u-bg); font-weight: 700; font-size: 0.8rem;">
                        <i class="fas fa-headset text-navy"></i> Contact Support
                    </a>
                    <a href="#" class="btn btn-secondary justify-content-start border-0" style="background: var(--u-bg); font-weight: 700; font-size: 0.8rem;">
                        <i class="fas fa-file-invoice-dollar text-navy"></i> Request Payout
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-start border-4 border-success" style="padding: 1.25rem;">
                <h3 class="card__title mb-2 text-success">Account Health</h3>
                <p class="text-muted mb-0" style="font-size: 0.825rem; line-height: 1.5;">All your hotels are performing well and accepting bookings normally.</p>
            </div>
        </div>
    </div>

    <!-- 4️⃣ Operations Table -->
    <div class="card card--no-pad mt-4">
        <div class="card__header">
            <h3 class="card__title text-navy">Recent Bookings</h3>
            <a href="#" class="text-muted fw-bold" style="font-size: 0.75rem; text-decoration: none;">Full Record &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="u-table">
                <thead>
                    <tr>
                        <th>Hotel / Room Type</th>
                        <th>Reference</th>
                        <th>Check-In</th>
                        <th>Revenue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hotelBookings as $booking)
                        <tr>
                            <td>
                                <div class="fw-bold text-navy">{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}</div>
                                <div class="text-muted small mt-1">{{ $booking->hotelRoom?->room_type }}</div>
                            </td>
                            <td><span style="font-family: monospace; font-size: 0.85rem; color: var(--u-text-muted);">#{{ $booking->booking_reference }}</span></td>
                            <td><span class="fw-bold text-navy">{{ $booking->check_in->format('M d, Y') }}</span></td>
                            <td><span class="fw-bold text-navy">${{ number_format($booking->total_price, 0) }}</span></td>
                            <td>
                                <span class="badge {{ strtolower($booking->status) === 'confirmed' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Revenue Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('hotelRevenueChart').getContext('2d');
            const dataRow = {!! json_encode($charts['revenue']) !!};
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataRow.map(d => d.label),
                    datasets: [{
                        label: 'Earnings',
                        data: dataRow.map(d => d.revenue),
                        backgroundColor: '#0f172a',
                        borderRadius: 6,
                        maxBarThickness: 45
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
                            ticks: { color: '#94a3b8', font: { size: 10 }, callback: v => '$' + v.toLocaleString() } 
                        },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</x-dashboard-layout>
