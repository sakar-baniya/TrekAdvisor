@props([
    'title' => '',
    'subtitle' => '',
])

<x-app-layout>
    <div class="bg-slate-50/50 min-h-screen">
        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn print:py-0 print:px-0">
            <!-- Header -->
            @if($title)
                <div class="mb-10 print:hidden">
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

