<x-app-layout>
    <section class="market-hero">
        <div class="container market-hero__inner">
            <div class="market-hero__text-box">
                <h1 class="market-hero__title">Local Experts in Himalayan<br>Trekking</h1>
                <p class="market-hero__subtitle">Explore the Himalayas with a team that's guided with care for over 17 years.</p>
            </div>
            <div class="hero-search-wrapper" x-data="{
                category: 'treks',
                treksUrl: '{{ route('treks.index') }}',
                hotelsUrl: '{{ route('hotels.index') }}',
                placeholder() {
                    if (this.category === 'treks') return 'Search for treks, destinations...'
                    return 'Search for hotels, locations...'
                },
                exploreLabel() {
                    if (this.category === 'treks') return 'Explore Treks'
                    return 'Explore Hotels'
                },
                actionUrl() {
                    if (this.category === 'treks') return this.treksUrl
                    return this.hotelsUrl
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
                    </div>

                    <button type="submit" class="hero-search-btn" x-text="exploreLabel()"></button>
                </form>
            </div>

            <div class="market-hero__stats">
                <div>
                    <strong>{{ \App\Models\Trek::where('status', 'active')->count() }}+</strong>
                    <span>Featured Treks</span>
                </div>
                <div>
                    <strong>{{ \App\Models\Hotel::where('status', 'active')->count() }}+</strong>
                    <span>Active Hotels</span>
                </div>
            </div>

        </div>
    </section>

    <section class="booking-section">
        <div class="container container-wide">
            <div class="booking-section__head">
                <div class="booking-section__title">
                    <p class="section-kicker">Adventure Awaits</p>
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

            <div class="booking-grid">
                @forelse ($featuredTreks->take(3) as $trek)
                    <article class="booking-card booking-card--featured booking-card--trek">
                        <div class="booking-card__media">
                            @if($trek->image)
                                <img src="{{ $trek->image }}" alt="Image of {{ $trek->title }}">
                            @else
                                <div class="booking-card__media-placeholder"></div>
                            @endif
                        </div>
                        <div class="booking-card__body">
                            <div class="booking-card__meta">
                                <span class="booking-card__rating"><i class="fas fa-star"></i> {{ $trek->reviews_avg_rating ? number_format($trek->reviews_avg_rating, 1) : 'New' }} ({{ $trek->reviews_count }} reviews)</span>
                            </div>
                            <h3>{{ $trek->title }}</h3>
                            <div class="booking-card__footer">
                                <div class="booking-card__price">From <strong>${{ number_format($trek->base_price, 0) }}</strong> <span>/person</span></div>
                                <a href="{{ route('treks.show', $trek->slug) }}" class="btn-primary-filled booking-card__action">View Details</a>
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

            <div class="booking-grid">
                @forelse ($featuredHotels->take(3) as $hotel)
                    <article class="booking-card booking-card--featured booking-card--trek">
                        <div class="booking-card__media">
                            @if($hotel->image)
                                <img src="{{ $hotel->image }}" alt="Image of {{ $hotel->name }}">
                            @else
                                <div class="booking-card__media-placeholder"></div>
                            @endif
                        </div>
                        <div class="booking-card__body">
                            <div class="booking-card__meta">
                                <span class="booking-card__rating"><i class="fas fa-star"></i> {{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'New' }} ({{ $hotel->reviews_count }} reviews)</span>
                            </div>
                            <h3>{{ $hotel->name }}</h3>
                            <div class="booking-card__footer">
                                <div class="booking-card__price">From <strong>${{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong> <span>/night</span></div>
                                <a href="{{ route('hotels.show', $hotel) }}" class="btn-primary-filled booking-card__action">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No hotel listings yet.</p>
                @endforelse
            </div>
        </div>
    </section>



    <section class="market-cta">
        <div class="container market-cta__inner">
            <h2>Ready for Your Adventure?</h2>
            <p>Join trekkers using TrekAdvisor to plan routes and stays in one thoughtful, premium experience.</p>
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
                @foreach($testimonials as $testimonial)
                    <article class="market-quote-card">
                        <div class="market-quote-card__top">
                            <div class="market-quote-card__stars">
                                @for($i = 0; $i < 5; $i++)
                                    {{ $i < $testimonial->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                            <i class="fas fa-quote-right quote-icon"></i>
                        </div>
                        <p class="market-quote-card__text">"{{ Str::limit($testimonial->comment, 150) }}"</p>
                        <div class="market-quote-card__author">
                            <div class="avatar"><i class="fas fa-user"></i></div>
                            <div class="author-info">
                                <strong>{{ $testimonial->user?->name ?? 'Anonymous' }}</strong>
                                <span>{{ $testimonial->reviewable?->title ?? $testimonial->reviewable?->name ?? 'Recent Trip' }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
