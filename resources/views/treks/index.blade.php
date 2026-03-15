<x-app-layout>
    <div class="treks-page">
        <div class="container">
            <!-- Page Title -->
            <header class="treks-header">
                <h1 class="treks-title">
                    Find Your Next <span class="treks-title-accent">Adventure</span>
                </h1>
                <p class="treks-subtitle">
                    Choose from our hand-picked list of amazing treks in the Himalayas.
                </p>
            </header>

            <!-- Filters -->
            <div class="treks-filters">
                <form action="{{ route('treks.index') }}" method="GET" class="filters-grid">
                    <div class="filter-field">
                        <input name="search" value="{{ request('search') }}" placeholder="Search treks by name or description" class="filter-input">
                        <div class="filter-icon">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>

                    <div class="filter-field">
                        <select name="difficulty" class="filter-select">
                            <option value="">All Difficulty</option>
                            <option value="Easy" {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                            <option value="Moderate" {{ request('difficulty') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="Difficult" {{ request('difficulty') == 'Difficult' ? 'selected' : '' }}>Difficult</option>
                            <option value="Extreme" {{ request('difficulty') == 'Extreme' ? 'selected' : '' }}>Extreme</option>
                        </select>
                        <div class="filter-icon right">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <input name="min_price" value="{{ request('min_price') }}" type="number" min="0" step="1" placeholder="Min price" class="filter-input">
                    <input name="max_price" value="{{ request('max_price') }}" type="number" min="0" step="1" placeholder="Max price" class="filter-input">

                    <div class="filter-actions">
                        <button type="submit" class="filter-apply">
                            Apply
                        </button>
                        @if(request()->filled('search') || request()->filled('difficulty') || request()->filled('min_price') || request()->filled('max_price'))
                            <a href="{{ route('treks.index') }}" class="filter-clear">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Trek List -->
            <div class="trek-grid">
                @foreach($treks as $trek)
                    <div class="trek-card">
                        <!-- Image -->
                        <div class="trek-image">
                            <img src="{{ $trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=640&q=80' }}" alt="{{ $trek->title }}">
                            <div class="trek-overlay"></div>
                            @php
                                $difficultyClass = [
                                    'easy' => 'trek-badge easy',
                                    'moderate' => 'trek-badge moderate',
                                    'difficult' => 'trek-badge difficult',
                                    'extreme' => 'trek-badge extreme'
                                ][strtolower($trek->difficulty)] ?? 'trek-badge neutral';
                            @endphp
                            <div class="{{ $difficultyClass }}">
                                {{ strtoupper($trek->difficulty) }}
                            </div>
                        </div>

                        <!-- Trek Details -->
                        <div class="trek-body">
                            <h3 class="trek-name">
                                {{ $trek->title }}
                            </h3>

                            <div class="trek-price-row">
                                <span class="trek-price-label">Starting From</span>
                                <span class="trek-price">${{ number_format($trek->base_price) }}</span>
                            </div>

                            <p class="trek-description">
                                {{ Str::limit($trek->description, 110) }}
                            </p>

                            <a href="{{ route('treks.show', $trek->slug) }}" class="trek-cta">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- System Pagination -->
            <div class="treks-pagination">
                {{ $treks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
