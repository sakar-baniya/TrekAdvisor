<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#1a1e18] antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-[#f4f4f4]">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 text-[#1a1e18]">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#1a1e18] text-white font-black text-lg">TA</span>
                    <span class="text-xl font-black tracking-tight">TrekAdvisor</span>
                </a>
            </div>

            <div class="w-full max-w-md mt-8 px-6 py-6 bg-white border border-[#e2e2e2] shadow-sm overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
