<nav x-data="{ open: false }" class="bg-white border-b border-[#e2e2e2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-black text-[#1a1e18]">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#1a1e18] text-white text-xs">TA</span>
                    <span>TrekAdvisor</span>
                </a>

                <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-[#3a3a3a]">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#1a1e18]' : 'hover:text-[#1a1e18]' }}">Home</a>
                    <a href="{{ route('treks.index') }}" class="{{ request()->routeIs('treks.*') ? 'text-[#1a1e18]' : 'hover:text-[#1a1e18]' }}">Treks</a>
                    <span class="text-[#9a9a9a]">Hotels</span>
                    <span class="text-[#9a9a9a]">Gear Rental</span>
                    @auth
                        <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="hover:text-[#1a1e18]">My Account</a>
                    @endauth
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-[#3a3a3a] hover:text-[#1a1e18]">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[#3a3a3a] hover:text-[#1a1e18]">Login</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-[#1a1e18] px-4 py-2 text-sm font-semibold text-white hover:bg-black">Sign Up</a>
                @endauth
            </div>

            <button @click="open = ! open" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-[#3a3a3a] hover:bg-[#f4f4f4]">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-[#e2e2e2] bg-white">
        <div class="px-4 py-3 space-y-2 text-sm font-semibold text-[#3a3a3a]">
            <a href="{{ route('home') }}" class="block">Home</a>
            <a href="{{ route('treks.index') }}" class="block">Treks</a>
            <span class="block text-[#9a9a9a]">Hotels</span>
            <span class="block text-[#9a9a9a]">Gear Rental</span>
            @auth
                <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="block">My Account</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-left">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block">Login</a>
                <a href="{{ route('register') }}" class="block text-[#1a1e18]">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>
