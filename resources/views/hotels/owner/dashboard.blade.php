<x-layouts.dashboard>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-display">Dashboard</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Track requests, confirm stays, and manage your property inventory.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('hotel_owner.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                Bookings
            </a>
            <a href="{{ route('hotel_owner.hotels.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                Properties
            </a>
            <a href="{{ route('hotel_owner.hotels.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/10">
                <i class="fas fa-plus mr-2"></i> Add Hotel
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach([
            ['label' => 'Projected Revenue', 'value' => 'NPR ' . number_format($stats['revenue_this_month'] ?? 0, 0), 'meta' => 'Completed check-ins this month', 'icon' => 'fa-wallet', 'color' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Active Bookings', 'value' => number_format($stats['active_bookings'] ?? 0), 'meta' => 'Across all rooms', 'icon' => 'fa-calendar-check', 'color' => 'bg-blue-50 text-blue-600'],
            ['label' => 'Pending Requests', 'value' => number_format($stats['pending_requests'] ?? 0), 'meta' => 'Needs your confirmation', 'icon' => 'fa-hourglass-half', 'color' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Next 7 Days', 'value' => number_format($stats['upcoming_checkins'] ?? 0), 'meta' => 'Upcoming check-ins', 'icon' => 'fa-door-open', 'color' => 'bg-slate-900 text-white']
        ] as $stat)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl {{ $stat['color'] }} flex items-center justify-center text-lg mb-4">
                    <i class="fas {{ $stat['icon'] }}"></i>
                </div>
                <strong class="block text-2xl font-semibold text-slate-900 tracking-tight">{{ $stat['value'] }}</strong>
                <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-1">{{ $stat['label'] }}</span>
                <p class="text-xs font-semibold text-slate-400 opacity-60 line-clamp-1">{{ $stat['meta'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8 flex flex-col">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight font-display">Weekly Revenue</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Based on confirmed stays</p>
            </div>
            <div class="flex-grow h-[300px]">
                <canvas id="hotelRevenueChart"></canvas>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-8">
            <!-- Pipeline -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6">Booking Summary</h3>
                <div class="space-y-3">
                    @foreach($statusBreakdown ?? [] as $label => $val)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50/50">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white rounded-3xl border-l-[6px] border-slate-900 shadow-sm p-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 px-1">Properties Overview</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl">
                         <span class="block text-xl font-semibold text-slate-900">{{ $stats['hotels'] ?? 0 }}</span>
                         <span class="text-[10px] font-semibold text-slate-400 uppercase italic tracking-tighter">Properties</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                         <span class="block text-xl font-semibold text-slate-900">{{ $stats['rooms'] ?? 0 }}</span>
                         <span class="text-[10px] font-semibold text-slate-400 uppercase italic tracking-tighter">Room Types</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight font-display">Recent Activity</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Latest check-in requests across all hotels</p>
            </div>
            <a href="{{ route('hotel_owner.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-semibold uppercase tracking-widest text-slate-600 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                View All &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-400 border-b border-slate-50">
                        <th class="px-8 py-4 text-center">Reference</th>
                        <th class="px-8 py-4">Hotel / Room</th>
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Stay Dates</th>
                        <th class="px-8 py-4">Amount</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($hotelBookings ?? [] as $booking)
                        <tr class="group hover:bg-slate-50/50 transition-colors italic-hover">
                            <td class="px-8 py-5 text-center">
                                <span class="bg-slate-50 text-slate-400 text-[10px] font-bold px-2 py-1 rounded-lg">#{{ $booking->booking_reference }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-semibold text-slate-900 transition-colors uppercase tracking-tight">{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel' }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $booking->hotelRoom?->room_type ?? 'Standard Room' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-bold text-slate-900">{{ $booking->user?->name ?? 'Customer' }}</div>
                                <div class="text-[10px] font-medium text-slate-400 tracking-wider">{{ $booking->user?->email }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-xs font-semibold text-slate-600 uppercase tracking-tighter">
                                    {{ optional($booking->check_in)->format('M d') }} - {{ optional($booking->check_out)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-semibold text-slate-900 tracking-tight">NPR {{ number_format($booking->total_price, 0) }}</span>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $statusClass = match(strtolower($booking->status)) {
                                        'confirmed', 'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-slate-100 text-slate-500 border-slate-200'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-semibold uppercase tracking-widest border {{ $statusClass }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('hotel_owner.bookings.show', $booking) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center text-xs font-bold text-slate-400 italic">No booking requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('hotelRevenueChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const dataRow = {!! json_encode($charts['revenue'] ?? []) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataRow.map(item => item.label),
                    datasets: [{
                        label: 'Revenue',
                        data: dataRow.map(item => item.revenue),
                        backgroundColor: '#0f172a',
                        hoverBackgroundColor: '#1e293b',
                        borderRadius: 12,
                        borderSkipped: false,
                        maxBarThickness: 40
                    }],
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
                                callback: function (value) {
                                    return 'NPR ' + value.toLocaleString();
                                },
                            },
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } }
                        }
                    },
                },
            });
        });
    </script>
</x-layouts.dashboard>

