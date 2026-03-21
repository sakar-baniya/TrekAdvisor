<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="admin-body">
        @php
            $adminNavigation = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-chart-line',
                    'route' => route('admin.dashboard'),
                    'active' => request()->routeIs('admin.dashboard'),
                ],
                [
                    'label' => 'Trek Management',
                    'icon' => 'fa-mountain-sun',
                    'children' => [
                        ['label' => 'All Treks', 'route' => route('admin.treks.index'), 'active' => request()->routeIs('admin.treks.index')],
                        ['label' => 'Add New Trek', 'route' => route('admin.treks.create'), 'active' => request()->routeIs('admin.treks.create')],
                        ['label' => 'Departures', 'route' => route('admin.departures.index'), 'active' => request()->routeIs('admin.departures.*')],
                        ['label' => 'Trek Bookings', 'route' => route('admin.trek-bookings.index'), 'active' => request()->routeIs('admin.trek-bookings.*')],
                    ],
                ],
                [
                    'label' => 'Hotel Management',
                    'icon' => 'fa-hotel',
                    'children' => [
                        ['label' => 'All Hotels', 'route' => route('admin.hotels.index'), 'active' => request()->routeIs('admin.hotels.*')],
                    ],
                ],
                [
                    'label' => 'Gear Management',
                    'icon' => 'fa-backpack',
                    'children' => [
                        ['label' => 'All Gear Items', 'route' => route('admin.gear.index'), 'active' => request()->routeIs('admin.gear.*')],
                        ['label' => 'Add Gear Item', 'route' => route('admin.gear.create'), 'active' => request()->routeIs('admin.gear.create')],
                        ['label' => 'Gear Rentals', 'route' => route('admin.gear-rentals.index'), 'active' => request()->routeIs('admin.gear-rentals.*')],
                    ],
                ],
                [
                    'label' => 'User Management',
                    'icon' => 'fa-users',
                    'children' => [
                        ['label' => 'All Users', 'route' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
                        ['label' => 'Add Staff', 'route' => route('admin.users.create-staff'), 'active' => request()->routeIs('admin.users.create-staff')],
                    ],
                ],
                [
                    'label' => 'Payments',
                    'icon' => 'fa-credit-card',
                    'children' => [
                        ['label' => 'All Payments', 'route' => route('admin.payments.index'), 'active' => request()->routeIs('admin.payments.*')],
                    ],
                ],
                [
                    'label' => 'Reviews',
                    'icon' => 'fa-star',
                    'children' => [
                        ['label' => 'All Reviews', 'route' => route('admin.reviews.index'), 'active' => request()->routeIs('admin.reviews.index')],
                        ['label' => 'Flagged Reviews', 'route' => route('admin.reviews.flagged'), 'active' => request()->routeIs('admin.reviews.flagged')],
                    ],
                ],
            ];
        @endphp

        <div class="admin-shell" data-admin-shell>
            <aside class="admin-sidebar" id="admin-sidebar">
                <div class="admin-sidebar__brand">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brandmark">
                        <span class="admin-brandmark__badge">TA</span>
                        <span>
                            <strong>TrekAdvisor</strong>
                            <small>Admin Panel</small>
                        </span>
                    </a>
                </div>

                <nav class="admin-sidebar__nav">
                    @foreach ($adminNavigation as $item)
                        @if (isset($item['children']))
                            @php
                                $groupIsActive = collect($item['children'])->contains(fn ($child) => $child['active']);
                            @endphp
                            <section class="admin-nav-group {{ $groupIsActive ? 'is-open' : '' }}" data-nav-group>
                                <button type="button" class="admin-nav-group__toggle" data-nav-toggle aria-expanded="{{ $groupIsActive ? 'true' : 'false' }}">
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
                </nav>

                <div class="admin-sidebar__footer">
                    <div class="admin-user-card">
                        <div class="admin-user-card__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ str_replace('_', ' ', auth()->user()->role) }}</small>
                        </div>
                    </div>

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
                <header class="admin-topbar">
                    <div class="admin-topbar__left">
                        <button type="button" class="admin-icon-button admin-mobile-toggle" data-sidebar-toggle>
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <p class="admin-eyebrow">Operations center</p>
                            <h1 class="admin-topbar__title">TrekAdvisor Admin</h1>
                        </div>
                    </div>

                    <div class="admin-topbar__right">
                        <label class="admin-searchbar">
                            <i class="fas fa-magnifying-glass"></i>
                            <input type="search" placeholder="Search pages, bookings, users" />
                        </label>
                        <button type="button" class="admin-icon-button">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="admin-profile-chip">
                            <div class="admin-profile-chip__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ now()->format('M d, Y') }}</small>
                            </div>
                        </div>
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
            })();
        </script>
    </body>
</html>
