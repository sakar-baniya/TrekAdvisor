{{-- resources/views/components/dashboard/topbar.blade.php --}}

@props(['title', 'subtitle' => null])

<header class="dashboard-topbar" {{ $attributes }}>
    <div class="dashboard-topbar__left">
        <button type="button" class="dashboard-mobile-toggle" data-sidebar-toggle aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="dashboard-topbar__branding">
            <h1 class="dashboard-topbar__title">{{ $title }}</h1>
            @if($subtitle)
                <p class="dashboard-topbar__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="dashboard-topbar__right">
        <label class="dashboard-searchbar">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" placeholder="Search..." />
        </label>

        <button type="button" class="dashboard-topbar__icon-btn" aria-label="Notifications">
            <i class="fas fa-bell"></i>
        </button>

        <x-dashboard.profile-menu />
    </div>
</header>
