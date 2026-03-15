<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Dashboard</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-dashboard">
        <div class="dashboard-shell">
            
            <!-- SIDEBAR: Role-Based Navigation -->
            <aside id="sidebar" class="dashboard-sidebar">
                <div class="sidebar-header">
                    <span class="sidebar-title">
                        Trek<span class="brand-accent">Advisor</span>
                        <span class="role-pill">{{ auth()->user()->role }}</span>
                    </span>
                </div>

                <nav class="dashboard-nav">
                    <!-- Global/Shared Links -->
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="dashboard-link {{ request()->routeIs('*.dashboard') ? 'is-active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Specific Links -->
                        <a href="{{ route('admin.treks.index') }}" class="dashboard-link {{ request()->routeIs('admin.treks.*') ? 'is-active' : '' }}">
                            <i class="fas fa-mountain"></i>
                            <span>Manage Treks</span>
                        </a>
                        <a href="{{ route('admin.hotels.index') }}" class="dashboard-link {{ request()->routeIs('admin.hotels.*') ? 'is-active' : '' }}">
                            <i class="fas fa-hotel"></i>
                            <span>Hotel Approvals</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="dashboard-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'staff')
                        <!-- Staff Specific Links -->
                        <a href="#" class="dashboard-link">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Check-in Logs</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'hotel_owner')
                        <!-- Hotel Owner Specific Links -->
                        <a href="#" class="dashboard-link">
                            <i class="fas fa-bed"></i>
                            <span>My Rooms</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'customer')
                        <!-- Customer Specific Links -->
                        <a href="#" class="dashboard-link">
                            <i class="fas fa-ticket-alt"></i>
                            <span>My Bookings</span>
                        </a>
                    @endif

                    <!-- Logout at bottom -->
                    <div class="logout-wrap">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dashboard-link logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <div class="dashboard-content">
                <!-- Top Navigation Bar -->
                <header class="dashboard-topbar">
                    <button onclick="toggleSidebar()" class="dashboard-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="dashboard-date">
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>

                    <div class="dashboard-user">
                        <span>{{ auth()->user()->name }}</span>
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <!-- Page Header -->
                @if(isset($header))
                    <div class="dashboard-header">
                        {{ $header }}
                    </div>
                @endif

                <!-- MAIN CONTENT -->
                <main class="dashboard-main">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- OVERLAY & JS -->
        <div id="sidebar-overlay" class="dashboard-overlay" onclick="toggleSidebar()"></div>
        
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
        </script>
    </body>
</html>
