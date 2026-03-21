<nav x-data="{ open: false }" class="site-nav">
    <div class="container nav-inner">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-badge"><i class="fas fa-mountain"></i></span>
                <span class="brand-wordmark">TrekAdvisor</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('treks.index') }}" class="nav-link {{ request()->routeIs('treks.*') ? 'is-active' : '' }}">Treks</a>
                <a href="{{ route('home') }}#featured-hotels" class="nav-link">Hotels</a>
                <a href="{{ route('home') }}#featured-gear" class="nav-link">Gear Rental</a>
                @auth
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="nav-link">My Account</a>
                @endauth
            </div>
        </div>

        <div class="nav-actions">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-link"><i class="fas fa-user"></i> Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            @endauth
        </div>

        <button @click="open = ! open" class="nav-toggle" aria-label="Toggle navigation">
            <svg class="icon-24" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'is-hidden': open, 'is-inline-flex': ! open }" class="is-inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'is-hidden': ! open, 'is-inline-flex': open }" class="is-hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div :class="{ 'open': open }" class="nav-mobile">
        <div class="nav-mobile-links">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('treks.index') }}" class="nav-link">Treks</a>
            <a href="{{ route('home') }}#featured-hotels" class="nav-link">Hotels</a>
            <a href="{{ route('home') }}#featured-gear" class="nav-link">Gear Rental</a>
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
</nav>
