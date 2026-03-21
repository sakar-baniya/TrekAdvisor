<x-app-layout>
    <section class="detail-hero">
        <div class="container detail-hero__content">
            <div class="detail-breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('gear.index') }}">Gear</a> / <span>{{ $gearItem->name }}</span></div>
            <h1>{{ $gearItem->name }}</h1>
            <div class="detail-hero__stats">
                <span><i class="fas fa-boxes"></i> {{ $gearItem->available_stock }} available</span>
                <span><i class="fas fa-tag"></i> ${{ number_format($gearItem->daily_price, 0) }}/day</span>
                <span><i class="fas fa-star"></i> {{ number_format($gearItem->reviews->avg('rating') ?? 4.7, 1) }} ({{ $gearItem->reviews->count() }})</span>
            </div>
        </div>
    </section>

    <div class="container detail-grid">
        <section class="detail-main">
            <article class="detail-panel">
                <h2>Gear Overview</h2>
                <p>{{ $gearItem->type }} rental item prepared for trekking support and trip readiness.</p>
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
                        <li>Availability shown in real time from stock counts</li>
                        <li>Great for completing a trek packing list</li>
                        <li>Simple pricing with daily totals</li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
