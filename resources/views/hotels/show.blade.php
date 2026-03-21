<x-app-layout>
    <section class="detail-hero">
        <div class="container detail-hero__content">
            <div class="detail-breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('hotels.index') }}">Hotels</a> / <span>{{ $hotel->name }}</span></div>
            <h1>{{ $hotel->name }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}</span>
                <span><i class="fas fa-hotel"></i> {{ $hotel->rooms->count() }} room types</span>
                <span><i class="fas fa-star"></i> {{ number_format($hotel->reviews->avg('rating') ?? 4.6, 1) }} ({{ $hotel->reviews->count() }})</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            <article class="detail-panel">
                <h2>Hotel Overview</h2>
                <p>{!! nl2br(e($hotel->description)) !!}</p>
            </article>

            <article class="detail-panel">
                <h2>Available Rooms</h2>
                <div class="detail-departure-list">
                    @forelse ($hotel->rooms as $room)
                        <div class="detail-departure-card">
                            <div class="detail-departure-card__top">
                                <div>
                                    <strong>{{ $room->room_type }}</strong>
                                    <span>{{ $room->total_rooms }} rooms available</span>
                                </div>
                            </div>
                            <div class="detail-departure-card__bottom">
                                <span>${{ number_format($room->price_per_night, 0) }}/night</span>
                                <span class="market-button market-button--ghost">Book Soon</span>
                            </div>
                        </div>
                    @empty
                        <p class="empty-note">No room information available yet.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <aside class="detail-sidebar">
            <div class="detail-booking-card">
                <div class="detail-price-block">
                    <span>Starting from</span>
                    <strong>${{ number_format($hotel->rooms->min('price_per_night') ?? 0, 0) }}</strong>
                    <small>per night</small>
                </div>
                <div class="detail-discount-box">
                    <strong>Stay details</strong>
                    <ul>
                        <li>Location-aware booking support</li>
                        <li>Clean room type breakdown</li>
                        <li>Built to complement trek planning</li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
