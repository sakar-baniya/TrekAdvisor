<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Admin Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    @php
        use App\Services\Dashboard\DashboardNavigation;

        $user = auth()->user();
        $role = $user->role;
        $navConfig = DashboardNavigation::getConfig($role);
        $navigation = DashboardNavigation::getNormalizedNavigation($navConfig['navigation']);
    @endphp

    <body class="h-full text-slate-800 font-sans antialiased overflow-hidden" x-data="{ sidebarOpen: false }">
        <div class="flex h-full bg-slate-50">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col transition-transform duration-300 lg:static lg:translate-x-0 lg:flex-shrink-0">
                <!-- Branding -->
                <div class="flex items-center justify-between h-20 px-6 border-b border-slate-800">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-white font-bold text-xl tracking-tight hover:text-slate-200 transition-colors">
                        <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" class="w-8 h-8 rounded-full bg-slate-800 p-1" alt="TrekAdvisor logo" />
                        TrekAdvisor
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                    @foreach ($navigation as $item)
                        @if (isset($item['children']))
                            <div class="space-y-1" x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                                <button type="button" @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-semibold rounded-lg transition-colors {{ $item['active'] ? 'text-white bg-slate-800/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/30' }}">
                                    <span>{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-collapse class="pl-4 pr-2 space-y-1 mt-1">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ route($child['route']) }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ $child['active'] ? 'text-white font-semibold bg-emerald-500/10 text-emerald-400' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }}">
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route($item['route']) }}" class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all {{ $item['active'] ? 'bg-slate-800 text-white shadow-sm font-semibold' : 'text-slate-300 font-semibold hover:text-white hover:bg-slate-800/30' }}">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <header class="flex items-center justify-between h-20 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200 shadow-sm z-30">
                    <div class="flex items-center flex-1 gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <form class="hidden sm:flex w-full max-w-md relative" action="{{ route('search') }}" method="GET">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="search" name="q" value="{{ request('q') }}" class="w-full bg-slate-50 border border-slate-200 rounded-full pl-10 pr-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all" placeholder="Search parameters, treks, or users..." aria-label="Search">
                        </form>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="button" class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors rounded-full hover:bg-slate-100">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                        </button>
                        
                        <div class="hidden sm:block">
                            <!-- User Profile Component -->
                            <x-dashboard.profile-menu :user="$user" />
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
                    <div class="max-w-7xl mx-auto">
                        @if(isset($header))
                            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                {{ $header }}
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Confirmation Modal Component -->
        <x-confirmation-modal />
        <script src="{{ asset('js/modules/confirmation-modal.js') }}"></script>
    </body>
</html>
