<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Gear Management</p>
                <h2 class="admin-page-title">All Gear Items</h2>
            </div>
            <a href="{{ route('admin.gear.create') }}" class="admin-primary-button">
                <i class="fas fa-plus"></i>
                <span>Add Gear Item</span>
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
                <p>Search and sort the gear inventory</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.gear.index') }}" class="admin-filter-grid">
            <input type="search" name="search" value="{{ $search }}" class="admin-input" placeholder="Search item name" />
            <select name="type" class="admin-input">
                <option value="">All types</option>
                @foreach ($types as $option)
                    <option value="{{ $option }}" @selected($type === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <select name="availability" class="admin-input">
                <option value="">All availability</option>
                <option value="available" @selected($availability === 'available')>Available</option>
                <option value="out" @selected($availability === 'out')>Out of stock</option>
            </select>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Inventory</h3>
                <p>Current item stock and pricing</p>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Price/day</th>
                        <th>Stock</th>
                        <th>Available</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gearItems as $item)
                        <tr>
                            <td>
                                <div class="admin-table-item">
                                    <img src="{{ $item->image ?: 'https://via.placeholder.com/120x120?text=Gear' }}" alt="{{ $item->name }}">
                                    <strong>{{ $item->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $item->type }}</td>
                            <td>${{ number_format($item->daily_price, 2) }}</td>
                            <td>{{ $item->total_stock }}</td>
                            <td>{{ $item->available_stock }}</td>
                            <td>
                                <a href="{{ route('admin.gear.edit', $item) }}" class="admin-secondary-button">
                                    <i class="fas fa-pen"></i>
                                    <span>Edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table__empty">No gear items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($gearItems->hasPages())
        <div class="admin-pagination">{{ $gearItems->links() }}</div>
    @endif
</x-dashboard-layout>
