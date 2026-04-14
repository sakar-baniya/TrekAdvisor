<x-app-layout>
    @php
        // Build one gallery list by combining cover image and uploaded gallery photos.
        $galleryImages = $hotel->gallery->pluck('path')->prepend($hotel->image)->filter()->unique()->values();

        // Default back link is previous page.
        $backUrl = url()->previous();
        $returnTo = request()->query('return_to');

        // Use return_to only for local/safe URLs so users return to the right listing page.
        if (is_string($returnTo) && $returnTo !== '' && (\Illuminate\Support\Str::startsWith($returnTo, ['/']) || \Illuminate\Support\Str::startsWith($returnTo, url('/')))) {
            $backUrl = $returnTo;
        }
    @endphp

    <div class="container" style="margin-top: 1rem;">
        <a
            href="{{ $backUrl }}"
            style="display: inline-block; padding: 0.45rem 0.8rem; border: 1px solid #d1d5db; border-radius: 6px; background: #ffffff; color: #111827; text-decoration: none; line-height: 1.2;"
        >Back</a>
    </div>

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
                                <span>NPR {{ number_format($room->price_per_night, 0) }}/night</span>
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
                    <strong>NPR {{ number_format($hotel->rooms->min('price_per_night') ?? 0, 0) }}</strong>
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

                @if (filled($hotel->booking_policy))
                    <div class="detail-discount-box" style="margin-top: 0.75rem;">
                        <strong>Booking Policy</strong>
                        <p style="margin: 0.45rem 0 0; color: #374151; line-height: 1.45; font-size: 0.88rem;">{!! nl2br(e($hotel->booking_policy)) !!}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div style="margin-top: 1rem; padding: 0.65rem 0.75rem; border-radius: 8px; background: #fef2f2; color: #b91c1c; font-size: 0.9rem;">
                        {{ session('error') }}
                    </div>
                @endif

                @if (auth()->check() && auth()->user()->role === 'customer')
                    <form method="POST" action="{{ route('customer.hotel-bookings.store', $hotel) }}" style="margin-top: 1rem; display: grid; gap: 0.65rem;">
                        @csrf

                        <label style="display: grid; gap: 0.3rem; font-size: 0.86rem;">
                            <span>Room type</span>
                            <select name="hotel_room_id" required style="padding: 0.55rem 0.6rem; border: 1px solid #d1d5db; border-radius: 6px; background: #fff;">
                                <option value="">Select room</option>
                                @foreach ($hotel->rooms as $room)
                                    <option value="{{ $room->id }}" @selected((string) old('hotel_room_id') === (string) $room->id)>
                                        {{ $room->room_type }} - NPR {{ number_format($room->price_per_night, 0) }}/night ({{ $room->total_rooms }} total)
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_room_id')<small style="color: #b91c1c;">{{ $message }}</small>@enderror
                        </label>

                        <label style="display: grid; gap: 0.3rem; font-size: 0.86rem;">
                            <span>Check-in</span>
                            <input type="date" name="check_in" required value="{{ old('check_in') }}" min="{{ now()->toDateString() }}" style="padding: 0.55rem 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;" />
                            @error('check_in')<small style="color: #b91c1c;">{{ $message }}</small>@enderror
                        </label>

                        <label style="display: grid; gap: 0.3rem; font-size: 0.86rem;">
                            <span>Check-out</span>
                            <input type="date" name="check_out" required value="{{ old('check_out') }}" min="{{ now()->addDay()->toDateString() }}" style="padding: 0.55rem 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;" />
                            @error('check_out')<small style="color: #b91c1c;">{{ $message }}</small>@enderror
                        </label>

                        <label style="display: grid; gap: 0.3rem; font-size: 0.86rem;">
                            <span>Number of rooms</span>
                            <input type="number" name="num_rooms" min="1" max="10" required value="{{ old('num_rooms', 1) }}" style="padding: 0.55rem 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;" />
                            @error('num_rooms')<small style="color: #b91c1c;">{{ $message }}</small>@enderror
                        </label>

                        <button type="submit" class="market-button" style="width: 100%; justify-content: center;">Request Booking</button>
                        <small style="color: #4b5563; line-height: 1.4;">Online payment is not required now. Your booking request will be reviewed and confirmed by the hotel team.</small>
                    </form>
                @elseif (auth()->check())
                    <div style="margin-top: 1rem; font-size: 0.9rem; color: #4b5563; line-height: 1.5;">
                        Hotel booking requests can be submitted from customer accounts only.
                    </div>
                @else
                    <div style="margin-top: 1rem; font-size: 0.9rem; color: #4b5563; line-height: 1.5;">
                        <a href="{{ route('login') }}" style="color: #0f172a; text-decoration: underline;">Sign in</a> as customer to request this booking.
                    </div>
                @endif
            </div>
        </aside>
    </div>
</x-app-layout>
