<x-app-layout>
    @php
        $galleryImages = $hotel->gallery->pluck('path')->prepend($hotel->image)->filter()->unique()->values();
    @endphp

    <section class="detail-hero" @if($hotel->image) style="background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.5)), url('{{ $hotel->image }}');" @endif>
        <div class="container detail-hero__content">
            <h1>{{ $hotel->name }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-map-marker-alt"></i> {{ $hotel->location }}</span>
                <span><i class="fas fa-hotel"></i> {{ $hotel->rooms->count() }} room types</span>
                <span><i class="fas fa-bed"></i> {{ $hotel->rooms->sum('total_rooms') }} total rooms</span>
                <span><i class="fas fa-star"></i> {{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'New' }} ({{ $hotel->reviews_count }})</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            @if ($galleryImages->isNotEmpty())
                <article class="detail-panel">
                    <div class="detail-gallery">
                        @foreach ($galleryImages as $image)
                            <div class="detail-gallery__item">
                                <img src="{{ $image }}" alt="{{ $hotel->name }} photo {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
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
