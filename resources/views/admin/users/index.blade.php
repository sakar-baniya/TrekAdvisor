<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">User Management</p>
                <h2 class="admin-page-title">All Users</h2>
            </div>
            <a href="{{ route('admin.users.create-staff') }}" class="admin-primary-button">
                <i class="fas fa-user-plus"></i>
                <span>Add Staff User</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="admin-flash error">{{ session('error') }}</div>
    @endif

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-violet"><i class="fas fa-users"></i></div>
            <div>
                <p>Total Users</p>
                <h3>{{ number_format($users->total()) }}</h3>
                <span>Across all roles</span>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-card__icon is-amber"><i class="fas fa-user-clock"></i></div>
            <div>
                <p>Pending Hotel Owners</p>
                <h3>{{ number_format($pendingHotelOwners) }}</h3>
                <span>Need approval</span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Search users and sort by role</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search name or email" />
            <select name="role" class="admin-input">
                <option value="">All roles</option>
                @foreach (['admin' => 'Admins', 'staff' => 'Staff', 'customer' => 'Customers', 'hotel_owner' => 'Hotel Owners'] as $value => $label)
                    <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="admin-filter-tabs">
                <a href="{{ route('admin.users.index') }}" class="admin-filter-tab {{ $role === '' ? 'is-active' : '' }}">All Users</a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="admin-filter-tab {{ $role === 'admin' ? 'is-active' : '' }}">Admins</a>
                <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" class="admin-filter-tab {{ $role === 'staff' ? 'is-active' : '' }}">Staff</a>
                <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="admin-filter-tab {{ $role === 'customer' ? 'is-active' : '' }}">Customers</a>
                <a href="{{ route('admin.users.index', ['role' => 'hotel_owner']) }}" class="admin-filter-tab {{ $role === 'hotel_owner' ? 'is-active' : '' }}">Hotel Owners</a>
            </div>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>User List</h3>
                <p>Roles, approvals, and quick actions</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Approval</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ str_replace('_', ' ', $user->role) }}</td>
                            <td>
                                @if ($user->role === 'hotel_owner')
                                    <span class="admin-badge {{ $user->is_approved ? 'is-success' : 'is-warning' }}">{{ $user->is_approved ? 'Approved' : 'Pending' }}</span>
                                @else
                                    <span class="admin-badge is-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="admin-inline-actions">
                                    @if ($user->role === 'hotel_owner' && ! $user->is_approved)
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="admin-secondary-button">
                                                <i class="fas fa-check"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="admin-inline-role-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="admin-input">
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            <option value="staff" @selected($user->role === 'staff')>Staff</option>
                                            <option value="customer" @selected($user->role === 'customer')>Customer</option>
                                            <option value="hotel_owner" @selected($user->role === 'hotel_owner')>Hotel Owner</option>
                                        </select>
                                        <button type="submit" class="admin-secondary-button">
                                            <i class="fas fa-rotate"></i>
                                            <span>Update</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table__empty">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($users->hasPages())
        <div class="admin-pagination">{{ $users->links() }}</div>
    @endif
</x-dashboard-layout>
