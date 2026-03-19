<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Gear Management</p>
                <h2 class="admin-page-title">Gear Rentals</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Status</h3>
                <p>Track active, pending, and returned rentals</p>
            </div>
        </div>

        <div class="admin-filter-tabs admin-filter-tabs--padded">
            <a href="{{ route('admin.gear-rentals.index') }}" class="admin-filter-tab {{ $status === '' ? 'is-active' : '' }}">All</a>
            @foreach (['Active', 'Pending', 'Returned', 'Cancelled'] as $option)
                <a href="{{ route('admin.gear-rentals.index', ['status' => $option]) }}" class="admin-filter-tab {{ $status === $option ? 'is-active' : '' }}">{{ $option }}</a>
            @endforeach
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Rental List</h3>
                <p>Mark items as returned to restore stock</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Item</th>
                        <th>Customer</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rentals as $rental)
                        <tr>
                            <td class="admin-table__ref">{{ $rental->rental_reference }}</td>
                            <td>
                                <strong>{{ $rental->gearItem?->name ?? 'Unknown item' }}</strong>
                                <small>{{ $rental->quantity }} item(s)</small>
                            </td>
                            <td>
                                <strong>{{ $rental->user?->name ?? 'Unknown customer' }}</strong>
                                <small>{{ $rental->user?->email }}</small>
                            </td>
                            <td>{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }} ({{ $rental->num_days }} days)</td>
                            <td>
                                <span class="admin-badge {{ $rental->status === 'Returned' ? 'is-success' : ($rental->status === 'Active' ? 'is-warning' : 'is-muted') }}">{{ $rental->status }}</span>
                            </td>
                            <td>
                                @if ($rental->status !== 'Returned')
                                    <form method="POST" action="{{ route('admin.gear-rentals.return', $rental) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="admin-secondary-button">
                                            <i class="fas fa-box-open"></i>
                                            <span>Return</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="admin-badge is-success">Done</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table__empty">No gear rentals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($rentals->hasPages())
        <div class="admin-pagination">{{ $rentals->links() }}</div>
    @endif
</x-dashboard-layout>
