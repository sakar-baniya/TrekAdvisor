<x-app-layout>
    <section class="market-hero">
        <div class="container market-hero__inner">
            <p class="market-kicker">Tourism Marketplace</p>
            <h1 class="market-hero__title">Discover Your Next Adventure</h1>
            <p class="market-hero__subtitle">Trek the Himalayas, book trusted stays, and rent the gear you need from one streamlined experience.</p>

            <div class="market-search-card">
                <div class="market-search-grid">
                    <input type="text" placeholder="Search treks, hotels, gear" class="market-input" />
                    <select class="market-input">
                        <option>All Services</option>
                        <option>Treks</option>
                        <option>Hotels</option>
                        <option>Gear</option>
                    </select>
                    <a href="{{ route('treks.index') }}" class="market-search-btn">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </a>
                </div>
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
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="market-section__head">
                <div>
                    <p class="market-kicker">Top Picks</p>
                    <h2>Featured Treks</h2>
                </div>
                <a href="{{ route('treks.index') }}" class="market-link">View All</a>
            </div>

            <div class="market-card-grid market-card-grid--three">
                @forelse ($featuredTreks as $trek)
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($trek->image) style="background-image: linear-gradient(rgba(8, 145, 178, 0.15), rgba(6, 78, 59, 0.45)), url('{{ $trek->image }}');" @endif>
                            <span class="market-badge market-badge--{{ strtolower($trek->difficulty) === 'easy' ? 'green' : (strtolower($trek->difficulty) === 'moderate' ? 'orange' : 'red') }}">{{ $trek->difficulty }}</span>
                            <span class="market-rating"><i class="fas fa-star"></i> {{ number_format($trek->reviews->avg('rating') ?? 4.8, 1) }}</span>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $trek->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($trek->description), 95) }}</p>
                            <div class="market-card__footer">
                                <div>
                                    <strong>${{ number_format($trek->base_price, 0) }}</strong>
                                    <span>/person</span>
                                </div>
                                <a href="{{ route('treks.show', $trek->slug) }}" class="market-button">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No featured treks yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="featured-hotels" class="market-section market-section--soft">
        <div class="container">
            <div class="market-section__head">
                <div>
                    <p class="market-kicker">Stay Options</p>
                    <h2>Featured Hotels</h2>
                </div>
                <a href="{{ route('home') }}#featured-hotels" class="market-link">Explore</a>
            </div>

            <div class="market-card-grid market-card-grid--three">
                @forelse ($featuredHotels as $hotel)
                    <article class="market-card market-card--hotel">
                        <div class="market-card__media market-card__media--icon">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <div class="market-card__body">
                            <div class="market-card__meta"><span><i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}</span><span><i class="fas fa-star"></i> 4.6</span></div>
                            <h3>{{ $hotel->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($hotel->description), 88) }}</p>
                            <div class="market-card__footer">
                                <div>
                                    <strong>${{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong>
                                    <span>/night</span>
                                </div>
                                <span class="market-button market-button--ghost">Book Soon</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No hotel listings yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="featured-gear" class="market-section">
        <div class="container">
            <div class="market-section__head">
                <div>
                    <p class="market-kicker">Rental Gear</p>
                    <h2>Essentials for the Trail</h2>
                </div>
                <a href="{{ route('home') }}#featured-gear" class="market-link">Browse</a>
            </div>

            <div class="market-card-grid market-card-grid--four">
                @forelse ($featuredGearItems as $item)
                    <article class="market-mini-card">
                        <div class="market-mini-card__icon"><i class="fas fa-campground"></i></div>
                        <span class="market-stock {{ $item->available_stock > 5 ? 'is-good' : 'is-low' }}">Available: {{ $item->available_stock }}</span>
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->type }}</p>
                        <div class="market-mini-card__footer">
                            <strong>${{ number_format($item->daily_price, 0) }}/day</strong>
                            <span class="market-button market-button--ghost">Rent</span>
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
            <p>Join trekkers using TrekAdvisor to plan routes, stays, and rental gear in one place.</p>
            <a href="{{ route('treks.index') }}" class="market-cta__button">Start Planning Now</a>
        </div>
    </section>
</x-app-layout>
