<x-app-layout>
    <section class="detail-hero">
        <div class="container detail-hero__content">
            <h1>{{ $hotel->name }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}</span>
                <span><i class="fas fa-hotel"></i> {{ $hotel->rooms->count() }} room types</span>
                <span><i class="fas fa-bed"></i> {{ $hotel->rooms->sum('total_rooms') }} total rooms</span>
                <span><i class="fas fa-star"></i> {{ number_format($hotel->reviews->avg('rating') ?? 4.6, 1) }} ({{ $hotel->reviews->count() }})</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            @if ($hotel->image)
                <article class="detail-panel">
                    <div class="market-card__media" style="min-height: 300px; border-radius: 24px; background-image: linear-gradient(rgba(16, 27, 45, 0.12), rgba(16, 27, 45, 0.35)), url('{{ $hotel->image }}');"></div>
                </article>
            @endif

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
                        <li>{{ $hotel->location }}</li>
                        <li>{{ $hotel->rooms->count() }} room types currently listed</li>
                        <li>{{ $hotel->rooms->sum('total_rooms') }} rooms available across categories</li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
