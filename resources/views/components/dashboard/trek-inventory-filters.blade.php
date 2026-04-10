{{-- TrekInventoryFilters Component --}}
@props(['search', 'difficulty', 'status'])

<form method="GET" action="{{ route('admin.treks.index') }}" class="trek-filters-grid">
    <div class="trek-filters-search">
        <label for="trek-search" class="filter-label">Search</label>
        <div class="trek-filters-search-inner">
            <i class="fas fa-search"></i>
            <input id="trek-search" type="search" name="search" value="{{ $search }}" class="u-input" placeholder="e.g. Everest Base Camp" autocomplete="off" />
        </div>
    </div>
    <div class="trek-filters-select">
        <label for="trek-difficulty" class="filter-label">Difficulty</label>
        <select id="trek-difficulty" name="difficulty" class="u-input">
            <option value="">All Levels</option>
            @foreach (["Easy", "Moderate", "Difficult", "Extreme"] as $option)
                <option value="{{ $option }}" @selected($difficulty === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>
    <div class="trek-filters-select">
        <label for="trek-status" class="filter-label">Status</label>
        <select id="trek-status" name="status" class="u-input">
            <option value="">All Statuses</option>
            @foreach (["Active", "Inactive"] as $option)
                <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>
    <div class="trek-filters-actions">
        <button type="submit" class="u-btn u-btn--primary" style="min-width:110px;">Apply Filters</button>
        <a href="{{ route('admin.treks.index') }}" class="u-btn u-btn--secondary" title="Reset Filters" aria-label="Reset Filters">
            <i class="fas fa-redo-alt"></i>
        </a>
    </div>
</form>
