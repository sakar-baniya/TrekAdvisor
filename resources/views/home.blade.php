<x-app-layout>
    <section class="bg-[#1a1e18] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black">Discover Your Next Adventure</h1>
                <p class="mt-3 text-sm sm:text-base text-[#d9d9d9]">
                    Trek the Himalayas, book hotels, rent gear. All in one place.
                </p>
            </div>

            <div class="mt-8 flex flex-col md:flex-row gap-3 items-stretch justify-center">
                <input type="text" placeholder="Search treks, hotels..." class="w-full md:w-[360px] rounded-md border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/70 focus:ring-2 focus:ring-white focus:border-white">
                <select class="w-full md:w-40 rounded-md border border-white/20 bg-white/10 px-4 py-3 text-sm text-white focus:ring-2 focus:ring-white focus:border-white">
                    <option>Treks</option>
                    <option>Hotels</option>
                    <option>Gear</option>
                </select>
                <a href="{{ route('treks.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-[#1a1e18] hover:bg-[#f4f4f4]">
                    Search
                </a>
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-10 text-xs font-semibold text-[#d9d9d9]">
                <span>500+ Treks</span>
                <span>200+ Hotels</span>
                <span>1000+ Happy Travelers</span>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-[#1a1e18]">Featured Treks</h2>
            </div>
            <a href="{{ route('treks.index') }}" class="text-xs font-semibold text-[#1a1e18] hover:text-black">View All</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @forelse($featuredTreks as $trek)
                <article class="rounded-xl border border-[#e2e2e2] bg-white shadow-sm overflow-hidden">
                    <div class="h-24 bg-[#f4f4f4] flex items-center justify-center text-[#1a1e18] text-sm font-black uppercase">
                        Trek
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between text-xs text-[#6b6b6b]">
                            <span>{{ $trek->difficulty }}</span>
                            <span>★ 4.8</span>
                        </div>
                        <h3 class="mt-2 text-base font-black text-[#1a1e18]">{{ $trek->title }}</h3>
                        <p class="mt-2 text-sm text-[#5a5a5a]">{{ \Illuminate\Support\Str::limit($trek->description, 70) }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-[#1a1e18]">${{ number_format($trek->base_price, 2) }}</span>
                            <a href="{{ route('treks.show', $trek->slug) }}" class="rounded-md bg-[#1a1e18] px-3 py-1.5 text-xs font-semibold text-white hover:bg-black">View Details</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">No active treks found.</p>
            @endforelse
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-[#1a1e18]">Featured Hotels</h2>
            </div>
            <span class="text-xs font-semibold text-[#1a1e18]">View All</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div>
                @forelse($featuredHotels as $hotel)
                    <article class="rounded-xl border border-[#e2e2e2] bg-white shadow-sm overflow-hidden">
                        <div class="h-24 bg-[#f4f4f4] flex items-center justify-center text-[#1a1e18] text-sm font-black uppercase">
                            Hotel
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between text-xs text-[#6b6b6b]">
                                <span>{{ $hotel->location }}</span>
                                <span>★ 4.6</span>
                            </div>
                            <h3 class="mt-2 text-base font-black text-[#1a1e18]">{{ $hotel->name }}</h3>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm font-bold text-[#1a1e18]">${{ number_format($hotel->rooms_min_price_per_night ?? 0, 2) }}/night</span>
                                <span class="rounded-md bg-[#1a1e18] px-3 py-1.5 text-xs font-semibold text-white">Book Now</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">No active hotels yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-[#1a1e18]">Rental Gear</h2>
            </div>
            <span class="text-xs font-semibold text-[#1a1e18]">View All</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredGearItems as $item)
                <article class="rounded-xl border border-[#e2e2e2] bg-white shadow-sm overflow-hidden">
                    <div class="h-20 bg-[#f4f4f4] flex items-center justify-center text-[#1a1e18] text-xs font-black uppercase">
                        Gear
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-bold text-[#1a1e18]">{{ $item->name }}</h3>
                        <p class="text-xs text-[#6b6b6b]">{{ $item->type }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-bold text-[#1a1e18]">${{ number_format($item->daily_price, 2) }}/day</span>
                            <span class="rounded-md bg-[#1a1e18] px-2 py-1 text-xs font-semibold text-white">Rent</span>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">No gear items available yet.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-[#1a1e18] text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h2 class="text-2xl sm:text-3xl font-black">Ready for Your Adventure?</h2>
            <p class="mt-2 text-sm text-[#d9d9d9]">Join thousands of happy trekkers exploring the Himalayas.</p>
            <a href="{{ route('treks.index') }}" class="mt-6 inline-flex items-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-[#1a1e18] hover:bg-[#f4f4f4]">
                Start Planning Now
            </a>
        </div>
    </section>
</x-app-layout>
