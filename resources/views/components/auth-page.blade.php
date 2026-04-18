@props([
    'title' => '',
    'heading' => '',
    'subheading' => '',
    'maxWidth' => 'md',
    'errorMessage' => 'Please fix the errors and try again.',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TrekAdvisor') }}{{ $title ? " - $title" : '' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased text-slate-900 bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full {{ $maxWidth === 'xl' ? 'max-w-xl' : 'max-w-md' }} animate-fadeIn">

        <!-- Logo + Brand -->
        <div class="flex flex-col items-center mb-10 cursor-default">
            <!-- Refined Logo Container -->
            <div class="relative mb-4">
                <!-- Outer Glow -->
                <div class="absolute inset-0 bg-slate-400 opacity-20 rounded-full blur-2xl"></div>
                
                <!-- The Logo (Circularized to eliminate square white backgrounds) -->
                <div class="relative w-28 h-28 rounded-full bg-white border-8 border-white shadow-2xl overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" 
                         class="w-full h-full object-contain" 
                         alt="TrekAdvisor Logo">
                </div>
            </div>

            <!-- Brand Name -->
            <div class="text-center">
                <h1 class="text-2xl font-black text-slate-900 tracking-tighter uppercase leading-none">TrekAdvisor</h1>
                <div class="flex justify-center mt-2">
                    <div class="h-1 w-8 bg-slate-900 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl shadow-slate-200/80 overflow-hidden">
            
            <div class="p-8 md:p-10">
                <!-- Header Text -->
                @if($heading)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ $heading }}</h2>
                        @if($subheading)
                            <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed">{{ $subheading }}</p>
                        @endif
                    </div>
                @endif

                <!-- Session Status Messages -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest leading-none mb-1">Status Update</p>
                        <p class="text-xs font-semibold text-emerald-600">{{ session('status') }}</p>
                    </div>
                @endif

                <!-- Validation Errors (Global Alert) -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <p class="text-[10px] font-black text-red-800 uppercase tracking-widest leading-none mb-1">Alert</p>
                        <p class="text-xs font-semibold text-red-600">{{ $errorMessage }}</p>
                    </div>
                @endif

                <!-- Form Content -->
                {{ $slot }}

                <!-- Optional Footer (Inside Card) -->
                @isset($footer)
                    <div class="mt-8 pt-8 border-t border-slate-50 text-center">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>

        <!-- Optional Back Links (Outside Card) -->
        @isset($back)
            <div class="text-center mt-8">
                {{ $back }}
            </div>
        @endisset
    </div>
</body>
</html>
