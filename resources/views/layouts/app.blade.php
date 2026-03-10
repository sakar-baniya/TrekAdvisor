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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f4f4f4] text-[#1a1e18]">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <footer class="bg-[#1a1e18] text-[#f4f4f4]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
                    <div>
                        <div class="flex items-center gap-2 text-white font-black">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-xs text-[#1a1e18]">TA</span>
                            TrekAdvisor
                        </div>
                        <p class="mt-3 text-xs text-[#cfcfcf]">
                            Your trusted platform for treks, hotels, and rental gear in Nepal.
                        </p>
                    </div>
                    <div>
                        <p class="text-white font-semibold mb-3">Quick Links</p>
                        <ul class="space-y-2 text-[#cfcfcf]">
                            <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                            <li><a href="{{ route('treks.index') }}" class="hover:text-white">Treks</a></li>
                            <li><span>Hotels</span></li>
                            <li><span>Gear Rental</span></li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-white font-semibold mb-3">Services</p>
                        <ul class="space-y-2 text-[#cfcfcf]">
                            <li>Book Trek</li>
                            <li>Book Hotel</li>
                            <li>Rent Gear</li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-white font-semibold mb-3">Contact</p>
                        <ul class="space-y-2 text-[#cfcfcf]">
                            <li>Kathmandu, Nepal</li>
                            <li>info@trekadvisor.com</li>
                            <li>+977 9800000000</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-white/10 pt-4 text-xs text-[#cfcfcf]">
                    &copy; {{ date('Y') }} TrekAdvisor. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>
