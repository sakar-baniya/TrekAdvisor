<x-app-layout>

    {{-- ===================== HERO SECTION ===================== --}}
    <section id="hero" class="hero-section">

        <div class="container hero-center">

            {{-- Headline --}}
            <h1 class="hero-title">
                Discover Your Next Adventure
            </h1>

            {{-- Subtitle --}}
            <p class="hero-subtitle">
                Trek the Himalayas, Book Hotels, Rent Gear &mdash; All in One Place
            </p>

            {{-- Horizontal Search Bar --}}
            <div class="hero-search">
                <div class="hero-search-bar">

                    {{-- Text input --}}
                    <input
                        id="hero-search-input"
                        type="text"
                        placeholder="Search treks, hotels..."
                        class="hero-search-input"
                    >

                    {{-- Divider --}}
                    <div class="hero-search-divider"></div>

                    {{-- Category dropdown --}}
                    <select
                        id="hero-search-category"
                        class="hero-search-select"
                    >
                        <option value="treks">Treks</option>
                        <option value="hotels">Hotels</option>
                        <option value="gear">Gear</option>
                    </select>

                    {{-- Search button --}}
                    <a href="{{ route('treks.index') }}"
                       id="hero-search-btn"
                       class="hero-search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        Search
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-value">500+</div>
                    <div class="hero-stat-label">Treks</div>
                </div>
                <div>
                    <div class="hero-stat-value">200+</div>
                    <div class="hero-stat-label">Hotels</div>
                </div>
                <div>
                    <div class="hero-stat-value">1000+</div>
                    <div class="hero-stat-label">Happy Trekkers</div>
                </div>
            </div>

        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Featured Treks</h2>
                </div>
                <a href="{{ route('treks.index') }}" class="view-link">View All</a>
            </div>

            <div class="card-grid">
                @forelse($featuredTreks as $trek)
                    <article class="feature-card">
                        <div class="card-top">
                            Trek
                        </div>
                        <div class="card-content">
                            <div class="meta-row">
                                <span>{{ $trek->difficulty }}</span>
                                <span>â˜… 4.8</span>
                            </div>
                            <h3 class="card-title">{{ $trek->title }}</h3>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($trek->description, 70) }}</p>
                            <div class="card-actions">
                                <span class="price">${{ number_format($trek->base_price, 2) }}</span>
                                <a href="{{ route('treks.show', $trek->slug) }}" class="action-btn">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No active treks found.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Featured Hotels</h2>
                </div>
                <span class="view-link">View All</span>
            </div>
            <div class="card-grid">
                @forelse($featuredHotels as $hotel)
                    <article class="feature-card">
                        <div class="card-top">
                            Hotel
                        </div>
                        <div class="card-content">
                            <div class="meta-row">
                                <span>{{ $hotel->location }}</span>
                                <span>â˜… 4.6</span>
                            </div>
                            <h3 class="card-title">{{ $hotel->name }}</h3>
                            <div class="card-actions">
                                <span class="price">${{ number_format($hotel->rooms_min_price_per_night ?? 0, 2) }}/night</span>
                                <span class="action-btn">Book Now</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No active hotels yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Rental Gear</h2>
                </div>
                <span class="view-link">View All</span>
            </div>
            <div class="card-grid">
                @forelse($featuredGearItems as $item)
                    <article class="feature-card">
                        <div class="card-top">
                            Gear
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $item->name }}</h3>
                            <p class="card-text">{{ $item->type }}</p>
                            <div class="card-actions">
                                <span class="price">${{ number_format($item->daily_price, 2) }}/day</span>
                                <span class="action-btn">Rent</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No gear items available yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready for Your Adventure?</h2>
            <p class="cta-text">Join thousands of happy trekkers exploring the Himalayas.</p>
            <a href="{{ route('treks.index') }}" class="cta-btn">
                Start Planning Now
            </a>
        </div>
    </section>
</x-app-layout>
