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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50 min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        @php
            $authVisual = match (true) {
                request()->routeIs('register') => [
                    'eyebrow' => 'Adventure starts here',
                    'title' => 'Book treks and stays from one beautiful basecamp.',
                    'body' => 'Create your TrekAdvisor account to plan Himalayan journeys, compare options, and keep every booking in one place.',
                ],
                request()->routeIs('password.request') => [
                    'eyebrow' => 'Account recovery',
                    'title' => 'Get back to your TrekAdvisor plans without the stress.',
                    'body' => 'We will help you reset access so you can return to upcoming departures and hotel bookings.',
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

        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 flex flex-col md:flex-row">
            <!-- Header section for the auth cards -->
            <div class="px-8 py-10 bg-slate-900 border-r border-slate-800 relative overflow-hidden md:w-5/12 flex flex-col justify-center">
                <!-- Abstract pattern overlay (optional styling touch) -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                
                <a href="{{ route('home') }}" class="inline-flex justify-start items-center mb-10 relative z-10 transition-transform hover:scale-105">
                    <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" class="w-14 h-14 rounded-full bg-slate-800 p-1 shadow-lg border-2 border-slate-700 hover:border-slate-500 transition-colors" alt="TrekAdvisor logo" />
                </a>
                
                <div class="relative z-10">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">{{ $authVisual['eyebrow'] }}</p>
                    <h2 class="text-2xl font-bold text-white mb-4 leading-tight tracking-tight">{{ $authVisual['title'] }}</h2>
                    <p class="text-sm text-slate-300 leading-relaxed">{{ $authVisual['body'] }}</p>
                </div>
            </div>

            <!-- Form body section -->
            <div class="px-8 py-10 bg-white md:w-7/12 flex items-center">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Updated Alpine.js is now favored for most toggle things, but for 
            // password visibility, leaving this fast Vanilla query selector:
            document.querySelectorAll('[data-toggle-password]').forEach((button) => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                button.setAttribute('aria-controls', targetId);
                button.setAttribute('aria-pressed', 'false');
                button.setAttribute('aria-label', 'Show password');

                const showIcon = button.querySelector('[data-icon="show"]');
                const hideIcon = button.querySelector('[data-icon="hide"]');

                button.addEventListener('click', () => {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    button.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                    button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    if (showIcon && hideIcon) {
                        showIcon.classList.toggle('is-hidden', isPassword);
                        hideIcon.classList.toggle('is-hidden', !isPassword);
                    }
                });
            });

            // Handle Laravel flash error messages for auth gently fading out
            document.querySelectorAll('.form-error').forEach((errorBlock) => {
                const message = errorBlock.textContent.replace(/\s+/g, ' ').trim();
                if (message === 'These credentials do not match our records.') {
                    setTimeout(() => {
                        errorBlock.style.transition = 'opacity 0.5s ease';
                        errorBlock.style.opacity = '0';
                        setTimeout(() => errorBlock.style.display = 'none', 500);
                    }, 4000);
                }
            });
        });
    </script>
</html>
