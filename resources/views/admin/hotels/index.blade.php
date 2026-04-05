<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Hotel Management</p>
                <h2 class="admin-page-title">All Hotels</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-slate"><i class="fas fa-hotel"></i></div>
            <div>
                <p>Total Hotels</p>
                <h3>{{ number_format($hotels->total()) }}</h3>
                <span>All listings</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-amber"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <p>Pending</p>
                <h3>{{ number_format($pendingCount) }}</h3>
                <span>Waiting for approval</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-green"><i class="fas fa-circle-check"></i></div>
            <div>
                <p>Active</p>
                <h3>{{ number_format($activeCount) }}</h3>
                <span>Live hotel listings</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Review pending approvals or search by hotel and owner</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.hotels.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search hotel, location, or owner" />
            <select name="status" class="admin-input">
                <option value="">All statuses</option>
                @foreach (['Pending', 'Active', 'Inactive'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <div class="admin-filter-tabs">
                <a href="{{ route('admin.hotels.index') }}" class="admin-filter-tab {{ $status === '' ? 'is-active' : '' }}">All Hotels</a>
                <a href="{{ route('admin.hotels.index', ['status' => 'Pending']) }}" class="admin-filter-tab {{ $status === 'Pending' ? 'is-active' : '' }}">Pending Approval ({{ $pendingCount }})</a>
                <a href="{{ route('admin.hotels.index', ['status' => 'Active']) }}" class="admin-filter-tab {{ $status === 'Active' ? 'is-active' : '' }}">Active</a>
                <a href="{{ route('admin.hotels.index', ['status' => 'Inactive']) }}" class="admin-filter-tab {{ $status === 'Inactive' ? 'is-active' : '' }}">Inactive</a>
            </div>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-card-list">
        @forelse ($hotels as $hotel)
            <article class="admin-list-card">
                <div class="admin-list-card__media">
                    <img src="{{ $hotel->image ?: 'https://via.placeholder.com/480x320?text=Hotel' }}" alt="{{ $hotel->name }}">
                </div>
                <div class="admin-list-card__content">
                    <div class="admin-list-card__top">
                        <div>
                            <h3>{{ $hotel->name }}</h3>
                            <p>{{ $hotel->location }}</p>
                        </div>
                        <span class="admin-badge {{ $hotel->status === 'active' ? 'is-success' : ($hotel->status === 'pending' ? 'is-warning' : 'is-muted') }}">{{ ucfirst($hotel->status) }}</span>
                    </div>

                    <div class="admin-list-card__meta">
                        <span>Owner: {{ $hotel->owner?->name ?? 'Unknown' }}</span>
                        <span>{{ $hotel->owner?->email ?? 'No email' }}</span>
                        <span>Room types: {{ $hotel->rooms->count() }}</span>
                        <span>Owner approval: {{ $hotel->owner?->approval_status === 'approved' ? 'Approved' : 'Pending' }}</span>
                    </div>

                    <p class="admin-list-card__excerpt">{{ \Illuminate\Support\Str::limit($hotel->description, 150) }}</p>

                    <div class="admin-list-card__actions">
                        @if ($hotel->status !== 'active')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active" />
                                <button type="submit" class="admin-primary-button">
                                    <i class="fas fa-check"></i>
                                    <span>Approve</span>
                                </button>
                            </form>
                        @endif

                        @if ($hotel->status !== 'inactive')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="inactive" />
                                <button type="submit" class="admin-danger-button">
                                    <i class="fas fa-ban"></i>
                                    <span>Set Inactive</span>
                                </button>
                            </form>
                        @endif

                        @if ($hotel->status !== 'pending')
                            <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="pending" />
                                <button type="submit" class="admin-secondary-button">
                                    <i class="fas fa-clock"></i>
                                    <span>Move to Pending</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="admin-panel admin-panel--empty">
                <div class="admin-panel__header">
                    <div>
                        <h3>No hotels found</h3>
                        <p>Try a different filter or search term.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </section>

    @if ($hotels->hasPages())
        <div class="admin-pagination">{{ $hotels->links() }}</div>
    @endif
</x-dashboard-layout>



