<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <h2 class="admin-page-title">Hotel Bookings</h2>
            </div>
        </div>
    </x-slot>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search by reference/customer and filter by hotel or status</p>
            </div>
        </div>

        <form method="GET" action="{{ route('staff.hotel-bookings.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search reference or customer" />

            <select name="hotel_id" class="admin-input">
                <option value="">All hotels</option>
                @foreach ($hotels as $hotel)
                    <option value="{{ $hotel->id }}" @selected($selectedHotel == $hotel->id)>{{ $hotel->name }}</option>
                @endforeach
            </select>

            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['pending', 'confirmed', 'cancellation_requested', 'completed', 'cancelled'] as $option)
                    <option value="{{ $option }}" @selected($selectedStatus === $option)>{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                @endforeach
            </select>

            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Bookings</h3>
                <p>Latest hotel booking records</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Hotel</th>
                        <th>Customer</th>
                        <th>Stay</th>
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
                                <strong>{{ $booking->hotelRoom?->hotel?->name ?? 'Unknown Hotel' }}</strong>
                                <small>{{ $booking->hotelRoom?->room_type ?? 'Room' }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->user?->name ?? 'Unknown customer' }}</strong>
                                <small>{{ $booking->user?->email }}</small>
                            </td>
                            <td>{{ optional($booking->check_in)->format('M d') }} - {{ optional($booking->check_out)->format('M d, Y') }}</td>
                            <td>NPR {{ number_format($booking->total_price, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</td>
                            <td>
                                <a href="{{ route('staff.hotel-bookings.show', $booking) }}" class="admin-secondary-button">
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-table__empty">No hotel bookings found.</td>
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
