<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Operations</p>
                <h2 class="admin-page-title">Edit Departure</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.departures.update', $departure) }}" method="POST">
        @csrf
        @method('PUT')
            @include('admin.departures.departure-form-fields')
    </form>
</x-dashboard-layout>
