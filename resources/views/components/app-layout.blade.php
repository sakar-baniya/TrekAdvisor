<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const syncNavbarState = function () {
                    document.querySelectorAll('.site-nav').forEach(function (nav) {
                        nav.classList.toggle('is-scrolled', window.scrollY > 50);
                    });
                };

                syncNavbarState();
                window.addEventListener('scroll', syncNavbarState, { passive: true });

                document.querySelectorAll('[data-nav-shell]').forEach(function (shell) {
                    const toggle = shell.querySelector('[data-nav-toggle]');
                    const mobile = shell.querySelector('[data-nav-mobile]');

                    if (!toggle || !mobile) {
                        return;
                    }

                    const setOpen = function (isOpen) {
                        mobile.classList.toggle('open', isOpen);
                        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    };

                    toggle.addEventListener('click', function () {
                        setOpen(!mobile.classList.contains('open'));
                    });

                    document.addEventListener('click', function (event) {
                        if (!shell.contains(event.target)) {
                            setOpen(false);
                        }
                    });

                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            setOpen(false);
                        }
                    });
                });
            });
        </script>
    </head>
    <body class="body-app">
        <!-- Navigation Include for Public Pages -->
        @include('layouts.navigation')

        <!-- Page Heading Slot -->
        @isset($header)
            <header class="page-header">
                <div class="container header-inner">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content Slot -->
        <main>
            {{ $slot }}
        </main>

        <!-- Global Footer -->
        <footer class="page-footer">
            <div class="footer-container">
                <div class="footer-grid">
                    <!-- Brand Section -->
                    <div class="footer-section-brand">
                        <a href="{{ route('home') }}" class="footer-brand">
                            <span class="footer-brand-icon"><i class="fas fa-mountain-sun"></i></span>
                            <span>TrekAdvisor</span>
                        </a>
                        <p class="footer-description">
                            Your trusted companion for Himalayan trekking adventures. Expert-guided journeys with over 17 years of experience in mountain exploration.
                        </p>
                        <div class="footer-social">
                            <a href="https://facebook.com" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://instagram.com" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="https://twitter.com" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="https://wa.me/9779816681137" class="social-link" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="footer-section-title">Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                            <li><a href="{{ route('treks.index') }}" class="footer-link">Explore Treks</a></li>
                            <li><a href="{{ route('hotels.index') }}" class="footer-link">Hotels</a></li>
                            <li><a href="{{ route('gear.index') }}" class="footer-link">Gear Rental</a></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h4 class="footer-section-title">Company</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('about') }}" class="footer-link">About Us</a></li>
                            <li><a href="{{ route('blog') }}" class="footer-link">Travel Guide</a></li>
                            <li><a href="{{ route('contact') }}" class="footer-link">Contact Us</a></li>
                            <li><a href="#" class="footer-link">Careers</a></li>
                        </ul>
                    </div>

                    <!-- Contact & Newsletter -->
                    <div>
                        <h4 class="footer-section-title">Stay Updated</h4>
                        <form class="footer-newsletter" action="#" method="POST">
                            <div style="width: 100%;">
                                <input type="email" class="newsletter-input" placeholder="Your email" required />
                                <button type="submit" class="newsletter-submit" style="width: 100%; margin-top: 8px;">Subscribe</button>
                            </div>
                        </form>
                        <p style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.6); margin-top: 12px;">Get trek updates & travel tips</p>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <p class="footer-copyright">
                        &copy; {{ date('Y') }} TrekAdvisor. All rights reserved.
                    </p>
                    <div class="footer-legal">
                        <a href="#" class="footer-legal-link">Privacy Policy</a>
                        <a href="#" class="footer-legal-link">Terms of Service</a>
                        <a href="#" class="footer-legal-link">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>


