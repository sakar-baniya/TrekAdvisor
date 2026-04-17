<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Staff Console</h1>
            <p class="text-slate-500 font-medium">Monitoring trek booking activity and operational requests.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('staff.trek-bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
                <i class="fas fa-mountain-sun mr-2"></i> Manage Treks
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach([
            ['label' => "Today's Trek Bookings", 'value' => number_format($stats['today_trek_bookings'] ?? 0), 'icon' => 'fa-mountain-sun', 'color' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Pending Bookings', 'value' => number_format($stats['pending_trek_bookings'] ?? 0), 'icon' => 'fa-clock', 'color' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Cancellation Alerts', 'value' => number_format($stats['cancellation_requests'] ?? 0), 'icon' => 'fa-triangle-exclamation', 'color' => 'bg-red-50 text-red-600']
        ] as $stat)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl {{ $stat['color'] }} flex items-center justify-center text-xl shrink-0">
                    <i class="fas {{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <strong class="block text-2xl font-black text-slate-900 tracking-tight">{{ $stat['value'] }}</strong>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Booking Activity Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Booking Activity</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 mb-8">Daily Volume (Last 7 Days)</p>
            </div>
            <div class="h-[300px]">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Recent Table -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Recent Orders</h3>
            </div>
            
            <div class="flex-grow overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($recentTrekBookings ?? [] as $booking)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3 mb-1">
                                         <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">#{{ $booking->booking_reference }}</span>
                                         @php
                                            $statusClass = $booking->status === 'confirmed' ? 'bg-emerald-500' : ($booking->status === 'pending' ? 'bg-amber-500' : 'bg-slate-200');
                                         @endphp
                                         <span class="w-1.5 h-1.5 rounded-full {{ $statusClass }}"></span>
                                    </div>
                                    <div class="text-sm font-black text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                        {{ $booking->departure?->trek?->title ?? 'Trek' }}
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-400 truncate uppercase mt-0.5">
                                        {{ $booking->user?->name }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-8 py-12 text-center text-xs font-bold text-slate-400 italic">No activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-slate-50/50 border-t border-slate-50">
                <a href="{{ route('staff.trek-bookings.index') }}" class="inline-flex justify-center items-center w-full py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-black rounded-xl hover:bg-slate-900 hover:text-white transition-all uppercase tracking-widest shadow-sm">
                    All Bookings &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxActivity = document.getElementById('activityChart').getContext('2d');
            const dataSet = {!! json_encode($charts['activity'] ?? []) !!};

            new Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: dataSet.map(d => d.label),
                    datasets: [{
                        label: 'Volume',
                        data: dataSet.map(d => d.count),
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
                            ticks: { 
                                color: '#94a3b8', 
                                font: { weight: 'bold', size: 10 },
                                precision: 0
                            } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } } 
                        }
                    }
                }
            });
        });
    </script>
</x-dashboard-layout>
