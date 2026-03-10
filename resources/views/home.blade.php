<x-app-layout>

    {{-- ===================== HERO SECTION ===================== --}}
    <section id="hero" class="text-white" style="background-color: #0d2137; padding: 64px 16px 72px;">

        <div class="max-w-4xl mx-auto text-center">

            {{-- Headline --}}
            <h1 style="font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 800; line-height: 1.2; color: #ffffff; margin-bottom: 14px;">
                Discover Your Next Adventure
            </h1>

            {{-- Subtitle --}}
            <p style="font-size: 0.95rem; color: rgba(255,255,255,0.72); margin-bottom: 32px;">
                Trek the Himalayas, Book Hotels, Rent Gear &mdash; All in One Place
            </p>

            {{-- Horizontal Search Bar --}}
            <div class="mx-auto" style="max-width: 660px;">
                <div style="display: flex; align-items: stretch; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.25);">

                    {{-- Text input --}}
                    <input
                        id="hero-search-input"
                        type="text"
                        placeholder="Search treks, hotels..."
                        style="flex: 1; min-width: 0; border: none; outline: none; padding: 14px 18px; font-size: 0.875rem; color: #1a2e3d; background: transparent; font-family: inherit;"
                    >

                    {{-- Divider --}}
                    <div style="width: 1px; background: #e0e0e0; margin: 10px 0; flex-shrink: 0;"></div>

                    {{-- Category dropdown --}}
                    <select
                        id="hero-search-category"
                        style="border: none; outline: none; background: transparent; padding: 14px 14px; font-size: 0.875rem; color: #1a2e3d; cursor: pointer; font-family: inherit; min-width: 110px; flex-shrink: 0;"
                    >
                        <option value="treks">Treks</option>
                        <option value="hotels">Hotels</option>
                        <option value="gear">Gear</option>
                    </select>

                    {{-- Search button --}}
                    <a href="{{ route('treks.index') }}"
                       id="hero-search-btn"
                       style="display: inline-flex; align-items: center; gap: 6px; background: #f97316; color: #ffffff; padding: 14px 24px; font-size: 0.875rem; font-weight: 600; white-space: nowrap; flex-shrink: 0; text-decoration: none; font-family: inherit; transition: background 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        Search
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div style="margin-top: 40px; display: flex; flex-wrap: wrap; justify-content: center; gap: 40px;">
                <div class="text-center">
                    <div style="font-size: 1.6rem; font-weight: 800; color: #ffffff; line-height: 1;">500+</div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.5); margin-top: 4px;">Treks</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 1.6rem; font-weight: 800; color: #ffffff; line-height: 1;">200+</div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.5); margin-top: 4px;">Hotels</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 1.6rem; font-weight: 800; color: #ffffff; line-height: 1;">1000+</div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.5); margin-top: 4px;">Happy Trekkers</div>
                </div>
            </div>

        </div>
    </section>

    <style>
        #hero-search-btn:hover { background: #ea580c !important; }
    </style>

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
