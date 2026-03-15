<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="admin-title">Manage Users</h2>
    </x-slot>

    @if (session('success'))
        <div class="admin-alert success">
            <div class="admin-alert-icon"><i class="fas fa-check"></i></div>
            <span class="admin-alert-text">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="admin-alert error">
            <div class="admin-alert-icon"><i class="fas fa-exclamation"></i></div>
            <span class="admin-alert-text">{{ session('error') }}</span>
        </div>
    @endif

    <div class="admin-summary">
        <div class="summary-card">
            <p class="summary-label">Total Users</p>
            <h3 class="summary-value">{{ $users->total() }}</h3>
        </div>
        <div class="summary-card">
            <p class="summary-label">Pending Hotel Owners</p>
            <h3 class="summary-value">{{ $pendingHotelOwners }}</h3>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-header">
            <h4 class="table-caption">Users</h4>
            <form method="GET" action="{{ route('admin.users.index') }}" class="admin-search">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name or email"
                />
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="recent-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Approval</th>
                        <th>Joined</th>
                        <th class="table-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="table-label">{{ $user->name }}</div>
                                <div class="table-text">{{ $user->email }}</div>
                            </td>
                            <td>
                                <span class="table-text">{{ str_replace('_', ' ', $user->role) }}</span>
                            </td>
                            <td>
                                @if ($user->role === 'hotel_owner')
                                    <span class="admin-pill {{ $user->is_approved ? 'approved' : 'pending' }}">
                                        {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                @else
                                    <span class="table-text">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="table-date">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="table-right">
                                <div class="admin-row-actions">
                                    @if ($user->role === 'hotel_owner' && ! $user->is_approved)
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="admin-action">Approve</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="admin-row-actions">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="filter-select">
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="hotel_owner" {{ $user->role === 'hotel_owner' ? 'selected' : '' }}>Hotel Owner</option>
                                        </select>
                                        <button type="submit" class="admin-action">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-table-header">
            {{ $users->links() }}
        </div>
    </div>
</x-dashboard-layout>
