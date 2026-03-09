<x-app-layout>
    <section class="relative overflow-hidden bg-gradient-to-br from-sky-900 via-cyan-800 to-teal-700 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.16),_transparent_42%)]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-28">
            <p class="inline-flex items-center rounded-full bg-white/15 px-4 py-1 text-xs font-semibold tracking-wide uppercase">
                TrekAdvisor
            </p>
            <h1 class="mt-5 max-w-3xl text-4xl sm:text-5xl lg:text-6xl font-black leading-tight">
                Book treks, hotels, and gear in one place.
            </h1>
            <p class="mt-5 max-w-2xl text-base sm:text-lg text-cyan-50">
                Start with curated Himalayan treks today, then complete your trip with hotel stays and rental gear.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('treks.index') }}" class="inline-flex items-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-bold text-white hover:bg-orange-400 transition">
                    Explore Treks
                </a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg border border-white/40 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">
                        Create Account
                    </a>
                @else
                    <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="inline-flex items-center rounded-lg border border-white/40 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">
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
                <p class="text-sm text-slate-600 mt-1">Active departures and top adventure picks.</p>
            </div>
            <a href="{{ route('treks.index') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-600">View all</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @forelse($featuredTreks as $trek)
                <article class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $trek->title }}" class="h-44 w-full object-cover">
                    <div class="p-5">
                        <p class="text-xs font-semibold text-cyan-700 uppercase">{{ $trek->difficulty }}</p>
                        <h3 class="mt-2 text-lg font-black text-slate-900">{{ $trek->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($trek->description, 85) }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-900">${{ number_format($trek->base_price, 2) }}</span>
                            <a href="{{ route('treks.show', $trek->slug) }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-600">Details</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">No active treks found.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-slate-100 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Featured Hotels</h2>
                    <p class="text-sm text-slate-600 mt-1 mb-6">Hotel booking module is next in line.</p>
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
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Featured Gear</h2>
                    <p class="text-sm text-slate-600 mt-1 mb-6">Rental items available stock preview.</p>
                    <div class="space-y-4">
                        @forelse($featuredGearItems as $item)
                            <article class="rounded-xl border border-slate-200 bg-white p-4 flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $item->name }}</h3>
                                    <p class="text-sm text-slate-600">{{ $item->type }}</p>
                                    <p class="text-sm font-semibold text-cyan-700 mt-2">${{ number_format($item->daily_price, 2) }}/day</p>
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
        </div>
    </section>
</x-app-layout>
