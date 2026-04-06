@php
    $navVariant = $navVariant ?? 'default';
    $navClusterClass = $navVariant === 'auth' ? 'site-nav-cluster site-nav-cluster--auth' : 'site-nav-cluster';
    $navClass = $navVariant === 'auth' ? 'site-nav site-nav--auth' : 'site-nav';
    $navMobileClass = $navVariant === 'auth' ? 'nav-mobile nav-mobile--auth' : 'nav-mobile';
@endphp

<div class="{{ $navClusterClass }}" data-nav-shell>
    <nav class="{{ $navClass }}" aria-label="Primary">
        <div class="container nav-inner">
            <div class="nav-section nav-left">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-badge"><i class="fas fa-mountain-sun"></i></span>
                    <span class="brand-wordmark">TrekAdvisor</span>
                </a>
            </div>
            <div class="nav-section nav-center">
                <div class="nav-links">
                    <a href="{{ route('treks.index') }}" class="nav-link {{ request()->routeIs('treks.*') ? 'is-active' : '' }}">Treks</a>
                    <a href="{{ route('hotels.index') }}" class="nav-link {{ request()->routeIs('hotels.*') ? 'is-active' : '' }}">Hotels</a>

                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'is-active' : '' }}">Travel Guide</a>
                </div>
            </div>
            <div class="nav-section nav-right">
                <a href="{{ route('contact') }}" class="btn btn-cta">Contact</a>
                @auth
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="btn btn-link nav-account-link {{ request()->routeIs(auth()->user()->dashboardRouteName()) ? 'is-active' : '' }}">My Account</a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                        @csrf
                        <button type="submit" class="btn btn-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-auth btn-auth--ghost">Login</a>
                @endauth
                <button
                    type="button"
                    class="nav-toggle"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-controls="site-nav-mobile"
                    aria-label="Toggle navigation menu"
                >
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <div id="site-nav-mobile" class="{{ $navMobileClass }}" data-nav-mobile>
        <div class="nav-mobile-links">
            <a href="{{ route('treks.index') }}" class="nav-link">Treks</a>
            <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>

            <a href="{{ route('about') }}" class="nav-link">About</a>
            <a href="{{ route('blog') }}" class="nav-link">Travel Guide</a>
            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            @auth
                <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="nav-link">My Account</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            @endauth
        </div>
    </div>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/9779816681137" class="floating-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
