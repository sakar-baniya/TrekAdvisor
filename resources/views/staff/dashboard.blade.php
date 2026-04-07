<x-dashboard-layout>
    <!-- 1️⃣ Header -->
    <div class="page-header">
        <div class="page-header__content">
            <h1>Activity Console</h1>
            <p>Monitor platform activity and review pending requests.</p>
        </div>
        <div class="page-header__actions">
            @if($stats['pending_hotels'] > 0)
                <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="btn btn-warning">
                    <i class="fas fa-bell"></i> review {{ $stats['pending_hotels'] }} hotels
                </a>
            @endif
        </div>
    </div>

    <!-- 2️⃣ Stats -->
    <section class="stat-grid">
        <x-dashboard.stat-card 
            label="Today's Trek Bookings"
            value="{{ number_format($stats['today_trek_bookings']) }}"
            icon="fa-mountain-sun"
            trend="+5%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="Today's Hotel Bookings"
            value="{{ number_format($stats['today_hotel_bookings']) }}"
            icon="fa-hotel"
            trend="+2%"
            trendDirection="up"
        />
        
        <x-dashboard.stat-card 
            label="Pending Reviews"
            value="{{ number_format($stats['pending_hotels']) }}"
            icon="fa-clock"
        />

        <x-dashboard.stat-card 
            label="Support Tickets"
            value="12"
            icon="fa-headset"
            trend="-2"
            trendDirection="down"
        />
    </section>

    <!-- 3️⃣ Main Analytics -->
    <div class="dashboard-grid">
        <div class="card">
            <div class="card__header" style="margin-bottom: 1.5rem;">
                <div>
                    <h3 class="card__title text-navy">Activity Trend</h3>
                    <p class="text-muted mt-1">Platform booking volume over the last 7 days.</p>
                </div>
                <div class="badge badge-info">High Volume</div>
            </div>
            <div class="chart-container" style="height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card shadow-sm border-start border-4 border-navy" style="padding: 1.25rem;">
                <h3 class="card__title mb-3">Priority Tasks</h3>
                <div class="ops-item">
                    <div class="ops-item__icon"><i class="fas fa-ticket-alt"></i></div>
                    <div class="ops-item__content">
                        <h4 class="ops-item__title">Dispute #412</h4>
                        <p class="ops-item__desc">Double charge on Annapurna Trek.</p>
                    </div>
                </div>
                <div class="ops-item">
                    <div class="ops-item__icon"><i class="fas fa-user-plus"></i></div>
                    <div class="ops-item__content">
                        <h4 class="ops-item__title">Partner Support</h4>
                        <p class="ops-item__desc">Help Everest View Lodge with gallery setup.</p>
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 1.25rem;">
                <h3 class="card__title mb-1 text-navy">Guidelines</h3>
                <p class="text-muted mb-3" style="font-size: 0.75rem;">Platform operational standards.</p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="#" class="btn btn-secondary justify-content-start border-0" style="background: var(--u-bg); font-weight: 700; font-size: 0.8rem;">
                        <i class="fas fa-book text-navy"></i> Support Guide
                    </a>
                </div>
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
