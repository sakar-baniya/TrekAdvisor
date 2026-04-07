<x-dashboard-layout>
    <!-- 1️⃣ Redesigned Page Header -->
    <div class="page-header">
        <div>
            <h1>Trek Inventory</h1>
            <p>Monitor and manage all trekking expeditions across the marketplace.</p>
        </div>
        <div>
            <a href="{{ route('admin.treks.create') }}" class="u-btn u-btn--primary">
                <i class="fas fa-plus"></i> New Trek Listing
            </a>
        </div>
    </div>

    <!-- 2️⃣ Refined Filter Toolbar - Horizontal SaaS Layout -->
    <section class="filter-card">
        <form method="GET" action="{{ route('admin.treks.index') }}" class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Search</label>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 0.85rem; top: 14px; opacity: 0.3; font-size: 0.8rem;"></i>
                    <input type="search" name="search" value="{{ $search }}" class="u-input w-100" placeholder="e.g. Everest Base Camp..." style="padding-left: 2.5rem;">
                </div>
            </div>

            <div class="filter-group" style="min-width: 140px;">
                <label class="filter-label">Difficulty</label>
                <select name="difficulty" class="u-input w-100">
                    <option value="">All Levels</option>
                    @foreach (['Easy', 'Moderate', 'Difficult', 'Extreme'] as $option)
                        <option value="{{ $option }}" @selected($difficulty === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group" style="min-width: 140px;">
                <label class="filter-label">Status</label>
                <select name="status" class="u-input w-100">
                    <option value="">All Statuses</option>
                    @foreach (['Active', 'Inactive'] as $option)
                        <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="u-btn u-btn--primary px-4">
                    Apply Filters
                </button>
                <a href="{{ route('admin.treks.index') }}" class="u-btn u-btn--secondary" title="Reset Filters">
                    <i class="fas fa-redo-alt"></i>
                </a>
            </div>
        </form>
    </section>

    <!-- 3️⃣ Modern Trek Listing Grid -->
    <div class="admin-treks-listing">
        @forelse ($treks as $trek)
            <article class="trek-row">
                <div class="trek-row__media">
                    <img src="{{ $trek->image ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $trek->title }}">
                    <div style="position: absolute; top: 1rem; left: 1rem;">
                        <span class="u-badge {{ $trek->status === 'Active' ? 'u-badge--success' : 'u-badge--muted' }}">
                            {{ $trek->status }}
                        </span>
                    </div>
                </div>
                
                <div class="trek-row__content">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h3 class="trek-row__title">{{ $trek->title }}</h3>
                        <div class="d-flex gap-2">
                             <a href="{{ route('admin.treks.edit', $trek) }}" class="u-btn u-btn--secondary py-1 px-3" style="font-size: 0.75rem;">
                                <i class="fas fa-edit opacity-50"></i> Edit
                            </a>
                            <form action="{{ route('admin.treks.destroy', $trek) }}" method="POST" onsubmit="return confirm('Archive this trek listing?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="u-btn u-btn--danger py-1 px-3" style="font-size: 0.75rem;">
                                    <i class="fas fa-trash-alt opacity-50"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="trek-row__meta">
                        <span><i class="fas fa-mountain me-2"></i> {{ $trek->difficulty }}</span>
                        <span><i class="fas fa-clock me-2"></i> {{ $trek->duration_days ?? 'N/A' }} Days</span>
                        <span><i class="fas fa-location-arrow me-2"></i> {{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'N/A' }}</span>
                    </div>

                    <div class="trek-row__stats">
                        <div class="stat-item">
                            <span class="stat-item__label">Base Price</span>
                            <span class="stat-item__value">${{ number_format($trek->base_price, 0) }}</span>
                        </div>
                        <div class="stat-item" style="border-left: 1px solid var(--u-border-soft); padding-left: 2rem;">
                            <span class="stat-item__label">Departures</span>
                            <span class="stat-item__value">{{ $trek->departures_count }} Active</span>
                        </div>
                        <div class="stat-item" style="border-left: 1px solid var(--u-border-soft); padding-left: 2rem;">
                            <span class="stat-item__label">Total Bookings</span>
                            <span class="stat-item__value">{{ (int) $trek->total_booked_seats }}</span>
                        </div>
                    </div>

                    <div class="trek-row__actions">
                        <a href="{{ route('admin.treks.show', $trek) }}" class="text-primary fw-bold text-decoration-none" style="font-size: 0.85rem;">
                            View Analytical Details &rarr;
                        </a>
                        <a href="{{ route('admin.departures.index', ['trek_id' => $trek->id]) }}" class="u-btn u-btn--primary" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-calendar-alt"></i> Manage Schedule
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="card p-5 text-center shadow-sm" style="border-radius: 20px;">
                <div style="font-size: 3rem; opacity: 0.1; color: var(--u-navy); margin-bottom: 1.5rem;">
                    <i class="fas fa-mountain-sun"></i>
                </div>
                <h3 class="fw-bold">No treks found</h3>
                <p class="text-muted">Adjust your filters or add a new trek to get started.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.treks.index') }}" class="u-btn u-btn--secondary">Clear All Filters</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- 4️⃣ Polished Pagination Footer -->
    @if ($treks->hasPages())
        <footer class="pagination-wrap">
            <div class="pagination-info">
                Showing {{ $treks->firstItem() }} to {{ $treks->lastItem() }} of <strong>{{ $treks->total() }}</strong> total treks
            </div>
            
            <nav>
                <ul class="v-pagination">
                    {{-- Previous Page Link --}}
                    @if ($treks->onFirstPage())
                        <li class="v-pagination__item is-disabled"><i class="fas fa-chevron-left"></i></li>
                    @else
                        <a href="{{ $treks->previousPageUrl() }}" class="v-pagination__item"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($treks->getUrlRange(max(1, $treks->currentPage() - 2), min($treks->lastPage(), $treks->currentPage() + 2)) as $page => $url)
                        @if ($page == $treks->currentPage())
                            <li class="v-pagination__item is-active">{{ $page }}</li>
                        @else
                            <a href="{{ $url }}" class="v-pagination__item">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($treks->hasMorePages())
                        <a href="{{ $treks->nextPageUrl() }}" class="v-pagination__item"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <li class="v-pagination__item is-disabled"><i class="fas fa-chevron-right"></i></li>
                    @endif
                </ul>
            </nav>
        </footer>
    @endif
</x-dashboard-layout>
