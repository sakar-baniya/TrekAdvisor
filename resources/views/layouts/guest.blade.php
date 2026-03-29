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
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-guest body-auth-page">
        @php
            $authVisual = match (true) {
                request()->routeIs('register') => [
                    'eyebrow' => 'Adventure starts here',
                    'title' => 'Book treks, stays, and gear from one beautiful basecamp.',
                    'body' => 'Create your TrekAdvisor account to plan Himalayan journeys, compare options, and keep every booking in one place.',
                ],
                request()->routeIs('password.request') => [
                    'eyebrow' => 'Account recovery',
                    'title' => 'Get back to your TrekAdvisor plans without the stress.',
                    'body' => 'We will help you reset access so you can return to upcoming departures, hotel bookings, and rental plans.',
                ],
                request()->routeIs('admin.login') => [
                    'eyebrow' => 'Operations access',
                    'title' => 'Manage TrekAdvisor from a calmer, clearer admin sign-in.',
                    'body' => 'Review listings, bookings, and approvals with a dedicated dashboard built for platform operations.',
                ],
                default => [
                    'eyebrow' => 'Welcome back',
                    'title' => 'Return to your Himalayan plans right where you left them.',
                    'body' => 'Sign in to review bookings, confirm departures, manage stays, and keep your next adventure moving smoothly.',
                ],
            };
        @endphp

        <div class="guest-shell guest-shell--auth">
            <div class="guest-auth-stage">
                <div class="guest-auth-shell">
                    <aside class="auth-visual-panel">
                        <a href="{{ route('home') }}" class="auth-back-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to website</span>
                        </a>

                        <div class="auth-visual-panel__media">
                            <div class="auth-visual-panel__overlay"></div>
                            <div class="auth-visual-panel__copy">
                                <p class="auth-visual-panel__eyebrow">{{ $authVisual['eyebrow'] }}</p>
                                <h2>{{ $authVisual['title'] }}</h2>
                                <p>{{ $authVisual['body'] }}</p>
                            </div>
                            <div class="auth-visual-panel__dots" aria-hidden="true">
                                <span class="is-active"></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </aside>

                    <section class="auth-form-panel">
                        <div class="auth-card auth-card--centered">
                            {{ $slot }}
                        </div>

                        <p class="auth-note auth-note--centered">
                            By continuing, you agree to TrekAdvisor terms and privacy policy.
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const syncNavbarState = () => {
                document.querySelectorAll('.site-nav').forEach((nav) => {
                    nav.classList.toggle('is-scrolled', window.scrollY > 50);
                });
            };

            syncNavbarState();
            window.addEventListener('scroll', syncNavbarState, { passive: true });

            document.querySelectorAll('[data-nav-shell]').forEach((shell) => {
                const toggle = shell.querySelector('[data-nav-toggle]');
                const mobile = shell.querySelector('[data-nav-mobile]');

                if (!toggle || !mobile) {
                    return;
                }

                const setOpen = (isOpen) => {
                    mobile.classList.toggle('open', isOpen);
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                };

                toggle.addEventListener('click', () => {
                    setOpen(!mobile.classList.contains('open'));
                });

                document.addEventListener('click', (event) => {
                    if (!shell.contains(event.target)) {
                        setOpen(false);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setOpen(false);
                    }
                });
            });

            document.querySelectorAll('[data-toggle-password]').forEach((button) => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                const showIcon = button.querySelector('[data-icon="show"]');
                const hideIcon = button.querySelector('[data-icon="hide"]');

                button.addEventListener('click', () => {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    if (showIcon && hideIcon) {
                        showIcon.classList.toggle('is-hidden', isPassword);
                        hideIcon.classList.toggle('is-hidden', !isPassword);
                    }
                });
            });
        });
    </script>
</html>
