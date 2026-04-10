<x-app-layout>
    @php
        $galleryImages = $trek->gallery->pluck('path')->prepend($trek->image)->filter()->unique()->values();
    @endphp

    <section class="detail-hero" @if($trek->image) style="background-image: url('{{ $trek->image }}');" @endif>
        <div class="detail-hero__overlay"></div>
        <div class="container detail-hero__content">
            <h1>{{ $trek->title }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-clock"></i> {{ $trek->duration_days ?? $trek->itineraries->count() }} Days</span>
                <span><i class="fas fa-signal"></i> {{ $trek->difficulty }}</span>
                <span><i class="fas fa-mountain"></i> {{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'High-altitude route' }}</span>
                <span><i class="fas fa-star"></i> {{ $avgRating ? number_format($avgRating, 1) . ' - ' . $reviewCount . ' reviews' : 'New' }}</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            <div class="detail-tabs-wrap">
                <div class="detail-tabs">
                    <button type="button" class="detail-tab is-active" data-tab-target="overview">Overview</button>
                    <button type="button" class="detail-tab" data-tab-target="itinerary">Itinerary</button>
                    <button type="button" class="detail-tab" data-tab-target="reviews">Reviews</button>
                </div>
            </div>

            <article class="detail-panel detail-tab-panel is-active" data-tab-panel="overview">
                <h2>Trip Overview</h2>
                @if ($galleryImages->count() > 1)
                    <div class="detail-gallery">
                        @foreach ($galleryImages as $image)
                            <div class="detail-gallery__item">
                                <img src="{{ $image }}" alt="{{ $trek->title }} photo {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="detail-panel__lead">
                    <p>{!! nl2br(e($trek->description)) !!}</p>
                </div>
                <div class="detail-overview-highlights">
                    <h3 class="detail-panel__section-title">What makes this trek stand out</h3>
                    <div class="detail-highlight-grid">
                        <div class="detail-highlight-card">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Expert-led route</strong>
                                <span>Well-structured pacing for a smoother trekking experience.</span>
                            </div>
                        </div>
                        <div class="detail-highlight-card">
                            <i class="fas fa-compass"></i>
                            <div>
                                <strong>Adventure planning</strong>
                                <span>Departure selection, group pricing, and booking flow in one place.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="detail-panel detail-tab-panel" data-tab-panel="itinerary">
                <h2>Day by Day Itinerary</h2>
                <div class="detail-timeline">
                    @foreach($trek->itineraries as $itinerary)
                        <div class="detail-timeline__item">
                            <div class="detail-timeline__day">Day {{ $itinerary->day_number }}</div>
                            <div class="detail-timeline__content">
                                <h3>{{ $itinerary->title }}</h3>
                                <p>{{ $itinerary->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="detail-panel detail-tab-panel" data-tab-panel="reviews">
                <div class="detail-review-summary">
                    <div>
                        <strong>{{ $avgRating ?? 'New' }}</strong>
                        <span>{{ $reviewCount }} reviews</span>
                    </div>
                </div>
                <div class="detail-review-list">
                    @forelse($reviews as $review)
                        <div class="detail-review-card">
                            <strong class="detail-review-stars">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star {{ $i < $review->rating ? 'is-filled' : '' }}"></i>
                                @endfor
                            </strong>
                            <p>"{{ $review->comment }}"</p>
                            <span>{{ $review->user->name }} &bull; {{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="empty-note">No reviews yet.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <aside class="detail-sidebar">
            <div class="detail-booking-card">
                <div class="detail-price-block">
                    <span>Starting from</span>
                    <strong>${{ number_format($trek->base_price, 0) }}</strong>
                    <small>per person</small>
                </div>
                <details class="detail-discount-box">
                    <summary>Group discounts</summary>
                    <ul>
                        <li>3-5 people: 5% off</li>
                        <li>6-9 people: 10% off</li>
                        <li>10+ people: 15% off</li>
                    </ul>
                </details>
                <h3>Available Departures</h3>
                <div class="detail-departure-list">
                    @forelse($trek->departures as $departure)
                        <div class="detail-departure-card">
                            <div class="detail-departure-card__top">
                                <div>
                                    <strong>{{ $departure->start_date->format('M d') }} - {{ $departure->end_date->format('M d, Y') }}</strong>
                                    <span>{{ $departure->capacity - $departure->booked_seats }} / {{ $departure->capacity }} seats left</span>
                                </div>
                                <span class="detail-status-badge {{ ($departure->capacity - $departure->booked_seats) > 4 ? 'is-good' : 'is-low' }}">{{ $departure->status }}</span>
                            </div>
                            <div class="detail-departure-card__bottom">
                                <span>${{ number_format($departure->price, 0) }}</span>
                                <a href="{{ route('bookings.create', $departure->id) }}" class="market-button">Book Now</a>
                            </div>
                        </div>
                    @empty
                        <p class="empty-note">No dates available.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>

    <script>
        (() => {
            const tabs = document.querySelectorAll('[data-tab-target]');
            const panels = document.querySelectorAll('[data-tab-panel]');
            tabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    tabs.forEach((item) => item.classList.remove('is-active'));
                    panels.forEach((panel) => panel.classList.remove('is-active'));
                    tab.classList.add('is-active');
                    document.querySelector(`[data-tab-panel="${tab.dataset.tabTarget}"]`)?.classList.add('is-active');
                });
            });
        })();
    </script>
</x-app-layout>
