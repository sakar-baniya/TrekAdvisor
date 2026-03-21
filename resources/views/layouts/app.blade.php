<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-app">
        <div class="page-shell">
            @include('layouts.navigation')

            @isset($header)
                <header class="page-header">
                    <div class="container header-inner">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="page-content">
                {{ $slot }}
            </main>
        </div>

        <footer class="site-footer">
            <div class="container section">
                <div class="footer-grid">
                    <div>
                        <div class="footer-brand">
                            <span class="footer-brand-badge"><i class="fas fa-mountain"></i></span>
                            TrekAdvisor
                        </div>
                        <p class="footer-muted footer-text">
                            Trek the Himalayas, plan stays, and rent gear from one beautiful marketplace.
                        </p>
                    </div>
                    <div>
                        <p class="footer-title">Quick Links</p>
                        <ul class="footer-muted footer-list">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('treks.index') }}">Treks</a></li>
                            <li><a href="{{ route('home') }}#featured-hotels">Hotels</a></li>
                            <li><a href="{{ route('home') }}#featured-gear">Gear Rental</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-title">Services</p>
                        <ul class="footer-muted footer-list">
                            <li>Book Trek</li>
                            <li>Book Hotel</li>
                            <li>Rent Gear</li>
                            <li>Travel Support</li>
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
