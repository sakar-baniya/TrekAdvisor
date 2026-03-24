<x-app-layout>
    <section class="detail-hero">
        <div class="container detail-hero__content">
            <h1>{{ $gearItem->name }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-layer-group"></i> {{ $gearItem->type }}</span>
                <span><i class="fas fa-boxes"></i> {{ $gearItem->available_stock }} available</span>
                <span><i class="fas fa-tag"></i> ${{ number_format($gearItem->daily_price, 0) }}/day</span>
                <span><i class="fas fa-star"></i> {{ number_format($gearItem->reviews->avg('rating') ?? 4.7, 1) }} ({{ $gearItem->reviews->count() }})</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            @if ($gearItem->image)
                <article class="detail-panel">
                    <div class="market-card__media" style="min-height: 300px; border-radius: 24px; background-image: linear-gradient(rgba(16, 27, 45, 0.10), rgba(16, 27, 45, 0.28)), url('{{ $gearItem->image }}');"></div>
                </article>
            @endif

            <article class="detail-panel">
                <h2>Gear Overview</h2>
                <p>{{ $gearItem->description ?: ($gearItem->type . ' rental item prepared for trekking support and trip readiness.') }}</p>
            </article>
        </section>

        <aside class="detail-sidebar">
            <div class="detail-booking-card">
                <div class="detail-price-block">
                    <span>Daily rental rate</span>
                    <strong>${{ number_format($gearItem->daily_price, 0) }}</strong>
                    <small>per day</small>
                </div>
                <div class="detail-discount-box">
                    <strong>Rental notes</strong>
                    <ul>
                        <li>Category: {{ $gearItem->type }}</li>
                        <li>{{ $gearItem->available_stock }} currently available for rental</li>
                        <li>{{ $gearItem->total_stock }} total items in inventory</li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
