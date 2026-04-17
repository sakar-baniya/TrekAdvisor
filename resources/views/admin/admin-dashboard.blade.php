<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Admin Dashboard</h1>
            <p class="text-slate-500 font-medium">Real-time overview of the TrekAdvisor marketplace.</p>
        </div>
        <div class="flex gap-3">
            <button class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
            <a href="{{ route('admin.treks.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
                <i class="fas fa-plus mr-2"></i> New Trek
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach([
            ['label' => 'Monthly Revenue', 'value' => 'NPR ' . number_format($stats['revenue_this_month'] ?? 0, 0), 'meta' => number_format($stats['bookings_this_month'] ?? 0) . ' bookings', 'icon' => 'fa-wallet', 'color' => 'bg-emerald-50 text-emerald-600', 'trend' => '+12%'],
            ['label' => 'Total Users', 'value' => number_format($stats['total_users'] ?? 0), 'meta' => 'Registered customers', 'icon' => 'fa-users', 'color' => 'bg-blue-50 text-blue-600', 'trend' => '+8%'],
            ['label' => 'Active Hotels', 'value' => number_format($stats['active_hotels'] ?? 0), 'meta' => ($stats['pending_hotels'] ?? 0) . ' pending review', 'icon' => 'fa-hotel', 'color' => 'bg-amber-50 text-amber-600', 'trend' => '+5%'],
            ['label' => 'Live Treks', 'value' => number_format($stats['active_treks'] ?? 0), 'meta' => 'Marketplace inventory', 'icon' => 'fa-mountain-sun', 'color' => 'bg-slate-900 text-white', 'trend' => '+3%']
        ] as $stat)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl {{ $stat['color'] }} flex items-center justify-center text-lg">
                        <i class="fas {{ $stat['icon'] }}"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <strong class="block text-2xl font-black text-slate-900 tracking-tight">{{ $stat['value'] }}</strong>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $stat['label'] }}</span>
                <p class="text-xs font-semibold text-slate-400 opacity-60">{{ $stat['meta'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Main Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Revenue Trend</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Last 6 Months Growth</p>
                </div>
                <select class="bg-slate-50 border-none text-xs font-black uppercase tracking-widest rounded-xl px-4 py-2 text-slate-600">
                    <option>Year 2024</option>
                    <option>Year 2023</option>
                </select>
            </div>
            <div class="h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Booking Status Distribution -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h3 class="text-lg font-black text-slate-900 tracking-tight mb-8">Booking Status</h3>
            <div class="h-[180px] relative mb-8">
                <canvas id="statusChart"></canvas>
            </div>
            
            <div class="space-y-3 mt-auto">
                @foreach($charts['status_distribution'] ?? [] as $status => $count)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full {{ match(strtolower($status)) { 'success', 'confirmed', 'active' => 'bg-emerald-500', 'pending' => 'bg-amber-500', 'failed', 'cancelled' => 'bg-red-500', default => 'bg-slate-300' } }}"></span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $status }}</span>
                        </div>
                        <span class="text-sm font-black text-slate-900">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Secondary Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity Table -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Recent Activity</h3>
                <a href="#" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 border-b border-slate-50">
                            <th class="px-8 py-4">Activity / Reference</th>
                            <th class="px-8 py-4">Amount</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentBookings ?? [] as $booking)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="text-sm font-black text-slate-900 mb-1 group-hover:text-emerald-600 transition-colors">{{ $booking->title }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span>#{{ $booking->reference }}</span>
                                        <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                        <span>{{ $booking->customer }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-slate-900 tracking-tight">NPR {{ number_format($booking->amount, 0) }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $statusClass = match(strtolower($booking->status)) {
                                            'success', 'confirmed', 'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admin Tools & Alerts -->
        <div class="space-y-8">
            <!-- Pending Requests Widget -->
            <div class="bg-white rounded-3xl border-l-[6px] border-amber-400 shadow-sm p-8 group">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Pending Approval</h3>
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black">{{ $stats['pending_hotels'] ?? 0 }}</span>
                </div>
                <div class="flex items-start gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0 group-hover:bg-amber-400 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 mb-2 uppercase tracking-tight">Hotel Review Queue</h4>
                        <p class="text-xs font-semibold text-slate-500 leading-relaxed mb-6">Manage new partners waiting for identity and property approval.</p>
                        <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="inline-flex items-center justify-center w-full py-3 bg-slate-100 text-slate-600 text-[10px] font-black rounded-xl hover:bg-slate-900 hover:text-white transition-all uppercase tracking-widest">
                            Review Queue
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admin Tools -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-black text-slate-900 tracking-tight mb-2">Platform Tools</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">Access system-level settings</p>
                
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-100 hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-slate-400 group-hover:bg-slate-900 group-hover:text-white flex items-center justify-center transition-all">
                            <i class="fas fa-users-cog text-sm"></i>
                        </div>
                        <span class="text-xs font-black text-slate-600 uppercase tracking-widest group-hover:text-slate-900 transition-colors">User Access</span>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-100 hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-slate-400 group-hover:bg-slate-900 group-hover:text-white flex items-center justify-center transition-all">
                            <i class="fas fa-receipt text-sm"></i>
                        </div>
                        <span class="text-xs font-black text-slate-600 uppercase tracking-widest group-hover:text-slate-900 transition-colors">Finance Logs</span>
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
            const dataSet = {!! json_encode($charts['revenue'] ?? []) !!};
            
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: dataSet.map(d => d.month),
                    datasets: [{
                        label: 'Earnings',
                        data: dataSet.map(d => d.revenue),
                        borderColor: '#0f172a',
                        backgroundColor: 'rgba(15, 23, 42, 0.03)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 3,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 }, callback: v => 'NPR ' + v.toLocaleString() }
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } } 
                        }
                    }
                }
            });

            // Donut Chart Setup
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const sData = {!! json_encode($charts['status_distribution'] ?? []) !!};
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(sData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{
                        data: Object.values(sData),
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#94A3B8'],
                        borderWidth: 6,
                        borderColor: '#fff',
                        hoverOffset: 20,
                        borderRadius: 10
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
