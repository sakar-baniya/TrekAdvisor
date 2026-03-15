<x-dashboard-layout>
    <x-slot name="header">
        <div>
            <h2 class="dashboard-header-title">Admin Dashboard</h2>
            <p class="dashboard-header-subtitle">Here’s what’s happening today.</p>
        </div>
    </x-slot>

    <!-- Stat Cards Grid -->
    <div class="stat-grid">
        <!-- Total Treks -->
        <div class="card card-hover">
            <div class="card-body stat-card">
                <div class="stat-meta">
                    <div class="icon-circle icon-gradient-teal">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div>
                        <p class="stat-title">Total Treks</p>
                        <h3 class="stat-value">{{ \App\Models\Trek::count() }}</h3>
                    </div>
                </div>
                <span class="badge badge-info">Active</span>
            </div>
        </div>

        <!-- Hotel Approvals -->
        <div class="card card-hover">
            <div class="card-body stat-card">
                <div class="stat-meta">
                    <div class="icon-circle icon-gradient-emerald">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div>
                        <p class="stat-title">Owner Approvals</p>
                        <h3 class="stat-value">{{ \App\Models\User::where('role', 'hotel_owner')->where('is_approved', false)->count() }}</h3>
                    </div>
                </div>
                <span class="badge badge-warning">Pending</span>
            </div>
        </div>

        <!-- Today's Activity -->
        <div class="card card-hover">
            <div class="card-body stat-card">
                <div class="stat-meta">
                    <div class="icon-circle icon-gradient-amber">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="stat-title">Today's Bookings</p>
                        <h3 class="stat-value">{{ \App\Models\Payment::whereDate('created_at', today())->where('status', 'Success')->count() }}</h3>
                    </div>
                </div>
                <span class="badge badge-warning">Live</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="card card-hover">
            <div class="card-body stat-card">
                <div class="stat-meta">
                    <div class="icon-circle icon-gradient-teal">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <p class="stat-title">Total Revenue</p>
                        <h3 class="stat-value">${{ number_format(\App\Models\Payment::where('status', 'Success')->sum('amount'), 2) }}</h3>
                    </div>
                </div>
                <span class="badge badge-info">Growth</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="stat-title">Recent Activity</h4>
            <span class="table-caption">Latest 5 Bookings</span>
        </div>
        <div class="recent-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer Name</th>
                        <th>Paid Amount</th>
                        <th>Status</th>
                        <th class="table-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Payment::latest()->take(5)->get() as $payment)
                        <tr>
                            <td>
                                <span class="table-label">#{{ substr($payment->transaction_id, -8) }}</span>
                            </td>
                            <td>
                                <span class="table-text">{{ $payment->user->name }}</span>
                            </td>
                            <td class="table-amount">
                                ${{ number_format($payment->amount / 100, 2) }}
                            </td>
                            <td>
                                <span class="status-pill {{ $payment->status === 'Success' ? 'status-success' : 'status-muted' }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="table-right table-date">
                                {{ $payment->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">
                                No activity found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
