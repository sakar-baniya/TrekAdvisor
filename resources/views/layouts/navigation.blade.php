<div x-data="{ open: false }" class="header-wrapper">
    <div class="header-main">
        <div class="container header-main__inner">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-badge"><i class="fas fa-mountain-sun"></i></span>
                <span class="brand-lockup">
                    <span class="brand-wordmark">TrekAdvisor</span>
                    <span class="brand-tagline">Walk, Explore and Discover</span>
                </span>
            </a>

            <div class="header-contact">
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="contact-card__text">
                        <small>Direct Call or WhatsApp 24/7</small>
                        <strong>+977 9851 058678</strong>
                    </div>
                </div>
            </div>

            <button @click="open = ! open" class="nav-toggle" aria-label="Toggle navigation">
                <svg class="icon-24" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'is-hidden': open, 'is-inline-flex': ! open }" class="is-inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'is-hidden': ! open, 'is-inline-flex': open }" class="is-hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <nav class="nav-bar">
        <div class="container nav-bar__inner">
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('treks.index') }}" class="nav-link {{ request()->routeIs('treks.*') ? 'is-active' : '' }}">Treks</a>
                <a href="{{ route('hotels.index') }}" class="nav-link {{ request()->routeIs('hotels.*') ? 'is-active' : '' }}">Hotels</a>
                <a href="{{ route('gear.index') }}" class="nav-link {{ request()->routeIs('gear.*') ? 'is-active' : '' }}">Gear Rental</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
                <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'is-active' : '' }}">Travel Guide</a>
            </div>

            <div class="nav-actions">
                <a href="{{ route('contact') }}" class="btn btn-cta">CONTACT US</a>
                <button class="search-trigger"><i class="fas fa-search"></i></button>
                @auth
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="btn btn-link">My Account</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div :class="{ 'open': open }" class="nav-mobile">
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
