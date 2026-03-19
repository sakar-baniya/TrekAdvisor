<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Management</p>
                <h2 class="admin-page-title">All treks</h2>
            </div>
            <a href="{{ route('admin.treks.create') }}" class="admin-primary-button">
                <i class="fas fa-plus"></i>
                <span>Add New Trek</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search and narrow down trek records</p>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.treks.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search trek name" />
            <select name="difficulty" class="admin-input">
                <option value="">All difficulty</option>
                @foreach (['Easy', 'Moderate', 'Difficult', 'Extreme'] as $option)
                    <option value="{{ $option }}" @selected($difficulty === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['Active', 'Inactive'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-card-list">
        @forelse ($treks as $trek)
            <article class="admin-list-card">
                <div class="admin-list-card__media">
                    <img src="{{ $trek->image ?: 'https://via.placeholder.com/480x320?text=Trek' }}" alt="{{ $trek->title }}">
                </div>
                <div class="admin-list-card__content">
                    <div class="admin-list-card__top">
                        <div>
                            <h3>{{ $trek->title }}</h3>
                            <p>{{ $trek->difficulty }} | {{ $trek->duration_days ?? 'N/A' }} days | Max {{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'Not set' }}</p>
                        </div>
                        <span class="admin-badge {{ $trek->status === 'Active' ? 'is-success' : 'is-muted' }}">{{ $trek->status }}</span>
                    </div>
                    <div class="admin-list-card__meta">
                        <span>Price: ${{ number_format($trek->base_price, 2) }}</span>
                        <span>Departures: {{ $trek->departures_count }}</span>
                        <span>Total bookings: {{ (int) ($trek->total_booked_seats ?? 0) }}</span>
                    </div>
                    <p class="admin-list-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($trek->description), 150) }}</p>
                    <div class="admin-list-card__actions">
                        <a href="{{ route('admin.treks.edit', $trek) }}" class="admin-secondary-button">
                            <i class="fas fa-pen"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('admin.treks.show', $trek) }}" class="admin-secondary-button">
                            <i class="fas fa-eye"></i>
                            <span>View</span>
                        </a>
                        <form action="{{ route('admin.treks.destroy', $trek) }}" method="POST" onsubmit="return confirm('Delete this trek?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-danger-button">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-panel admin-panel--empty">
                <div class="admin-panel__header">
                    <div>
                        <h3>No treks found</h3>
                        <p>Try adjusting the filters or add a new trek.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </section>

    @if ($treks->hasPages())
        <div class="admin-pagination">{{ $treks->links() }}</div>
    @endif
</x-dashboard-layout>
