<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Gear Management</p>
                <h2 class="admin-page-title">Add Gear Item</h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.gear.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.gear._form')
    </form>
</x-dashboard-layout>
