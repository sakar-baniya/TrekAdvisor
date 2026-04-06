<x-app-layout>
    <section class="trek-hero">
        <div class="trek-hero-overlay"></div>
        <div class="container">
            <p class="market-kicker">Trek Collection</p>
            <h1>Discover Amazing Treks</h1>
            <p>Explore the Himalayas with curated routes, flexible departures, and customer reviews.</p>
        </div>
    </section>

    <div class="container catalog-main">
        <div class="catalog-filter-bar flex-filters">
            <form action="{{ route('treks.index') }}" method="GET" class="horizontal-filter-form">
                <div class="filter-group filter-search">
                    <i class="fas fa-search"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Search treks..." class="minimal-input">
                </div>
                
                <div class="filter-group">
                    <select name="difficulty" class="minimal-select">
                        <option value="">All Difficulty</option>
                        <option value="Easy" {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                        <option value="Moderate" {{ request('difficulty') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="Difficult" {{ request('difficulty') == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                        <option value="Extreme" {{ request('difficulty') == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                    </select>
                </div>

                <div class="filter-group filter-price">
                    <input name="min_price" value="{{ request('min_price') }}" type="number" min="0" class="minimal-input" placeholder="Min $">
                    <span class="price-dash">—</span>
                    <input name="max_price" value="{{ request('max_price') }}" type="number" min="0" class="minimal-input" placeholder="Max $">
                </div>

                <div class="filter-actions-inline">
                    <button type="submit" class="btn-filter-apply">Apply</button>
                    @if(request()->anyFilled(['search', 'difficulty', 'min_price', 'max_price']))
                        <a href="{{ route('treks.index') }}" class="btn-filter-reset" title="Reset">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <section class="catalog-content-full">
            <div class="catalog-results-header">
                <div class="results-left">
                    <span class="results-count"><strong>{{ $treks->total() }}</strong> Treks found</span>
                </div>
                <div class="results-right">
                    <div class="sort-selector">
                        <span>Sort by:</span>
                        <strong>Popularity</strong>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <div class="market-card-grid market-card-grid--three">
                @forelse($treks as $trek)
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($trek->image) style="background-image: linear-gradient(rgba(6, 78, 89, 0.25), rgba(15, 23, 42, 0.5)), url('{{ $trek->image }}');" @endif>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $trek->title }}</h3>
                            <div class="market-card__trip-meta">
                                <span>{{ $trek->duration_days ?? 'Flexible' }} Days</span>
                                <span class="market-card__reviews">
                                    <i class="fas fa-star"></i>
                                    {{ $trek->reviews_avg_rating ? number_format($trek->reviews_avg_rating, 1) : 'New' }}
                                    <em>{{ $trek->reviews_count }} Reviews</em>
                                </span>
                            </div>
                            <div class="market-card__footer">
                                <div class="market-card__price">
                                    <strong>${{ number_format($trek->base_price, 0) }}</strong>
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
