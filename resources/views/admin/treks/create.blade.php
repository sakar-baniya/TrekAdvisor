<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <a href="{{ route('admin.treks.index') }}" aria-label="Back to Treks" style="color: var(--text-muted); font-size: 1.25rem; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: white; border: 1px solid var(--border-light); text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="admin-eyebrow">Trek Management</p>
                    <h2 class="admin-page-title">Add New Trek</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.treks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.treks._form')
    </form>
</x-dashboard-layout>
