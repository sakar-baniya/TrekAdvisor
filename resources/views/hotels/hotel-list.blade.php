<x-app-layout>
    <section class="catalog-hero catalog-hero--hotels">
        <div class="catalog-hero-overlay"></div>
        <div class="container">
            <p class="market-kicker">Stay Collection</p>
            <h1>Find Your Perfect Stay</h1>
            <p>Browse premium hotels and lodges across the Himalayan regions, curated for comfort and authenticity.</p>
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
                            placeholder="Search hotels, cities..." 
                            class="minimal-input"
                        >
                    </div>
                    
                    <div class="filter-group">
                        <i class="fas fa-map-marker-alt" style="left: 1rem; position: absolute; color: #94A3B8; font-size: 0.85rem; pointer-events: none; z-index: 1;"></i>
                        <input 
                            type="text" 
                            id="filter-location" 
                            value="{{ request('location') }}" 
                            placeholder="Location..." 
                            class="minimal-input"
                            style="padding-left: 2.75rem;"
                        >
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
                        <button type="button" id="btn-clear-filters" class="close-btn" aria-label="Clear filters" style="display: {{ request()->anyFilled(['search', 'location', 'min_price', 'max_price']) ? 'flex' : 'none' }}">×</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="catalog-content-full">
            <div class="catalog-results-header">
                <div class="results-left">
                    <span class="results-count"><strong id="results-count">{{ $hotels->total() }}</strong> Hotels found</span>
                </div>
                <div class="results-right">
                    <div class="sort-dropdown">
                        <label for="sort-select">Sort by:</label>
                        <select id="sort-select" class="minimal-select sort-select">
                            <option value="popularity" {{ request('sort', 'popularity') == 'popularity' ? 'selected' : '' }}>Most Popular</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="hotel-grid-container" class="market-card-grid market-card-grid--three">
                @foreach($hotels as $hotel)
                    <article class="market-card market-card--hotel">
                        <div class="market-card__media" @if($hotel->image) style="background-image: linear-gradient(rgba(6, 78, 89, 0.2), rgba(15, 23, 42, 0.4)), url('{{ $hotel->image }}');" @endif>
                            <span class="difficulty-badge badge--extreme">{{ $hotel->location }}</span>
                        </div>
                        <div class="market-card__body">
                            <h3>{{ $hotel->name }}</h3>
                            <div class="market-card__trip-meta">
                                <span><i class="fas fa-bed"></i> Comfort & Style</span>
                                <span class="market-card__reviews">
                                    <i class="fas fa-star"></i>
                                    {{ $hotel->reviews_avg_rating ? number_format($hotel->reviews_avg_rating, 1) : 'New' }}
                                    <em>{{ $hotel->reviews_count }} Reviews</em>
                                </span>
                            </div>
                            <div class="market-card__footer">
                                <div class="market-card__price">
                                    <strong>NPR {{ number_format($hotel->rooms_min_price_per_night ?? 0, 0) }}</strong>
                                    <span>per night</span>
                                </div>
                                <a href="{{ route('hotels.show', $hotel) }}" class="market-button">View Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div id="hotel-empty" class="empty-note" style="display: {{ $hotels->isEmpty() ? 'block' : 'none' }}">
                No hotels matched your filters.
            </div>

            <div id="hotel-loading" class="catalog-loading" style="display: none">
                <div class="loading-spinner"></div>
                <p>Finding hotels...</p>
            </div>

            <div id="hotel-pagination" class="catalog-pagination">
                {{ $hotels->links() }}
            </div>
        </section>
    </div>

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
    </style>
    @endpush

    @push('scripts')
    <script>
    (() => {
        const API_URL = '/api/v1/hotels';
        const elements = {
            search: document.getElementById('filter-search'),
            location: document.getElementById('filter-location'),
            minPrice: document.getElementById('filter-min-price'),
            maxPrice: document.getElementById('filter-max-price'),
            sort: document.getElementById('sort-select'),
            applyBtn: document.getElementById('btn-apply-filters'),
            clearBtn: document.getElementById('btn-clear-filters'),
            container: document.getElementById('hotel-grid-container'),
            empty: document.getElementById('hotel-empty'),
            loading: document.getElementById('hotel-loading'),
            pagination: document.getElementById('hotel-pagination'),
            count: document.getElementById('results-count')
        };

        let currentPage = {{ $hotels->currentPage() }};

        function buildQuery(params) {
            const query = new URLSearchParams();
            Object.entries(params).forEach(([key, value]) => {
                if (value) query.set(key, value);
            });
            return query.toString();
        }

        function hasActiveFilters() {
            return elements.search?.value || elements.location?.value || elements.minPrice?.value || elements.maxPrice?.value;
        }

        function updateClearButton() {
            if (elements.clearBtn) {
                elements.clearBtn.style.display = hasActiveFilters() ? 'flex' : 'none';
            }
        }

        function renderHotel(hotel) {
            const rating = hotel.reviews_avg_rating ? parseFloat(hotel.reviews_avg_rating).toFixed(1) : 'New';
            const reviews = hotel.reviews_count || 0;
            const price = hotel.rooms_min_price_per_night || 0;
            
            // Image handling
            let imageUrl = hotel.image || '';
            if (!imageUrl && hotel.images && hotel.images.length > 0) {
                const primary = hotel.images.find(img => img.is_primary) || hotel.images[0];
                const path = primary.path;
                imageUrl = (path.startsWith('http') || path.startsWith('/storage/')) ? path : `/storage/${path}`;
            }
            
            const imageStyle = imageUrl ? `background-image: linear-gradient(rgba(6, 78, 89, 0.2), rgba(15, 23, 42, 0.4)), url('${imageUrl}');` : '';
            
            return `<article class="market-card market-card--hotel">
                <div class="market-card__media" style="${imageStyle}">
                    <span class="difficulty-badge badge--extreme">${hotel.location}</span>
                </div>
                <div class="market-card__body">
                    <h3>${hotel.name}</h3>
                    <div class="market-card__trip-meta">
                        <span><i class="fas fa-bed"></i> Comfort & Style</span>
                        <span class="market-card__reviews">
                            <i class="fas fa-star"></i>
                            ${rating}
                            <em>${reviews} Reviews</em>
                        </span>
                    </div>
                    <div class="market-card__footer">
                        <div class="market-card__price">
                            <strong>NPR ${Number(price).toLocaleString()}</strong>
                            <span>per night</span>
                        </div>
                        <a href="/hotels/${hotel.slug || hotel.id}" class="market-button">View Details</a>
                    </div>
                </div>
            </article>`;
        }

        async function fetchHotels() {
            if (!elements.container) return;
            
            if (elements.loading) elements.loading.style.display = 'block';
            elements.container.style.opacity = '0.5';
            
            const filters = {
                search: elements.search?.value || '',
                location: elements.location?.value || '',
                min_price: elements.minPrice?.value || '',
                max_price: elements.maxPrice?.value || '',
                sort: elements.sort?.value || 'popularity',
                page: currentPage
            };
            
            const url = `${API_URL}?${buildQuery(filters)}`;
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.data && data.data.length > 0) {
                    elements.container.innerHTML = data.data.map(renderHotel).join('');
                    elements.container.style.display = 'grid';
                    if (elements.empty) elements.empty.style.display = 'none';
                } else {
                    elements.container.style.display = 'none';
                    if (elements.empty) elements.empty.style.display = 'block';
                }
                
                if (elements.count && data.meta) {
                    elements.count.textContent = data.meta.total;
                }

                // Hide pagination on AJAX search for now or update it
                if (elements.pagination) {
                    elements.pagination.style.display = 'none';
                }

            } catch (error) {
                console.error('Error fetching hotels:', error);
            } finally {
                if (elements.loading) elements.loading.style.display = 'none';
                elements.container.style.opacity = '1';
            }
        }

        function applyFilters() {
            currentPage = 1;
            const filters = {
                search: elements.search?.value || '',
                location: elements.location?.value || '',
                min_price: elements.minPrice?.value || '',
                max_price: elements.maxPrice?.value || '',
                sort: elements.sort?.value || 'popularity'
            };
            const query = buildQuery(filters);
            window.history.pushState(filters, '', `/hotels?${query}`);
            fetchHotels();
        }

        function clearFilters() {
            if (elements.search) elements.search.value = '';
            if (elements.location) elements.location.value = '';
            if (elements.minPrice) elements.minPrice.value = '';
            if (elements.maxPrice) elements.maxPrice.value = '';
            if (elements.sort) elements.sort.value = 'popularity';
            currentPage = 1;
            updateClearButton();
            window.history.pushState({}, '', '/hotels');
            fetchHotels();
        }

        if (elements.applyBtn) elements.applyBtn.addEventListener('click', applyFilters);
        if (elements.clearBtn) elements.clearBtn.addEventListener('click', clearFilters);
        if (elements.sort) elements.sort.addEventListener('change', () => { currentPage = 1; fetchHotels(); });

        [elements.search, elements.location, elements.minPrice, elements.maxPrice].forEach(el => {
            if (el) {
                el.addEventListener('input', updateClearButton);
                el.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') applyFilters();
                });
            }
        });
    })();
    </script>
    @endpush
</x-app-layout>
