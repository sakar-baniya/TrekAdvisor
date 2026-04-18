<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Admin Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-white font-display font-bold text-xl tracking-tight hover:text-slate-200 transition-colors">
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
                                                <button type="button" @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-sm font-display font-bold rounded-xl transition-all {{ $item['active'] ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                                    <span>{{ $item['label'] }}</span>
                                                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                                </button>
                                                <div x-show="open" x-collapse class="pl-4 pr-2 space-y-1 mt-1">
                                                    @foreach ($item['children'] as $child)
                                                        <a href="{{ route($child['route']) }}" class="block px-4 py-2 text-xs font-display rounded-xl transition-all {{ $child['active'] ? 'bg-white/10 text-white font-bold shadow-sm' : 'text-slate-500 font-semibold hover:text-white hover:bg-white/5' }}">
                                                            {{ $child['label'] }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                        @else
                             <a href="{{ route($item['route']) }}" class="flex items-center px-4 py-3 text-sm font-display font-bold rounded-xl transition-all {{ $item['active'] ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                 {{ $item['label'] }}
                             </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <header class="flex items-center justify-between h-20 px-6 sm:px-8 bg-white border-b border-slate-200 z-30">
                    <div class="flex items-center flex-1 gap-6">
                        <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-500 hover:text-slate-900 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Page Title & Subtitle -->
                        <div class="hidden sm:flex items-center gap-4">
                            @hasSection('page-back')
                                <a href="@yield('page-back')" class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-900 hover:text-white transition-all border border-slate-200">
                                    <i class="fas fa-arrow-left text-xs"></i>
                                </a>
                            @endif
                            <div>
                                <h1 class="text-xl font-display font-black text-slate-900 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                                 @hasSection('page-subtitle')
                                     <p class="text-[10px] font-display font-black text-slate-400 uppercase tracking-widest mt-0.5">@yield('page-subtitle')</p>
                                 @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-4 font-sans text-right">
                        <!-- Navigation-Specific Actions (Export, New, etc.) -->
                        @hasSection('page-actions')
                            <div class="flex items-center gap-2 mr-2">
                                @yield('page-actions')
                            </div>
                        @endif
                        
                        <div class="h-8 w-px bg-slate-100 mx-1 hidden md:block"></div>

                        <!-- User Profile Component -->
                        <x-dashboard.profile-menu :user="$user" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-slate-50/50">
                    <div class="max-w-[1600px] mx-auto px-6 py-6">
                        @if(isset($header))
                            <div class="mb-6">
                                {{ $header }}
                            </div>
                        @endif

                        <div class="animate-fadeIn">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Confirmation Modal Component -->
        <x-ui.confirmation-modal />
        <script>
            /**
             * Global Confirmation System (Alpine-backed)
             */
            window.showConfirm = function (options) {
                window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                    detail: options
                }));
            };

            // Global handling for [data-confirm] triggers
            document.addEventListener('click', function (event) {
                var trigger = event.target.closest('[data-confirm]');
                if (!trigger) return;

                // For type="submit" in a form, we handle it in the submit listener
                if (trigger.type === 'submit' && trigger.closest('form')) {
                    return;
                }

                event.preventDefault();
                var action = trigger.getAttribute('data-confirm');
                var form = trigger.closest('form');

                window.showConfirm({
                    preset: (action && action !== 'true') ? action : null,
                    title: trigger.getAttribute('data-title'),
                    message: trigger.getAttribute('data-message'),
                    form: form
                });
            }, true);

            // Global handling for forms containing [data-confirm]
            document.addEventListener('submit', function (event) {
                var form = event.target;
                var confirmTrigger = form.querySelector('[data-confirm]');
                
                if (!confirmTrigger || confirmTrigger.type !== 'submit') {
                    return;
                }

                event.preventDefault();
                var action = confirmTrigger.getAttribute('data-confirm');
                
                window.showConfirm({
                    preset: (action && action !== 'true') ? action : null,
                    title: confirmTrigger.getAttribute('data-title'),
                    message: confirmTrigger.getAttribute('data-message'),
                    callback: () => form.submit()
                });
            }, true);
        </script>
    </body>
</html>

