<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Operations</p>
                <h2 class="admin-page-title">Departures</h2>
            </div>
            <a href="{{ route('admin.departures.create') }}" class="admin-primary-button">
                <i class="fas fa-plus"></i>
                <span>Add Departure</span>
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
                <p>Narrow departures by trek, status, or month</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.departures.index') }}" class="admin-filter-grid">
            <select name="trek_id" class="admin-input">
                <option value="">All treks</option>
                @foreach ($treks as $trek)
                    <option value="{{ $trek->id }}" @selected($selectedTrek == $trek->id)>{{ $trek->title }}</option>
                @endforeach
            </select>

            <select name="status" class="admin-input">
                <option value="">All status</option>
                @foreach (['available' => 'Available', 'full' => 'Full', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="month" class="admin-input">
                <option value="">All months</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" @selected((string) $selectedMonth === (string) $month)>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                @endforeach
            </select>

            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-card-list">
        @forelse ($departures as $departure)
            <article class="admin-list-card admin-list-card--compact">
                <div class="admin-list-card__content">
                    <div class="admin-list-card__top">
                        <div>
                            <h3>{{ $departure->trek?->title ?? 'Unknown Trek' }}</h3>
                            <p>{{ $departure->start_date->format('M d') }} - {{ $departure->end_date->format('M d, Y') }}</p>
                        </div>
                        <span class="admin-badge {{ $departure->status === 'Available' ? 'is-success' : ($departure->status === 'Full' ? 'is-warning' : 'is-muted') }}">{{ $departure->status }}</span>
                    </div>

                    <div class="admin-list-card__meta">
                        <span>Price: ${{ number_format($departure->price, 2) }}</span>
                        <span>Capacity: {{ $departure->capacity }}</span>
                        <span>Booked: {{ $departure->booked_seats }}</span>
                        <span>Available: {{ max($departure->capacity - $departure->booked_seats, 0) }}</span>
                    </div>

                    <div class="admin-list-card__actions">
                        <a href="{{ route('admin.departures.edit', $departure) }}" class="admin-secondary-button">
                            <i class="fas fa-pen"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('admin.trek-bookings.index', ['trek_id' => $departure->trek_id]) }}" class="admin-secondary-button">
                            <i class="fas fa-ticket"></i>
                            <span>View Bookings</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-panel admin-panel--empty">
                <div class="admin-panel__header">
                    <div>
                        <h3>No departures found</h3>
                        <p>Add a departure to start taking trek bookings.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </section>

    @if ($departures->hasPages())
        <div class="admin-pagination">{{ $departures->links() }}</div>
    @endif
</x-dashboard-layout>
