<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Gear Management</p>
                <h2 class="admin-page-title">Edit Gear Item</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.gear.update', $gearItem) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.gear._form')
    </form>
</x-dashboard-layout>
