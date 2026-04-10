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

    <!-- 2️⃣ Cohesive Filter Toolbar -->
    <section class="filter-card" style="padding-bottom: 1.25rem;">
        <x-dashboard.trek-inventory-filters :search="$search" :difficulty="$difficulty" :status="$status" />
    </section>

    <!-- 3️⃣ Modern Trek Listing Grid -->
    <div class="admin-treks-listing">
        @forelse ($treks as $trek)
            <x-dashboard.trek-card :trek="$trek" />
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
