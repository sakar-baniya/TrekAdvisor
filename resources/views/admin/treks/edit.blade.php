<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split" style="align-items: flex-end;">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <a href="{{ route('admin.treks.index') }}" aria-label="Back to Treks" class="admin-back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="admin-eyebrow">Trek Management</p>
                    <h2 class="admin-page-title">Edit Trek: <span class="text-navy">{{ $trek->title }}</span></h2>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <a href="{{ route('admin.treks.show', $trek) }}" class="admin-secondary-button">
                    <i class="fas fa-eye"></i>
                    <span>Preview</span>
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.treks.update', $trek) }}" method="POST" enctype="multipart/form-data" id="edit-trek-form">
        @csrf
        @method('PUT')
        @include('admin.treks._form')

        <!-- Sticky Action Bar -->
        <div class="sticky-action-bar">
            <div class="sticky-action-bar__inner">
                <a href="{{ route('admin.treks.index') }}" class="admin-secondary-button">Cancel</a>
                <button type="button" class="admin-primary-button" data-confirm="update-trek">Save Changes</button>
            </div>
        </div>
    </form>
</x-dashboard-layout>
