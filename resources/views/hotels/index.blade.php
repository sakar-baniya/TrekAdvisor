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
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($hotel->image) style="background-image: linear-gradient(rgba(6, 78, 89, 0.25), rgba(15, 23, 42, 0.5)), url('{{ $hotel->image }}');" @endif>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $hotel->name }}</h3>
                            <div class="market-card__trip-meta">
                                <span><i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i> {{ $hotel->location }}</span>
                                <span class="market-card__reviews">
                                    <i class="fas fa-star"></i>
                                    {{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'New' }}
                                    <em>{{ $hotel->reviews_count }} Reviews</em>
                                </span>
                            </div>
                            <div class="market-card__footer">
                                <div class="market-card__price">
                                    <strong>${{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong>
                                    <span>per night</span>
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
