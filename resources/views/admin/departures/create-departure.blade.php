<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Trek Operations</p>
                <h2 class="admin-page-title">Add Departure</h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.departures.store') }}" method="POST">
        @csrf
            @include('admin.departures.departure-form-fields')
    </form>
</x-dashboard-layout>
