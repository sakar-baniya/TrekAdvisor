<div class="site-nav-cluster" x-data="{ open: false }">
    <nav class="site-nav" aria-label="Primary">
        <div class="container nav-inner">
            <div class="nav-section nav-left">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-badge"><i class="fas fa-mountain-sun"></i></span>
                    <span class="brand-wordmark">TrekAdvisor</span>
                </a>
            </div>
            <div class="nav-section nav-center">
                <div class="nav-links">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('treks.index') }}" class="nav-link {{ request()->routeIs('treks.*') ? 'is-active' : '' }}">Treks</a>
                    <a href="{{ route('hotels.index') }}" class="nav-link {{ request()->routeIs('hotels.*') ? 'is-active' : '' }}">Hotels</a>
                    <a href="{{ route('gear.index') }}" class="nav-link {{ request()->routeIs('gear.*') ? 'is-active' : '' }}">Gear Rental</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'is-active' : '' }}">Travel Guide</a>
                </div>
            </div>
            <div class="nav-section nav-right">
                <div class="header-contact">
                    <a href="https://wa.me/9779816681137" class="contact-card" target="_blank" rel="noopener noreferrer">
                        <span class="contact-card__icon" aria-hidden="true"><i class="fab fa-whatsapp"></i></span>
                        <span class="contact-card__text">
                            <span class="contact-card__label">24/7 WhatsApp</span>
                            <strong>+977 9816681137</strong>
                        </span>
                    </a>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-cta">Contact</a>
                <button type="button" class="search-trigger" aria-label="Search"><i class="fas fa-search"></i></button>
                @auth
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="btn btn-link nav-account-link">My Account</a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                        @csrf
                        <button type="submit" class="btn btn-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-auth btn-auth--ghost">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-auth btn-auth--primary">Sign Up</a>
                @endauth
                <button
                    type="button"
                    class="nav-toggle"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-controls="site-nav-mobile"
                    aria-label="Toggle navigation menu"
                >
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <div id="site-nav-mobile" class="nav-mobile" :class="{ 'open': open }" @click.outside="open = false">
        <div class="nav-mobile-links">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('treks.index') }}" class="nav-link">Treks</a>
            <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
            <a href="{{ route('gear.index') }}" class="nav-link">Gear Rental</a>
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
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            @endauth
        </div>
    </div>
</div>
