<x-app-layout>
    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">Gear Rental</p>
            <h1>Rent quality gear for the trail</h1>
            <p>Browse available trekking essentials and reserve what you need before departure.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container">
            <div class="market-card-grid market-card-grid--four">
                @forelse ($gearItems as $item)
                    <article class="market-mini-card">
                        @if ($item->image)
                            <div class="market-card__media" style="min-height: 180px; border-radius: 20px; margin-bottom: 1rem; background-image: linear-gradient(rgba(16, 27, 45, 0.12), rgba(16, 27, 45, 0.25)), url('{{ $item->image }}');"></div>
                        @else
                            <div class="market-mini-card__icon"><i class="fas fa-campground"></i></div>
                        @endif
                        <span class="market-stock {{ $item->available_stock > 5 ? 'is-good' : 'is-low' }}">Available: {{ $item->available_stock }}</span>
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->type }}</p>
                        <p>{{ \Illuminate\Support\Str::limit($item->description, 90) }}</p>
                        <div class="market-mini-card__footer">
                            <strong>${{ number_format($item->daily_price, 0) }}/day</strong>
                            <a href="{{ route('gear.show', $item) }}" class="market-button market-button--ghost">View</a>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No gear is available yet.</p>
                @endforelse
            </div>

            @if ($gearItems->hasPages())
                <div class="catalog-pagination">{{ $gearItems->links() }}</div>
            @endif
        </div>
    </section>
</x-app-layout>
