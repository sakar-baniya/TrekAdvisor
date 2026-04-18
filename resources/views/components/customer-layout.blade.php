@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumb' => [],
])

<x-app-layout>
    <div class="bg-slate-50/50 min-h-screen">
        <!-- Sub-Navigation (Tabs) -->
        <div class="bg-white border-b border-slate-200 sticky top-20 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-8 h-14 overflow-x-auto no-scrollbar">
                    @php
                        $tabs = [
                            ['label' => 'Dashboard', 'route' => 'customer.dashboard', 'icon' => 'fa-th-large'],
                            ['label' => 'My Bookings', 'route' => 'account.bookings.index', 'icon' => 'fa-clipboard-list'],
                            ['label' => 'Payments', 'route' => 'account.payments.index', 'icon' => 'fa-credit-card'],
                            ['label' => 'Settings', 'route' => 'settings.profile.show', 'icon' => 'fa-cog'],
                        ];
                    @endphp

                    @foreach ($tabs as $tab)
                        <a href="{{ route($tab['route']) }}" 
                           class="inline-flex items-center gap-2.5 h-full px-1 border-b-2 text-sm font-semibold transition-all whitespace-nowrap {{ request()->routeIs($tab['route'] . '*') ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-600 hover:border-slate-300' }}">
                            <i class="fas {{ $tab['icon'] }} text-[13px] {{ request()->routeIs($tab['route'] . '*') ? 'text-slate-900' : 'text-slate-400' }}"></i>
                            {{ $tab['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
            <!-- Breadcrumbs -->
            @if(!empty($breadcrumb))
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6" aria-label="Breadcrumb">
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                    @foreach($breadcrumb as $label => $link)
                        <span class="text-slate-300">/</span>
                        @if($link)
                            <a href="{{ $link }}" class="hover:text-slate-900 transition-colors">{{ $label }}</a>
                        @else
                            <span class="text-slate-900">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            <!-- Header -->
            @if($title)
                <div class="mb-10">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</x-app-layout>
