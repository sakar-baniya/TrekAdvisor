<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Payments</p>
                <h2 class="admin-page-title">All Payments</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-green"><i class="fas fa-wallet"></i></div>
            <div>
                <p>Total Amount</p>
                <h3>${{ number_format($totalAmount, 2) }}</h3>
                <span>Filtered records</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search by transaction or customer, then narrow by type and date</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.payments.index') }}" class="admin-filter-grid admin-filter-grid--payments">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search transaction or customer" />
            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['Success', 'Pending', 'Failed'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="type" class="admin-input">
                <option value="">All types</option>
                @foreach (['trek' => 'Trek', 'hotel' => 'Hotel', 'gear' => 'Gear'] as $value => $label)
                    <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ $from }}" class="admin-input" />
            <input type="date" name="to" value="{{ $to }}" class="admin-input" />
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Transactions</h3>
                <p>Payment records across trek, hotel, and gear bookings</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Txn ID</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="admin-table__ref">{{ $payment->transaction_id }}</td>
                            <td>{{ ucfirst($payment->payable_type) }}</td>
                            <td>
                                <strong>{{ $payment->user?->name ?? 'Unknown customer' }}</strong>
                                <small>{{ $payment->user?->email }}</small>
                            </td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->gateway ? ucfirst($payment->gateway) : 'N/A' }}</td>
                            <td>
                                <span class="admin-badge {{ $payment->status === 'Success' ? 'is-success' : ($payment->status === 'Pending' ? 'is-warning' : 'is-muted') }}">{{ $payment->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="admin-secondary-button">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($payments->hasPages())
        <div class="admin-pagination">{{ $payments->links() }}</div>
    @endif
</x-dashboard-layout>

