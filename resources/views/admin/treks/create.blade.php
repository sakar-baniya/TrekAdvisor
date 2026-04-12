<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split" style="align-items: flex-end;">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <a href="{{ route('admin.treks.index') }}" aria-label="Back to Treks" class="admin-back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="admin-eyebrow">Trek Management</p>
                    <h2 class="admin-page-title">Add New Trek</h2>
                    <p class="admin-page-subtitle">Fill details and click <b>Create Trek</b> to publish/save.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.treks.store') }}" method="POST" enctype="multipart/form-data" id="create-trek-form">
        @csrf
        @include('admin.treks._form')

        <!-- Sticky Action Bar -->
        <div class="sticky-action-bar">
            <div class="sticky-action-bar__inner">
                <a href="{{ route('admin.treks.index') }}" class="admin-secondary-button">Back to Treks</a>
                <button type="submit" class="admin-primary-button">Create Trek</button>
            </div>
        </div>
    </form>
</x-dashboard-layout>
