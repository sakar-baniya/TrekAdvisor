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

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-guest">
        <div class="guest-shell">
            <div class="guest-bg"></div>
            <div class="guest-orb guest-orb-teal"></div>
            <div class="guest-orb guest-orb-amber"></div>
            <div class="guest-orb guest-orb-emerald"></div>

            <div class="guest-nav">
                @include('layouts.navigation')
            </div>

            <div class="guest-content container-wide">
                <div class="guest-grid">
                    <div class="guest-spacer"></div>

                    <div class="guest-card-wrap">
                        <div class="guest-brand">
                            <a href="{{ route('home') }}" class="guest-brand-link">
                                <span class="guest-brand-badge">TA</span>
                                <span class="guest-brand-text">TrekAdvisor</span>
                            </a>
                        </div>

                        <div class="auth-card">
                            {{ $slot }}
                        </div>

                        <p class="auth-note">
                            By continuing, you agree to TrekAdvisor terms and privacy policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

