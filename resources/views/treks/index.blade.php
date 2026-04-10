<x-app-layout>
    <section class="catalog-hero">
        <div class="catalog-hero-overlay"></div>
        <div class="container">
            <p class="market-kicker">Trek Collection</p>
            <h1>Discover Amazing Treks</h1>
            <p>Explore the Himalayas with curated routes, flexible departures, and customer reviews.</p>
        </div>
    </section>

    <div class="container catalog-main">
        <div class="catalog-filter-bar">
            <div class="horizontal-filter-form">
                <div class="filter-row">
                    <div class="filter-group filter-search">
                        <i class="fas fa-search"></i>
                        <input 
                            type="text" 
                            id="filter-search" 
                            value="{{ request('search') }}" 
                            placeholder="Search treks..." 
                            class="minimal-input"
                        >
                    </div>
                    
                    <div class="filter-group">
                        <select id="filter-difficulty" class="minimal-select">
                            <option value="">All Difficulty</option>
                            <option value="easy" {{ strtolower(request('difficulty')) == 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="moderate" {{ strtolower(request('difficulty')) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="difficult" {{ strtolower(request('difficulty')) == 'difficult' ? 'selected' : '' }}>Difficult</option>
                            <option value="extreme" {{ strtolower(request('difficulty')) == 'extreme' ? 'selected' : '' }}>Extreme</option>
                        </select>
                    </div>

                    <div class="filter-group filter-price">
                        <span class="price-label">Price:</span>
                        <input 
                            type="number" 
                            id="filter-min-price" 
                            value="{{ request('min_price') }}" 
                            min="0" 
                            class="minimal-input" 
                            placeholder="Min"
                        >
                        <span class="price-dash">—</span>
                        <input 
                            type="number" 
                            id="filter-max-price" 
                            value="{{ request('max_price') }}" 
                            min="0" 
                            class="minimal-input" 
                            placeholder="Max"
                        >
                    </div>

                    <div class="filter-actions-inline">
                        <button type="button" id="btn-apply-filters" class="btn-filter-apply">Apply</button>
                        <button type="button" id="btn-clear-filters" class="close-btn" aria-label="Clear filters" style="display: {{ request()->anyFilled(['search', 'difficulty', 'min_price', 'max_price']) ? 'flex' : 'none' }}">×</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="catalog-content-full">
            <div class="catalog-results-header">
                <div class="results-left">
                    <span class="results-count"><strong id="results-count">{{ $treks->total() }}</strong> Treks found</span>
                </div>
                <div class="results-right">
                    <div class="sort-dropdown">
                        <label for="sort-select">Sort by:</label>
                        <select id="sort-select" class="minimal-select sort-select">
                            <option value="popularity" {{ request('sort', 'popularity') == 'popularity' ? 'selected' : '' }}>Most Popular</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="duration" {{ request('sort') == 'duration' ? 'selected' : '' }}>Duration</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="trek-grid-container" class="market-card-grid market-card-grid--three">
                @foreach($treks as $trek)
                    <article class="market-card market-card--trek">
                        <div class="market-card__media" @if($trek->image) style="background-image: linear-gradient(rgba(6, 78, 89, 0.25), rgba(15, 23, 42, 0.5)), url('{{ $trek->image }}');" @endif>
                    <span class="difficulty-badge badge--{{ strtolower($trek->difficulty ?? 'moderate') }}">{{ $trek->difficulty ?? 'Moderate' }}</span>
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
                @endforeach
            </div>

            <div id="trek-empty" class="empty-note" style="display: {{ $treks->isEmpty() ? 'block' : 'none' }}">
                No treks matched your filters.
            </div>

            <div id="trek-loading" class="catalog-loading" style="display: none">
                <div class="loading-spinner"></div>
                <p>Loading treks...</p>
            </div>

            <div id="trek-pagination" class="catalog-pagination trek-pagination-wrap">
                {{ $treks->links('components.pagination') }}
            </div>
        </section>
    </div>
</x-app-layout>

@push('styles')
<style>
.catalog-loading {
    text-align: center;
    padding: 3rem;
    color: #64748B;
}
.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #E2E8F0;
    border-top-color: #0F172A;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 1rem;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.market-card__media {
    position: relative;
}
.market-card-grid {
    transition: opacity 0.3s ease;
}
</style>
@endpush

<script>
(function() {
    const API_URL = '/api/v1/treks';
    let currentPage = {{ $treks->currentPage() }};
    
    const elements = {
        search: document.getElementById('filter-search'),
        difficulty: document.getElementById('filter-difficulty'),
        minPrice: document.getElementById('filter-min-price'),
        maxPrice: document.getElementById('filter-max-price'),
        sort: document.getElementById('sort-select'),
        applyBtn: document.getElementById('btn-apply-filters'),
        clearBtn: document.getElementById('btn-clear-filters'),
        container: document.getElementById('trek-grid-container'),
        empty: document.getElementById('trek-empty'),
        loading: document.getElementById('trek-loading'),
        count: document.getElementById('results-count')
    };

    function hasActiveFilters() {
        return elements.search.value || elements.difficulty.value || elements.minPrice.value || elements.maxPrice.value;
    }

    function updateClearButton() {
        if (elements.clearBtn) {
            elements.clearBtn.style.display = hasActiveFilters() ? 'flex' : 'none';
        }
    }

    function buildQuery(params) {
        const query = new URLSearchParams();
        if (params.search) query.set('search', params.search);
        if (params.difficulty) query.set('difficulty', params.difficulty);
        if (params.minPrice) query.set('min_price', params.minPrice);
        if (params.maxPrice) query.set('max_price', params.maxPrice);
        if (params.sort) query.set('sort', params.sort);
        query.set('page', params.page || 1);
        return query.toString();
    }

    function renderTrek(trek) {
        const rating = trek.reviews_avg_rating ? parseFloat(trek.reviews_avg_rating).toFixed(1) : 'New';
        const reviews = trek.reviews_count || 0;
        const duration = trek.duration_days || 'Flexible';
        const badgeClass = (trek.difficulty || 'moderate').toLowerCase();
        
        // Use the storage-ready image attribute if available, or fall back to relations
        let imageUrl = trek.image || '';
        if (!imageUrl && trek.images && trek.images.length > 0) {
            const primary = trek.images.find(img => img.is_primary) || trek.images[0];
            const path = primary.path;
            imageUrl = (path.startsWith('http') || path.startsWith('/storage/')) ? path : `/storage/${path}`;
        }
        
        const imageStyle = imageUrl ? `background-image: linear-gradient(rgba(6, 78, 89, 0.25), rgba(15, 23, 42, 0.5)), url('${imageUrl}');` : '';
        
        return `<article class="market-card market-card--trek">
            <div class="market-card__media" style="${imageStyle}">
                <span class="difficulty-badge badge--${badgeClass}">${trek.difficulty || 'Moderate'}</span>
            </div>
            <div class="market-card__body">
                <h3>${trek.title}</h3>
                <div class="market-card__trip-meta">
                    <span>${duration} Days</span>
                    <span class="market-card__reviews">
                        <i class="fas fa-star"></i>
                        ${rating}
                        <em>${reviews} Reviews</em>
                    </span>
                </div>
                <div class="market-card__footer">
                    <div class="market-card__price">
                        <strong>$${Number(trek.base_price).toLocaleString()}</strong>
                        <span>per person</span>
                    </div>
                    <a href="/treks/${trek.slug}" class="market-button">View Details</a>
                </div>
            </div>
        </article>`;
    }

    async function fetchTreks() {
        if (!elements.applyBtn || !elements.container) return;
        
        elements.applyBtn.disabled = true;
        elements.applyBtn.textContent = 'Loading...';
        elements.container.style.opacity = '0.5';
        
        const filters = {
            search: elements.search?.value || '',
            difficulty: elements.difficulty?.value || '',
            minPrice: elements.minPrice?.value || '',
            maxPrice: elements.maxPrice?.value || '',
            sort: elements.sort?.value || 'popularity',
            page: currentPage
        };
        
        const url = `${API_URL}?${buildQuery(filters)}`;
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.data && data.data.length > 0) {
                elements.container.innerHTML = data.data.map(renderTrek).join('');
                elements.container.style.display = 'grid';
                if (elements.empty) elements.empty.style.display = 'none';
            } else {
                elements.container.style.display = 'none';
                if (elements.empty) elements.empty.style.display = 'block';
            }
            
            if (elements.count && data.meta) {
                elements.count.textContent = data.meta.total;
            }
        } catch (error) {
            console.error('Error fetching treks:', error);
        } finally {
            elements.applyBtn.disabled = false;
            elements.applyBtn.textContent = 'Apply';
            elements.container.style.opacity = '1';
        }
    }

    function applyFilters() {
        currentPage = 1;
        
        // Update URL to reflect current filters so browser back/forward and standard pagination work
        const filters = {
            search: elements.search?.value || '',
            difficulty: elements.difficulty?.value || '',
            minPrice: elements.minPrice?.value || '',
            maxPrice: elements.maxPrice?.value || '',
            sort: elements.sort?.value || 'popularity'
        };
        const query = buildQuery(filters);
        window.history.pushState(filters, '', `/treks?${query}`);
        
        fetchTreks();
    }

    function clearFilters() {
        if (elements.search) elements.search.value = '';
        if (elements.difficulty) elements.difficulty.value = '';
        if (elements.minPrice) elements.minPrice.value = '';
        if (elements.maxPrice) elements.maxPrice.value = '';
        if (elements.sort) elements.sort.value = 'popularity';
        currentPage = 1;
        updateClearButton();
        
        window.history.pushState({}, '', '/treks');
        
        fetchTreks();
    }

    if (elements.applyBtn) {
        elements.applyBtn.addEventListener('click', applyFilters);
    }
    
    if (elements.clearBtn) {
        elements.clearBtn.addEventListener('click', clearFilters);
    }
    
    if (elements.difficulty) {
        elements.difficulty.addEventListener('change', () => { currentPage = 1; fetchTreks(); });
    }
    
    if (elements.sort) {
        elements.sort.addEventListener('change', () => { currentPage = 1; fetchTreks(); });
    }
    
    [elements.search, elements.minPrice, elements.maxPrice].forEach(el => {
        if (el) {
            el.addEventListener('input', () => {
                updateClearButton();
            });
            el.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });
        }
    });
})();
</script>