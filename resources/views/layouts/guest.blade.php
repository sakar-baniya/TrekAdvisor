<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="text-[#1a1e18] antialiased" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen relative overflow-hidden bg-[#f4f4f4]">
            <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-emerald-300/40 blur-3xl"></div>
            <div class="absolute top-1/3 -right-24 h-72 w-72 rounded-full bg-amber-200/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-slate-200/60 blur-3xl"></div>

            <div class="relative z-10">
                @include('layouts.navigation')
            </div>

            <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div class="hidden lg:block"></div>

                    <div class="w-full">
                        <div class="lg:hidden mb-8">
                            <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 text-[#1a1e18]">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1a1e18] text-white font-black text-lg">TA</span>
                                <span class="text-2xl font-black tracking-tight">TrekAdvisor</span>
                            </a>
                        </div>

                        <div class="w-full max-w-md mx-auto px-6 py-8 sm:px-8 sm:py-10 bg-white/90 border border-white/70 shadow-xl overflow-hidden rounded-3xl backdrop-blur">
                            {{ $slot }}
                        </div>

                        <p class="mt-6 text-xs text-slate-500 text-center">
                            By continuing, you agree to TrekAdvisor terms and privacy policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
