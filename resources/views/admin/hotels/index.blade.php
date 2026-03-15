<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="admin-title">Hotel Approvals</h2>
    </x-slot>

    @if (session('success'))
        <div class="admin-alert success">
            <div class="admin-alert-icon"><i class="fas fa-check"></i></div>
            <span class="admin-alert-text">{{ session('success') }}</span>
        </div>
    @endif

    <div class="admin-summary">
        <div class="summary-card">
            <p class="summary-label">Total Hotels</p>
            <h3 class="summary-value">{{ $hotels->total() }}</h3>
        </div>
        <div class="summary-card">
            <p class="summary-label">Pending Approvals</p>
            <h3 class="summary-value">{{ $pendingCount }}</h3>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-header">
            <h4 class="table-caption">Hotels</h4>
            <form method="GET" action="{{ route('admin.hotels.index') }}" class="admin-search">
                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Active" {{ $status === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="recent-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="table-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                        <tr>
                            <td>
                                <div class="table-label">{{ $hotel->name }}</div>
                                <div class="table-text">{{ $hotel->location }}</div>
                            </td>
                            <td>
                                <div class="table-text">{{ $hotel->owner?->name ?? 'Unknown' }}</div>
                                <div class="table-text">{{ $hotel->owner?->email ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="admin-pill {{ $hotel->status === 'Active' ? 'approved' : ($hotel->status === 'Pending' ? 'pending' : 'neutral') }}">
                                    {{ $hotel->status }}
                                </span>
                            </td>
                            <td>
                                <span class="table-date">{{ $hotel->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="table-right">
                                <div class="admin-row-actions">
                                    @if ($hotel->status !== 'Active')
                                        <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Active" />
                                            <button type="submit" class="admin-action">Activate</button>
                                        </form>
                                    @endif
                                    @if ($hotel->status !== 'Inactive')
                                        <form method="POST" action="{{ route('admin.hotels.status', $hotel) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Inactive" />
                                            <button type="submit" class="admin-action">Deactivate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">No hotels found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-table-header">
            {{ $hotels->links() }}
        </div>
    </div>
</x-dashboard-layout>
