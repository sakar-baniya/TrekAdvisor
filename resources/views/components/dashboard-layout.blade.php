<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Dashboard</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-slate-900">
        <div class="flex min-h-screen">
            
            <!-- SIDEBAR: Role-Based Navigation -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
                <div class="h-16 flex items-center px-6 border-b border-slate-700">
                    <span class="text-xl font-black tracking-tighter">
                        TREK<span class="text-blue-400">ADVISOR</span> 
                        <span class="text-[10px] font-bold text-slate-400 ml-1 uppercase">{{ auth()->user()->role }}</span>
                    </span>
                </div>

                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <!-- Global/Shared Links -->
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('*.dashboard') ? 'bg-slate-800 text-white font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fas fa-th-large w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Specific Links -->
                        <a href="{{ route('admin.treks.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.treks.*') ? 'bg-slate-800 text-white font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-mountain w-5 mr-3"></i>
                            <span>Manage Treks</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-hotel w-5 mr-3"></i>
                            <span>Hotel Approvals</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-users w-5 mr-3"></i>
                            <span>Manage Users</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'staff')
                        <!-- Staff Specific Links -->
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-clipboard-list w-5 mr-3"></i>
                            <span>Check-in Logs</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'hotel_owner')
                        <!-- Hotel Owner Specific Links -->
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-bed w-5 mr-3"></i>
                            <span>My Rooms</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'customer')
                        <!-- Customer Specific Links -->
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-ticket-alt w-5 mr-3"></i>
                            <span>My Bookings</span>
                        </a>
                    @endif

                    <!-- Logout at bottom -->
                    <div class="pt-10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-rose-400 hover:bg-rose-500/10 transition-colors group">
                                <i class="fas fa-sign-out-alt w-5 mr-3 group-hover:translate-x-1 transition-transform"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

                <!-- Top Navigation Bar -->
                <header class="h-16 bg-white border-b border-gray-200 sticky top-0 z-30 flex items-center justify-between px-6">
                    <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-400 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="hidden md:flex items-center space-x-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black text-slate-400">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <!-- Page Header -->
                @if(isset($header))
                    <div class="bg-white border-b border-gray-100 px-8 py-4">
                        {{ $header }}
                    </div>
                @endif

                <!-- MAIN CONTENT -->
                <main class="p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- OVERLAY & JS -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/50 hidden md:hidden" onclick="toggleSidebar()"></div>
        
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        </script>
    </body>
</html>
