<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Hotel Listings</p>
                <h2 class="admin-page-title">Edit Hotel</h2>
            </div>
            <a href="{{ route('hotels.show', $hotel) }}" class="admin-secondary-button">
                <i class="fas fa-eye"></i>
                <span>View Public Page</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('hotel_owner.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('hotels.owner._form')
    </form>
</x-dashboard-layout>
