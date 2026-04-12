<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Hotel Listings</p>
                <h2 class="admin-page-title">Add New Hotel</h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('hotel_owner.hotels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
            @include('hotels.owner.hotel-form-fields')
    </form>
</x-dashboard-layout>
