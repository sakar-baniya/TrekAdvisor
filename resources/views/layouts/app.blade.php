<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-app">
        <div class="page-shell">
            <header class="site-header">
                <div class="top-bar">
                    <div class="container top-bar__inner">
                        <div class="top-bar__center">
                            <i class="fas fa-bell"></i>
                            <span>Check Our Risk-Free Booking Policy <a href="#">View Details &raquo;</a></span>
                        </div>
                        <div class="top-bar__right">
                            <div class="top-bar__item">
                                <img src="https://flagcdn.com/w20/np.png" alt="Nepal" style="width: 16px; margin-right: 5px;">
                                <span>Nepal</span>
                                <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.navigation')
            </header>

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
                            <li><a href="{{ route('hotels.index') }}">Hotels</a></li>
                            <li><a href="{{ route('gear.index') }}">Gear Rental</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-title">Services</p>
                        <ul class="footer-muted footer-list">
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            <li><a href="{{ route('faq') }}">FAQ</a></li>
                            <li><a href="{{ route('blog') }}">Blog</a></li>
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
