<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=4">
        <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}?v=1">
    </head>
        @php
            use App\Services\Dashboard\DashboardNavigation;

            $user = auth()->user();
            $role = $user->role;

            // Get navigation configuration from centralized service
            $navConfig = DashboardNavigation::getConfig($role);
            
            // Convert routes in navigation to actual URLs and add active states
            $navigation = array_map(function ($item) {
                if (isset($item['children'])) {
                    $item['children'] = array_map(function ($child) {
                        $child['route'] = route($child['route']);
                        $child['active'] = DashboardNavigation::isRouteActive(explode('/', str_replace(url(''), '', $child['route']))[-1] ?? $child['route']);
                        return $child;
                    }, $item['children']);
                    $item['active'] = collect($item['children'])->contains(fn ($c) => $c['active']);
                } else {
                    $item['route'] = route($item['route']);
                    $item['active'] = DashboardNavigation::isRouteActive(explode('/', str_replace(url(''), '', $item['route']))[-1] ?? $item['route']);
                }
                return $item;
            }, $navConfig['navigation']);

            $dashboardConfig = [
                'title' => $navConfig['title'],
                'subtitle' => $navConfig['subtitle'],
                'panel_label' => $navConfig['panel_label'],
                'home_route' => route($navConfig['home_route']),
                'navigation' => $navigation,
            ];
        @endphp

    <body class="admin-body admin-body--{{ str_replace('_', '-', $role) }}">

        <div class="admin-shell" data-admin-shell>
            <aside class="admin-sidebar" id="admin-sidebar">
                <div class="admin-sidebar__brand">
                    <a href="{{ $dashboardConfig['home_route'] }}" class="admin-brandmark">
                        <span class="admin-brandmark__badge">TA</span>
                        <span>
                            <strong>TrekAdvisor</strong>
                            <small>{{ $dashboardConfig['panel_label'] }}</small>
                        </span>
                    </a>
                </div>

                <nav class="admin-sidebar__nav">
                    @foreach ($dashboardConfig['navigation'] as $item)
                        @if (isset($item['children']))
                            <section class="admin-nav-group {{ $item['active'] ? 'is-open' : '' }}" data-nav-group>
                                <button type="button" class="admin-nav-group__toggle" data-nav-toggle aria-expanded="{{ $item['active'] ? 'true' : 'false' }}">
                                    <i class="fas {{ $item['icon'] }}"></i>
                                    <span>{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down admin-nav-group__chevron"></i>
                                </button>

                                <div class="admin-nav-group__menu">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ $child['route'] }}" class="admin-nav-link {{ $child['active'] ? 'is-active' : '' }}">
                                            <span>{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @else
                            <a href="{{ $item['route'] }}" class="admin-nav-link admin-nav-link--root {{ $item['active'] ? 'is-active' : '' }}">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach

                    <a href="{{ route('home') }}" class="admin-nav-link admin-nav-link--root">
                        <i class="fas fa-compass"></i>
                        <span>Back to Site</span>
                    </a>
                </nav>

                <div class="admin-sidebar__footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-ghost-button admin-ghost-button--full">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="admin-content">
                <header class="dashboard-topbar">
                    <button type="button" class="admin-icon-button admin-mobile-toggle" data-sidebar-toggle>
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="dashboard-topbar__header">
                        <h1 class="dashboard-topbar__title">{{ $dashboardConfig['title'] }}</h1>
                        @if ($dashboardConfig['subtitle'])
                            <p class="dashboard-topbar__subtitle">{{ $dashboardConfig['subtitle'] }}</p>
                        @endif
                    </div>

                    <div class="dashboard-topbar__controls">
                        <button type="button" class="dashboard-topbar__search-btn" data-search-toggle>
                            <i class="fas fa-magnifying-glass"></i>
                        </button>

                        <div class="dashboard-topbar__search" data-search-bar>
                            <input type="search" class="dashboard-topbar__search-input" placeholder="Search...">
                            <i class="fas fa-magnifying-glass dashboard-topbar__search-icon"></i>
                        </div>

                        <button type="button" class="dashboard-topbar__notifications-btn">
                            <i class="fas fa-bell"></i>
                            <span class="dashboard-topbar__notifications-badge">3</span>
                        </button>

                        <x-dashboard.profile-menu :user="auth()->user()" />
                    </div>
                </header>

                @if (isset($header))
                    <div class="admin-page-header">
                        {{ $header }}
                    </div>
                @endif

                <main class="admin-main">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <button type="button" class="admin-overlay" id="admin-overlay" data-sidebar-toggle aria-label="Close sidebar"></button>

        <script>
            (() => {
                const shell = document.querySelector('[data-admin-shell]');
                const toggles = document.querySelectorAll('[data-sidebar-toggle]');
                const navToggles = document.querySelectorAll('[data-nav-toggle]');

                toggles.forEach((toggle) => {
                    toggle.addEventListener('click', () => shell.classList.toggle('sidebar-open'));
                });

                navToggles.forEach((toggle) => {
                    toggle.addEventListener('click', () => {
                        const group = toggle.closest('[data-nav-group]');
                        const isOpen = group.classList.toggle('is-open');
                        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    });
                });

                // Search toggle functionality
                const searchToggleBtn = document.querySelector('[data-search-toggle]');
                const searchBar = document.querySelector('[data-search-bar]');

                if (searchToggleBtn && searchBar) {
                    searchToggleBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        searchBar.classList.toggle('visible');
                        if (searchBar.classList.contains('visible')) {
                            searchBar.querySelector('input')?.focus();
                        }
                    });

                    document.addEventListener('click', () => {
                        searchBar.classList.remove('visible');
                    });

                    searchBar.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }
            })();
        </script>
    </body>
</html>

