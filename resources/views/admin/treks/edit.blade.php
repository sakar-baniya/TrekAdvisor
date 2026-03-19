<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Management</p>
                <h2 class="admin-page-title">Edit Trek</h2>
            </div>
            <a href="{{ route('admin.treks.show', $trek) }}" class="admin-secondary-button">
                <i class="fas fa-eye"></i>
                <span>Preview Trek</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.treks.update', $trek) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.treks._form')
    </form>
</x-dashboard-layout>
