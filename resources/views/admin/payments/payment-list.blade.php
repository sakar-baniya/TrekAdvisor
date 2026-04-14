<x-dashboard-layout>
    <style>
        .payment-status-pill {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .payment-status-pill.is-success {
            background: #dcfce7;
            color: #166534;
        }

        .payment-status-pill.is-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-status-pill.is-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .payment-status-pill.is-neutral {
            background: #e5e7eb;
            color: #374151;
        }
    </style>

    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <h2 class="admin-page-title">All Payments</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search by transaction or customer, then narrow by type and date</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.payments.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search transaction or customer" />
            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['Success', 'Pending', 'Failed'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="type" class="admin-input">
                <option value="">All types</option>
                @foreach (['trek' => 'Trek', 'hotel' => 'Hotel'] as $value => $label)
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
                <p>Payment records across trek and hotel bookings</p>
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
                            @php
                                // Keep list compact: show short id in table, full id in title tooltip.
                                $fullTransactionId = (string) $payment->transaction_id;
                                $shortTransactionId = strlen($fullTransactionId) > 16
                                    ? substr($fullTransactionId, 0, 8) . '...' . substr($fullTransactionId, -4)
                                    : $fullTransactionId;
                            @endphp
                            <td class="admin-table__ref" title="{{ $fullTransactionId }}">{{ $shortTransactionId }}</td>
                            <td>{{ ucfirst($payment->payable_type) }}</td>
                            <td>
                                <strong>{{ $payment->user?->name ?? 'Unknown customer' }}</strong>
                                <small>{{ $payment->user?->email }}</small>
                            </td>
                            <td>NPR {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->gateway ? ucfirst($payment->gateway) : 'N/A' }}</td>
                            <td>
                                @php
                                    // Map status text to a simple color class for faster scanning.
                                    $statusValue = strtolower((string) $payment->status);
                                    $statusClass = match (true) {
                                        in_array($statusValue, ['success', 'completed', 'paid'], true) => 'is-success',
                                        in_array($statusValue, ['pending', 'processing'], true) => 'is-pending',
                                        in_array($statusValue, ['failed', 'cancelled', 'canceled'], true) => 'is-failed',
                                        default => 'is-neutral',
                                    };
                                @endphp
                                <span class="payment-status-pill {{ $statusClass }}">{{ $payment->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="admin-secondary-button">
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

