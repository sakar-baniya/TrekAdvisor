<x-dashboard-layout>
    <!-- 1️⃣ Header Area -->
    <!-- Reduced header for dashboard: no hero/banner, just compact actions -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;gap:1.5rem;">
        <h2 class="fw-bold text-navy mb-0" style="font-size:1.35rem;">Dashboard Overview</h2>
        <div style="display:flex;gap:0.75rem;">
            <button class="u-btn u-btn--secondary">
                <i class="fas fa-file-export"></i> Export Report
            </button>
            <a href="{{ route('admin.treks.create') }}" class="u-btn u-btn--primary">
                <i class="fas fa-plus"></i> New Trek
            </a>
        </div>
    </div>

    <!-- 2️⃣ Stats Grid -->
    <section class="stat-grid">
        <x-dashboard.stat-card 
            label="Monthly Revenue"
            value="${{ number_format($stats['revenue_this_month'], 0) }}"
            meta="{{ number_format($stats['bookings_this_month']) }} bookings"
            icon="fa-wallet"
            trend="+12%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="Total Customers"
            value="{{ number_format($stats['total_users']) }}"
            icon="fa-users"
            trend="+8%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="Active Hotels"
            value="{{ number_format($stats['active_hotels']) }}"
            meta="{{ number_format($stats['pending_hotels']) }} pending"
            icon="fa-hotel"
            trend="+5%"
            trendDirection="up"
        />

        <x-dashboard.stat-card 
            label="Live Treks"
            value="{{ number_format($stats['active_treks']) }}"
            icon="fa-mountain-sun"
            trend="+3%"
            trendDirection="up"
        />
    </section>

    <!-- 3️⃣ Main Analytics -->
    <div class="dashboard-grid">
        <!-- Revenue Trend Chart -->
        <div class="card">
            <div class="card__header" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 class="card__title text-navy">Revenue Trend</h3>
                    <p class="text-muted mt-1">Growth overview over the last 6 months.</p>
                </div>
            </div>
            <div class="chart-container" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Booking Status Distribution -->
        <div class="card">
            <div class="card__header" style="margin-bottom: 1.5rem;">
                <h3 class="card__title text-navy">Booking Status</h3>
            </div>
            <div class="chart-container" style="height: 180px; position:relative; margin-bottom: 2rem;">
                <canvas id="statusChart"></canvas>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($charts['status_distribution'] as $status => $count)
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.85rem; padding: 0.65rem; background: var(--u-bg); border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background: {{ match(strtolower($status)) { 'success', 'confirmed', 'active' => '#10B981', 'pending' => '#F59E0B', 'failed', 'cancelled' => '#EF4444', default => '#94A3B8' } }};"></span>
                            <span class="text-muted fw-semibold">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="fw-bold text-navy">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4️⃣ Secondary Grid -->
    <div class="dashboard-grid mt-4" style="grid-template-columns: 1.6fr 1fr;">
        <!-- Recent Activity Table -->
        <div class="card card--no-pad">
            <div class="card__header">
                <h3 class="card__title">Recent Activity</h3>
                <a href="#" class="text-muted fw-bold" style="font-size: 0.75rem; text-decoration: none;">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="u-table">
                    <thead>
                        <tr>
                            <th>Activity / Reference</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold text-navy">{{ $booking->title }}</div>
                                    <div class="text-muted small mt-1">#{{ $booking->reference }} &bull; {{ $booking->customer }}</div>
                                </td>
                                <td><span class="fw-bold text-navy">${{ number_format($booking->amount, 0) }}</span></td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($booking->status)) {
                                            'success', 'confirmed', 'active' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            default => 'badge-info'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $booking->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Requests & Quick Actions -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Pending Requests Widget -->
            <div class="card" style="border-left: 4px solid var(--u-warning); padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem;">
                    <h3 class="card__title">Pending Requests</h3>
                    <span class="badge badge-warning">{{ $stats['pending_hotels'] }}</span>
                </div>
                <div class="ops-item">
                    <div class="ops-item__icon"><i class="fas fa-hotel"></i></div>
                    <div class="ops-item__content">
                        <h4 class="ops-item__title">Hotel Reviews</h4>
                        <p class="ops-item__desc">Manage {{ $stats['pending_hotels'] }} new partners waiting for approval.</p>
                        <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="btn card-cta mt-3 p-2 d-block text-center" style="font-size: 0.8rem;">Review Queue</a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card" style="padding: 1.25rem;">
                <h3 class="card__title mb-1">Admin Tools</h3>
                <p class="text-muted mb-3" style="font-size: 0.75rem;">Manage platform users and settings.</p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary justify-content-start border-0" style="background: var(--u-bg); font-weight: 700; font-size: 0.8rem;">
                        <i class="fas fa-users-cog text-navy"></i> User Permissions
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary justify-content-start border-0" style="background: var(--u-bg); font-weight: 700; font-size: 0.8rem;">
                        <i class="fas fa-receipt text-navy"></i> Payment Issues
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Area Chart Setup
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            const dataSet = {!! json_encode($charts['revenue']) !!};
            
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: dataSet.map(d => d.month),
                    datasets: [{
                        label: 'Earnings',
                        data: dataSet.map(d => d.revenue),
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
                            ticks: { color: '#94a3b8', font: { size: 11 }, callback: v => '$' + v.toLocaleString() } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 11 } } 
                        }
                    }
                }
            });

            // Donut Chart Setup
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const sData = {!! json_encode($charts['status_distribution']) !!};
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(sData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{
                        data: Object.values(sData),
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#94A3B8'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-dashboard-layout>
