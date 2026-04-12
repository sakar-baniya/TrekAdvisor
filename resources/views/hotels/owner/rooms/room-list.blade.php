<x-dashboard-layout>
    <div class="admin-page-heading">
        <div>
            <h2 class="admin-page-title">Rooms for {{ $hotel->name }}</h2>
            <p class="admin-eyebrow">Hotel Owner</p>
        </div>
        <a href="{{ route('hotel_owner.hotels.rooms.create', $hotel) }}" class="admin-primary-button">Add Room</a>
    </div>

    <section class="admin-panel">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Price/Night</th>
                        <th>Total Rooms</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->room_type }}</td>
                            <td>NPR {{ number_format($room->price_per_night, 2) }}</td>
                            <td>{{ $room->total_rooms }}</td>
                            <td>
                                <a href="{{ route('hotel_owner.hotels.rooms.edit', [$hotel, $room]) }}" class="admin-secondary-button">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No rooms added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">{{ $rooms->links() }}</div>
    </section>
</x-dashboard-layout>
