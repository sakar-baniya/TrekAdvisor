<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Management</p>
                <h2 class="admin-page-title">Add New Trek</h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.treks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.treks._form')
    </form>
</x-dashboard-layout>
