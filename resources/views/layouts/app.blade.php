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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-app">
        <div class="page-shell">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="page-header">
                    <div class="container header-inner">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="page-content">
                {{ $slot }}
            </main>
        </div>

        <footer class="site-footer">
            <div class="container section">
                <div class="footer-grid">
                    <div>
                        <div class="footer-brand">
                            <span class="footer-brand-badge">TA</span>
                            TrekAdvisor
                        </div>
                        <p class="footer-muted footer-text">
                            Your trusted platform for treks, hotels, and rental gear in Nepal.
                        </p>
                    </div>
                    <div>
                        <p class="footer-title">Quick Links</p>
                        <ul class="footer-muted footer-list">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('treks.index') }}">Treks</a></li>
                            <li><span>Hotels</span></li>
                            <li><span>Gear Rental</span></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-title">Services</p>
                        <ul class="footer-muted footer-list">
                            <li>Book Trek</li>
                            <li>Book Hotel</li>
                            <li>Rent Gear</li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-title">Contact</p>
                        <ul class="footer-muted footer-list">
                            <li>Kathmandu, Nepal</li>
                            <li>info@trekadvisor.com</li>
                            <li>+977 9800000000</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    &copy; {{ date('Y') }} TrekAdvisor. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>
