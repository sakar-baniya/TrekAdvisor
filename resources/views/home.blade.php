<x-app-layout>
    <section class="relative overflow-hidden bg-slate-900 text-white">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1600&q=80" alt="Himalayan trek" class="h-full w-full object-cover opacity-80">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/70 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-28">
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-200">TrekAdvisor</p>
            <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-black leading-tight">
                Plan your trek with confidence.
            </h1>
            <p class="mt-4 max-w-2xl text-base sm:text-lg text-slate-200">
                Discover Himalayan treks, then complete your trip with hotels and gear rentals in one place.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('treks.index') }}" class="rounded-full bg-emerald-500 px-6 py-3 text-sm font-bold text-slate-900 hover:bg-emerald-400 transition">
                    Browse Treks
                </a>
                @guest
                    <a href="{{ route('register') }}" class="rounded-full border border-white/40 px-6 py-3 text-sm font-bold text-white hover:bg-white/10 transition">
                        Create Account
                    </a>
                @else
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="rounded-full border border-white/40 px-6 py-3 text-sm font-bold text-white hover:bg-white/10 transition">
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Featured Treks</h2>
                <p class="text-sm text-slate-600 mt-1">Handpicked routes with active departures.</p>
            </div>
            <a href="{{ route('treks.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-600">View all treks</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @forelse($featuredTreks as $trek)
                <article class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $trek->title }}" class="h-44 w-full object-cover">
                    <div class="p-5">
                        <p class="text-xs font-semibold text-emerald-700 uppercase">{{ $trek->difficulty }}</p>
                        <h3 class="mt-2 text-lg font-black text-slate-900">{{ $trek->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($trek->description, 80) }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-900">${{ number_format($trek->base_price, 2) }}</span>
                            <a href="{{ route('treks.show', $trek->slug) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-600">Details</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">No active treks found.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <h3 class="text-base font-black text-slate-900">Trusted Routes</h3>
                    <p class="mt-2 text-sm text-slate-600">Routes curated by local experts and guides.</p>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <h3 class="text-base font-black text-slate-900">Transparent Pricing</h3>
                    <p class="mt-2 text-sm text-slate-600">Clear pricing with group discounts built-in.</p>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <h3 class="text-base font-black text-slate-900">One Marketplace</h3>
                    <p class="mt-2 text-sm text-slate-600">Treks, hotels, and gear rentals in one place.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Hotels Preview</h2>
                <p class="text-sm text-slate-600 mt-1 mb-6">Hotel marketplace goes live next.</p>
                <div class="space-y-4">
                    @forelse($featuredHotels as $hotel)
                        <article class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-base font-bold text-slate-900">{{ $hotel->name }}</h3>
                            <p class="text-sm text-slate-600">{{ $hotel->location }}</p>
                            <p class="text-sm font-semibold text-emerald-700 mt-2">
                                From ${{ number_format($hotel->rooms_min_price_per_night ?? 0, 2) }}/night
                            </p>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">No active hotels yet.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Gear Preview</h2>
                <p class="text-sm text-slate-600 mt-1 mb-6">Rental gear available soon.</p>
                <div class="space-y-4">
                    @forelse($featuredGearItems as $item)
                        <article class="rounded-xl border border-slate-200 bg-white p-4 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">{{ $item->name }}</h3>
                                <p class="text-sm text-slate-600">{{ $item->type }}</p>
                                <p class="text-sm font-semibold text-emerald-700 mt-2">${{ number_format($item->daily_price, 2) }}/day</p>
                            </div>
                            <span class="rounded-full bg-emerald-100 text-emerald-800 px-3 py-1 text-xs font-semibold">
                                {{ $item->available_stock }} in stock
                            </span>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">No gear items available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
