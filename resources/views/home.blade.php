<x-app-layout>
    <section class="market-hero">
        <div class="container market-hero__inner">
            <div class="market-hero__text-box">
                <h1 class="market-hero__title">Local Experts in Himalayan Trekking</h1>
                <p class="market-hero__subtitle">Explore the Himalayas with a team that's guided with care for over 17 years.</p>
            </div>

            <div class="hero-search-wrapper" x-data="{
                category: 'treks',
                treksUrl: '{{ route('treks.index') }}',
                hotelsUrl: '{{ route('hotels.index') }}',
                gearUrl: '{{ route('gear.index') }}',
                placeholder() {
                    if (this.category === 'treks') return 'Search for treks, destinations...'
                    if (this.category === 'hotels') return 'Search for hotels, locations...'
                    return 'Search for gear type, location...'
                },
                exploreLabel() {
                    if (this.category === 'treks') return 'Explore Treks'
                    if (this.category === 'hotels') return 'Explore Hotels'
                    return 'Explore Gear'
                },
                actionUrl() {
                    if (this.category === 'treks') return this.treksUrl
                    if (this.category === 'hotels') return this.hotelsUrl
                    return this.gearUrl
                }
            }">
                <div class="hero-search-tabs" role="tablist" aria-label="Search category">
                    <button
                        type="button"
                        class="hero-search-tab"
                        :class="{ 'is-active': category === 'treks' }"
                        @click="category = 'treks'"
                    >
                        Treks
                    </button>
                    <button
                        type="button"
                        class="hero-search-tab"
                        :class="{ 'is-active': category === 'hotels' }"
                        @click="category = 'hotels'"
                    >
                        Hotels
                    </button>
                    <button
                        type="button"
                        class="hero-search-tab"
                        :class="{ 'is-active': category === 'gear' }"
                        @click="category = 'gear'"
                    >
                        Gear Rental
                    </button>
                </div>

                <form :action="actionUrl()" method="GET" class="hero-search-bar">
                    <div class="hero-search-left">
                        <div class="hero-search-row">
                            <i class="fas fa-search search-icon" aria-hidden="true"></i>
                            <input
                                type="text"
                                name="search"
                                x-bind:placeholder="placeholder()"
                                class="hero-search-input"
                            />
                        </div>

                        <div class="hero-location-row" x-show="category" x-cloak>
                            <input
                                type="text"
                                name="location"
                                placeholder="Location (optional)"
                                class="hero-location-input"
                            />
                        </div>
                    </div>

                    <button type="submit" class="hero-search-btn" x-text="exploreLabel()"></button>
                </form>
            </div>

            <div class="market-hero__stats">
                <div>
                    <strong>{{ $featuredTreks->count() }}+</strong>
                    <span>Featured Treks</span>
                </div>
                <div>
                    <strong>{{ $featuredHotels->count() }}+</strong>
                    <span>Active Hotels</span>
                </div>
                <div>
                    <strong>{{ $featuredGearItems->count() }}+</strong>
                    <span>Gear Items</span>
                </div>
            </div>

            <a href="{{ route('treks.index') }}" class="market-hero__play" aria-label="Explore treks">
                <i class="fas fa-play"></i>
            </a>
        </div>
    </section>

    <section class="booking-section">
        <div class="container container-wide">
            <div class="booking-section__head">
                <div class="booking-section__title">
                    <p class="section-kicker">Top Picks</p>
                    <h2>Featured Treks</h2>
                </div>
                <div class="booking-section__actions">
                    <a href="{{ route('treks.index') }}" class="view-all-link">View All</a>
                    <div class="carousel-arrows">
                        <button class="arrow-btn" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                        <button class="arrow-btn" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="filter-chips">
                <button class="filter-chip active">All</button>
                <button class="filter-chip">Easy</button>
                <button class="filter-chip">Moderate</button>
                <button class="filter-chip">Hard</button>
            </div>

            <div class="booking-grid">
                @forelse ($featuredTreks->take(3) as $trek)
                    <article class="booking-card">
                        <div class="booking-card__media">
                            @if($trek->image)
                                <img src="{{ $trek->image }}" alt="Image of {{ $trek->title }}">
                            @else
                                <div class="booking-card__media-placeholder"></div>
                            @endif
                            <button class="save-btn" aria-label="Save"><i class="far fa-heart"></i></button>
                            <span class="booking-badge {{ strtolower($trek->difficulty) === 'hard' ? 'badge-red' : (strtolower($trek->difficulty) === 'moderate' ? 'badge-orange' : 'badge-green') }}">
                                {{ ucfirst($trek->difficulty) }}
                            </span>
                        </div>
                        <div class="booking-card__body">
                            <div class="booking-card__meta">
                                <span>{{ $trek->duration_days ?? 'Flexible' }} Days</span>
                                <span class="booking-card__rating"><i class="fas fa-star"></i> {{ number_format($trek->reviews->avg('rating') ?? 4.8, 1) }} ({{ $trek->reviews->count() ?: 12 }} reviews)</span>
                            </div>
                            <h3>{{ $trek->title }}</h3>
                            <div class="booking-card__footer">
                                <div class="booking-card__price">From <strong>${{ number_format($trek->base_price, 0) }}</strong> <span>/person</span></div>
                                <a href="{{ route('treks.show', $trek->slug) }}" class="btn-primary-filled">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No featured treks yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="featured-hotels" class="booking-section booking-section--soft">
        <div class="container container-wide">
            <div class="booking-section__head">
                <div class="booking-section__title">
                    <p class="section-kicker">Stay Options</p>
                    <h2>Featured Hotels</h2>
                </div>
                <div class="booking-section__actions">
                    <a href="{{ route('home') }}#featured-hotels" class="view-all-link">View All</a>
                    <div class="carousel-arrows">
                        <button class="arrow-btn" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                        <button class="arrow-btn" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="filter-chips">
                <button class="filter-chip active">All</button>
                <button class="filter-chip">Budget</button>
                <button class="filter-chip">Mid-range</button>
                <button class="filter-chip">Luxury</button>
            </div>

            <div class="booking-grid">
                @forelse ($featuredHotels->take(3) as $hotel)
                    <article class="booking-card">
                        <div class="booking-card__media">
                            @if($hotel->image)
                                <img src="{{ $hotel->image }}" alt="Image of {{ $hotel->name }}">
                            @else
                                <div class="booking-card__media-placeholder"></div>
                            @endif
                            <button class="save-btn" aria-label="Save"><i class="far fa-heart"></i></button>
                            <span class="booking-badge badge-location">
                                <i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit($hotel->location, 15) }}
                            </span>
                        </div>
                        <div class="booking-card__body">
                            <div class="booking-card__meta">
                                <span class="booking-card__rating"><i class="fas fa-star"></i> 4.6 (24 reviews)</span>
                                <span class="booking-card__amenities">
                                    <i class="fas fa-wifi" title="WiFi"></i>
                                    <i class="fas fa-swimming-pool" title="Pool"></i>
                                    <i class="fas fa-parking" title="Parking"></i>
                                </span>
                            </div>
                            <h3>{{ $hotel->name }}</h3>
                            <p class="booking-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($hotel->description), 60) }}</p>
                            <div class="booking-card__footer">
                                <div class="booking-card__price"><strong>${{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong> <span>/night</span></div>
                                <span class="btn-primary-filled">Book Now</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No hotel listings yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="featured-gear" class="booking-section">
        <div class="container container-wide">
            <div class="booking-section__head">
                <div class="booking-section__title">
                    <p class="section-kicker">Rental Gear</p>
                    <h2>Essentials for the Trail</h2>
                </div>
                <div class="booking-section__actions">
                    <a href="{{ route('home') }}#featured-gear" class="view-all-link">View All</a>
                    <div class="carousel-arrows">
                        <button class="arrow-btn" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                        <button class="arrow-btn" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="booking-grid">
                @forelse ($featuredGearItems->take(4) as $item)
                    <article class="booking-card">
                        <div class="booking-card__media">
                            @if ($item->image)
                                <img src="{{ $item->image }}" alt="Image of {{ $item->name }}">
                            @else
                                <div class="booking-card__media-placeholder"><i class="fas fa-campground"></i></div>
                            @endif
                            <button class="save-btn" aria-label="Save"><i class="far fa-heart"></i></button>
                            <span class="booking-badge badge-neutral">Available: {{ $item->available_stock }}</span>
                        </div>
                        <div class="booking-card__body">
                            <h3>{{ $item->name }}</h3>
                            <p class="booking-card__desc">{{ \Illuminate\Support\Str::limit($item->description, 50) }}</p>
                            <div class="booking-card__footer">
                                <div class="booking-card__price"><strong>${{ number_format($item->daily_price, 0) }}</strong> <span>/day</span></div>
                                <span class="btn-primary-filled">Rent Now</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No gear items available yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="market-cta">
        <div class="container market-cta__inner">
            <h2>Ready for Your Adventure?</h2>
            <p>Join trekkers using TrekAdvisor to plan routes, stays, and rental gear in one thoughtful, premium experience.</p>
            <a href="{{ route('treks.index') }}" class="market-cta__button">Start Planning Now</a>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="market-section__head">
                <div>
                    <p class="market-kicker">Testimonials</p>
                    <h2>What Trekkers Say</h2>
                </div>
            </div>

            <div class="market-card-grid market-card-grid--three">
                <article class="market-quote-card">
                    <div class="market-quote-card__top">
                        <div class="market-quote-card__stars">★★★★★</div>
                        <i class="fas fa-quote-right quote-icon"></i>
                    </div>
                    <p class="market-quote-card__text">"Everything felt smooth and premium from picking the route to checking departure details."</p>
                    <div class="market-quote-card__author">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="author-info">
                            <strong>Sajan Gurung</strong>
                            <span>Everest Base Camp Trek</span>
                        </div>
                    </div>
                </article>
                <article class="market-quote-card">
                    <div class="market-quote-card__top">
                        <div class="market-quote-card__stars">★★★★★</div>
                        <i class="fas fa-quote-right quote-icon"></i>
                    </div>
                    <p class="market-quote-card__text">"The clean layout made it easy to compare treks, stays, and gear without feeling overwhelmed."</p>
                    <div class="market-quote-card__author">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="author-info">
                            <strong>Nirmala Rai</strong>
                            <span>Annapurna Region</span>
                        </div>
                    </div>
                </article>
                <article class="market-quote-card">
                    <div class="market-quote-card__top">
                        <div class="market-quote-card__stars">★★★★☆</div>
                        <i class="fas fa-quote-right quote-icon"></i>
                    </div>
                    <p class="market-quote-card__text">"A strong booking experience with clear pricing, polished visuals, and the right information at each step."</p>
                    <div class="market-quote-card__author">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="author-info">
                            <strong>Prakash Tamang</strong>
                            <span>Langtang Trek</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</x-app-layout>
