<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Hotel Listings</p>
                <h2 class="admin-page-title">Manage Your Hotels</h2>
            </div>
            <a href="{{ route('hotel_owner.hotels.create') }}" class="admin-primary-button">
                <i class="fas fa-plus"></i>
                <span>Add Hotel</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-note-stack">
        @forelse ($hotels as $hotel)
            <article class="admin-note-card">
                <strong>{{ $hotel->name }}</strong>
                <span>{{ $hotel->location }} | {{ $hotel->rooms_count }} room types | {{ $hotel->gallery_count }} gallery photos | {{ $hotel->status }}</span>
                <a href="{{ route('hotel_owner.hotels.edit', $hotel) }}" class="admin-link-button">Edit hotel</a>
            </article>
        @empty
            <div class="admin-panel">
                <p class="admin-table__empty">No hotels have been added yet. Create one to start uploading photos.</p>
            </div>
        @endforelse
    </section>
</x-dashboard-layout>
