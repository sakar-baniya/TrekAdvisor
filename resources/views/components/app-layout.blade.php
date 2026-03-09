<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-slate-900">
        <!-- Navigation Include for Public Pages -->
        @include('layouts.navigation')

        <!-- Page Heading Slot -->
        @isset($header)
            <header class="bg-gray-50 border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content Slot -->
        <main>
            {{ $slot }}
        </main>

        <!-- Global Footer -->
        <footer class="bg-white border-t border-gray-100 py-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">
                    &copy; {{ date('Y') }} TrekAdvisor - Final Year Project
                </p>
            </div>
        </footer>
    </body>
</html>
