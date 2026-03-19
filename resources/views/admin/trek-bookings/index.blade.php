<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Trek Operations</p>
                <h2 class="admin-page-title">Trek Bookings</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search by reference or customer, and filter by trek or status</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.trek-bookings.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search reference or customer" />

            <select name="trek_id" class="admin-input">
                <option value="">All treks</option>
                @foreach ($treks as $trek)
                    <option value="{{ $trek->id }}" @selected($selectedTrek == $trek->id)>{{ $trek->title }}</option>
                @endforeach
            </select>

            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['Pending', 'Confirmed', 'Cancelled'] as $option)
                    <option value="{{ $option }}" @selected($selectedStatus === $option)>{{ $option }}</option>
                @endforeach
            </select>

            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Bookings</h3>
                <p>Latest trek booking records</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Trek</th>
                        <th>Customer</th>
                        <th>Pax</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="admin-table__ref">{{ $booking->booking_reference }}</td>
                            <td>
                                <strong>{{ $booking->departure?->trek?->title ?? 'Unknown Trek' }}</strong>
                                <small>{{ optional($booking->departure?->start_date)->format('M d, Y') }} departure</small>
                            </td>
                            <td>
                                <strong>{{ $booking->user?->name ?? 'Unknown customer' }}</strong>
                                <small>{{ $booking->user?->email }}</small>
                            </td>
                            <td>{{ $booking->total_passengers }}</td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="admin-badge {{ $booking->status === 'Confirmed' ? 'is-success' : ($booking->status === 'Pending' ? 'is-warning' : 'is-muted') }}">{{ $booking->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.trek-bookings.show', $booking) }}" class="admin-secondary-button">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No trek bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($bookings->hasPages())
        <div class="admin-pagination">{{ $bookings->links() }}</div>
    @endif
</x-dashboard-layout>
