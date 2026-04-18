<x-layouts.dashboard>
    <div class="admin-page-heading">
        <div>
            <h2 class="admin-page-title">Add Room</h2>
            <p class="admin-eyebrow">{{ $hotel->name }}</p>
        </div>
    </div>

    <section class="admin-panel">
        <form method="POST" action="{{ route('hotel_owner.hotels.rooms.store', $hotel) }}" class="admin-form-grid admin-form-grid--two">
            @csrf
            <label class="admin-field">
                <span>Room Type</span>
                <input class="admin-input" type="text" name="room_type" value="{{ old('room_type') }}" required>
            </label>
            <label class="admin-field">
                <span>Price Per Night</span>
                <input class="admin-input" type="number" min="0" step="0.01" name="price_per_night" value="{{ old('price_per_night') }}" required>
            </label>
            <label class="admin-field">
                <span>Total Rooms</span>
                <input class="admin-input" type="number" min="1" name="total_rooms" value="{{ old('total_rooms', 1) }}" required>
            </label>

            <div class="admin-form-actions" style="grid-column: 1 / -1;">
                <a href="{{ route('hotel_owner.hotels.rooms.index', $hotel) }}" class="admin-secondary-button">Cancel</a>
                <button type="submit" class="admin-primary-button">Save Room</button>
            </div>
        </form>
    </section>
</x-layouts.dashboard>

