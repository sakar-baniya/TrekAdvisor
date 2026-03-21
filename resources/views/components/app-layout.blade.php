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
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            <div class="container footer-center">
                <p class="footer-note">
                    &copy; {{ date('Y') }} TrekAdvisor - Final Year Project
                </p>
            </div>
        </footer>
    </body>
</html>
