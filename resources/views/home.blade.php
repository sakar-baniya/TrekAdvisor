<x-app-layout>
    <section class="market-hero">
        <div class="container market-hero__inner">
            <div class="hero-rating">
                <span class="rating-text">5.0</span>
                <span class="stars">★★★★★</span>
                <span class="rating-note">2614 TripAdvisor reviews</span>
            </div>
            <p class="market-kicker">Local Experts In Himalayan Journeys</p>
            <h1 class="market-hero__title">Local Experts in Himalayan Trekking</h1>
            <p class="market-hero__subtitle">Explore the Himalayas with a team that's guided with care for over 17 years.</p>

            <div class="hero-search-wrapper">
                <form action="{{ route('treks.index') }}" method="GET" class="hero-search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Find your trip..." class="hero-search-input" />
                    <button type="submit" class="hero-search-btn">Explore</button>
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
                @forelse ($featuredTreks->take(3) as $trek)
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($trek->image) style="background-image: linear-gradient(rgba(9, 39, 77, 0.18), rgba(32, 121, 215, 0.38)), url('{{ $trek->image }}');" @endif>
                            <span class="market-badge market-badge--label {{ strtolower($trek->difficulty) === 'easy' ? 'market-badge--green' : (strtolower($trek->difficulty) === 'moderate' ? 'market-badge--orange' : 'market-badge--red') }}">
                                <i class="fas fa-users"></i>
                                {{ strtolower($trek->difficulty) === 'easy' ? 'Easy Route' : (strtolower($trek->difficulty) === 'moderate' ? 'Group Tours' : 'High Trail') }}
                            </span>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $trek->title }}</h3>
                            <div class="market-card__trip-meta">
                                <span>{{ $trek->duration_days ?? 'Flexible' }} Days</span>
                                <span class="market-card__reviews">
                                    <i class="fas fa-star"></i>
                                    {{ number_format($trek->reviews->avg('rating') ?? 4.8, 1) }}
                                    <em>{{ $trek->reviews->count() ?: 12 }} Reviews</em>
                                </span>
                            </div>
                            <div class="market-card__footer">
                                <div class="market-card__price">
                                    <strong>US ${{ number_format($trek->base_price, 0) }}</strong>
                                    <span>per person</span>
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
                @forelse ($featuredHotels->take(3) as $hotel)
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
                    <div class="market-quote-card__stars">★★★★★</div>
                    <p>"Everything felt smooth and premium from picking the route to checking departure details."</p>
                    <strong>Sajan Gurung</strong>
                    <span>Everest Base Camp Trek</span>
                </article>
                <article class="market-quote-card">
                    <div class="market-quote-card__stars">★★★★★</div>
                    <p>"The clean layout made it easy to compare treks, stays, and gear without feeling overwhelmed."</p>
                    <strong>Nirmala Rai</strong>
                    <span>Annapurna Region</span>
                </article>
                <article class="market-quote-card">
                    <div class="market-quote-card__stars">★★★★☆</div>
                    <p>"A strong booking experience with clear pricing, polished visuals, and the right information at each step."</p>
                    <strong>Prakash Tamang</strong>
                    <span>Langtang Trek</span>
                </article>
            </div>
        </div>
    </section>
</x-app-layout>
