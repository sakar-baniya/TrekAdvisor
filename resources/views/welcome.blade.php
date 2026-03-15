<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'TrekAdvisor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles / Scripts -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="body-app">
        @include('layouts.navigation')

        <main class="welcome-hero">
            <div class="container">
                <div class="welcome-content">
                    <span class="welcome-kicker">Final Year Project</span>
                    <h1 class="welcome-title">Plan Your Next Himalayan Escape</h1>
                    <p class="welcome-subtitle">Discover treks, reserve hotels, and manage gear rentals in one beautiful platform.</p>
                    <div class="welcome-actions">
                        <a href="{{ route('treks.index') }}" class="welcome-primary">Explore Treks</a>
                        <a href="{{ route('login') }}" class="welcome-secondary">Sign In</a>
                    </div>
                </div>

                <div class="welcome-stats">
                    <div class="welcome-stat">
                        <h3>500+</h3>
                        <p>Treks</p>
                    </div>
                    <div class="welcome-stat">
                        <h3>200+</h3>
                        <p>Hotels</p>
                    </div>
                    <div class="welcome-stat">
                        <h3>1000+</h3>
                        <p>Happy Trekkers</p>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
