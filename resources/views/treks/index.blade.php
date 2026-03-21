<x-app-layout>
    <section class="catalog-hero catalog-hero--treks">
        <div class="container">
            <p class="market-kicker">Trek Collection</p>
            <h1>Discover Amazing Treks</h1>
            <p>Explore the Himalayas with curated routes, flexible departures, and customer reviews.</p>
        </div>
    </section>

    <div class="container catalog-layout">
        <aside class="catalog-sidebar">
            <div class="catalog-panel">
                <h3>Filters</h3>
                <form action="{{ route('treks.index') }}" method="GET" class="catalog-filter-form">
                    <label>
                        <span>Search</span>
                        <input name="search" value="{{ request('search') }}" placeholder="Search treks" class="market-input">
                    </label>
                    <label>
                        <span>Difficulty</span>
                        <select name="difficulty" class="market-input">
                            <option value="">All Difficulty</option>
                            <option value="Easy" {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                            <option value="Moderate" {{ request('difficulty') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="Difficult" {{ request('difficulty') == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                            <option value="Extreme" {{ request('difficulty') == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                        </select>
                    </label>
                    <label>
                        <span>Min Price</span>
                        <input name="min_price" value="{{ request('min_price') }}" type="number" min="0" step="1" class="market-input" placeholder="$0">
                    </label>
                    <label>
                        <span>Max Price</span>
                        <input name="max_price" value="{{ request('max_price') }}" type="number" min="0" step="1" class="market-input" placeholder="$3000">
                    </label>
                    <button type="submit" class="market-search-btn market-search-btn--full">Apply Filters</button>
                    <a href="{{ route('treks.index') }}" class="catalog-reset-link">Reset Filters</a>
                </form>
            </div>
        </aside>

        <section class="catalog-content">
            <div class="catalog-toolbar">
                <p>Showing {{ $treks->count() }} of {{ $treks->total() }} treks</p>
                <div class="catalog-toolbar__actions">
                    <span class="catalog-toolbar__chip">Most Popular</span>
                    <span class="catalog-toolbar__chip">Premium Trails</span>
                </div>
            </div>

            <div class="market-card-grid market-card-grid--three">
                @forelse($treks as $trek)
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($trek->image) style="background-image: linear-gradient(rgba(6, 78, 89, 0.25), rgba(15, 23, 42, 0.5)), url('{{ $trek->image }}');" @endif>
                            <span class="market-badge market-badge--label {{ strtolower($trek->difficulty) === 'easy' ? 'market-badge--green' : (strtolower($trek->difficulty) === 'moderate' ? 'market-badge--orange' : 'market-badge--red') }}">
                                <i class="fas fa-users"></i>
                                {{ strtolower($trek->difficulty) === 'easy' ? 'Easy Route' : (strtolower($trek->difficulty) === 'moderate' ? 'Group Tours' : 'High Trail') }}
                            </span>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $trek->title }}</h3>
                            <div class="market-card__trip-meta">
                                <span>{{ $trek->duration_days ?? 'Flexible' }} Days</span>
                                <span class="market-card__reviews">
                                    <i class="fas fa-star"></i>
                                    {{ number_format($trek->reviews->avg('rating') ?? 4.8, 1) }}
                                    <em>{{ $trek->reviews->count() ?: 12 }} Reviews</em>
                                </span>
                            </div>
                            <div class="market-card__footer">
                                <div class="market-card__price">
                                    <strong>US ${{ number_format($trek->base_price, 0) }}</strong>
                                    <span>per person</span>
                                </div>
                                <a href="{{ route('treks.show', $trek->slug) }}" class="market-button">View Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="empty-note">No treks matched your filters.</p>
                @endforelse
            </div>

            <div class="catalog-pagination">{{ $treks->links() }}</div>
        </section>
    </div>
</x-app-layout>
