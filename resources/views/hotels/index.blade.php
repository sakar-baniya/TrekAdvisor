<x-app-layout>
    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">Hotels</p>
            <h1>Find your perfect stay</h1>
            <p>Browse active hotel listings connected to your trekking journey.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="market-card-grid market-card-grid--three">
                @forelse ($hotels as $hotel)
                    <article class="market-card market-card--hotel">
                        <div class="market-card__media market-card__media--icon">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <div class="market-card__body">
                            <div class="market-card__meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}</span>
                                <span><i class="fas fa-star"></i> {{ number_format($hotel->reviews->avg('rating') ?? 4.6, 1) }}</span>
                            </div>
                            <h3>{{ $hotel->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($hotel->description), 100) }}</p>
                            <div class="market-card__footer">
                                <div>
                                    <strong>${{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong>
                                    <span>/night</span>
                                </div>
                                <a href="{{ route('hotels.show', $hotel) }}" class="market-button">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No hotels are available yet.</p>
                @endforelse
            </div>

            @if ($hotels->hasPages())
                <div class="catalog-pagination">{{ $hotels->links() }}</div>
            @endif
        </div>
    </section>
</x-app-layout>
