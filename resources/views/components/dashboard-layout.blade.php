<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Admin Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=11">
        <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}?v=11">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    @php
        use App\Services\Dashboard\DashboardNavigation;

        $user = auth()->user();
        $role = $user->role;
        $navConfig = DashboardNavigation::getConfig($role);
        $navigation = DashboardNavigation::getNormalizedNavigation($navConfig['navigation']);
    @endphp

    <body class="admin-body">
        <div class="admin-shell">
            <!-- Sidebar: Premium SaaS Design -->
            <aside class="admin-sidebar" id="adminSidebar">
                <div class="admin-sidebar__brand">
                    <a href="{{ route('home') }}" class="admin-brandmark">
                        <div class="admin-brandmark__badge">TA</div>
                        <strong>TrekAdvisor</strong>
                    </a>
                </div>

                <nav class="admin-sidebar__nav">
                    @foreach ($navigation as $item)
                        @if (isset($item['children']))
                            <div class="admin-nav-group {{ $item['active'] ? 'is-open' : '' }}">
                                <button type="button" class="admin-nav-group__toggle" onclick="this.parentElement.classList.toggle('is-open')">
                                    <i class="fas {{ $item['icon'] }}"></i>
                                    <span>{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7rem; opacity: 0.3;"></i>
                                </button>
                                <div class="admin-nav-group__menu">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ route($child['route']) }}" class="admin-nav-link {{ $child['active'] ? 'is-active' : '' }}">
                                            <span>{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route($item['route']) }}" class="admin-nav-link {{ $item['active'] ? 'is-active' : '' }}">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>

                <div class="admin-sidebar__footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-footer-btn">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content: Refined Hierarchy -->
            <div class="admin-content">
                <header class="dashboard-topbar">
                    <button type="button" class="d-lg-none me-3" style="background:none; border:none; font-size: 1.25rem;" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="dashboard-topbar__search">
                        <i class="fas fa-search dashboard-topbar__search-icon" style="opacity: 0.4;"></i>
                        <input type="search" class="dashboard-topbar__search-input" placeholder="Search parameters, treks, or users...">
                    </div>

                    <div class="dashboard-topbar__actions">
                        <button type="button" class="u-btn u-btn--secondary" style="width: 42px; height: 42px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; position: relative;">
                            <i class="fas fa-bell"></i>
                            <span style="position: absolute; top: 10px; right: 10px; width: 8px; height: 8px; background: var(--u-danger); border: 1.5px solid white; border-radius: 50%;"></span>
                        </button>
                        
                        <x-dashboard.profile-menu :user="$user" />
                    </div>
                </header>

                <main class="admin-main" style="padding: 2.5rem; flex: 1;">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            // Sidebar Mobile Toggle
            document.addEventListener('click', (e) => {
                const sidebar = document.getElementById('adminSidebar');
                const trigger = e.target.closest('.d-lg-none');
                if (trigger) return;
                
                if (window.innerWidth < 1024 && sidebar.classList.contains('open') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });

            // Enhanced Profile Dropdown
            document.addEventListener('click', (e) => {
                const trigger = document.getElementById('profileMenuTrigger');
                const dropdown = document.getElementById('profileDropdown');
                if (trigger && dropdown) {
                    if (trigger.contains(e.target)) {
                        dropdown.classList.toggle('show');
                    } else if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                }
            });
        </script>
        
        <style>
            @media (max-width: 1024px) {
                .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
                .admin-sidebar.open { transform: translateX(0); }
                .admin-content { margin-left: 0; }
            }
        </style>
    </body>
</html>
