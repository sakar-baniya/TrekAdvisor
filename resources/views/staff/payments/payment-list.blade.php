<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <h2 class="admin-page-title">Payments Queue</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Pending and failed payments that need follow-up</p>
            </div>
        </div>

        <form method="GET" action="{{ route('staff.payments.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search transaction or customer" />
            <select name="status" class="admin-input">
                <option value="">Pending + Failed</option>
                <option value="pending" @selected($status === 'pending')>Pending</option>
                <option value="failed" @selected($status === 'failed')>Failed</option>
            </select>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Queue</h3>
                <p>Transactions needing attention</p>
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
                            <td>{{ $payment->currency ?? 'NPR' }} {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->gateway ? ucfirst($payment->gateway) : 'N/A' }}</td>
                            <td>{{ ucfirst(strtolower($payment->status)) }}</td>
                            <td>
                                <a href="{{ route('staff.payments.show', $payment) }}" class="admin-secondary-button">
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No queued payments found.</td>
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
