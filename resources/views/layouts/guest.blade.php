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
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-guest">
        @include('layouts.navigation')

        <div class="guest-shell guest-shell--auth">
            <div class="guest-auth-stage">
                <div class="guest-auth-shell">
                    <div class="auth-card auth-card--centered">
                        {{ $slot }}
                    </div>

                    <p class="auth-note auth-note--centered">
                        By continuing, you agree to TrekAdvisor terms and privacy policy.
                    </p>
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


