<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-header">
            <h2 class="admin-title">{{ __('Trek List') }}</h2>
            <a href="{{ route('admin.treks.create') }}" class="admin-action">
                <i class="fas fa-plus"></i> Add New Trek
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="admin-alert">
            <div class="admin-alert-icon">
                <i class="fas fa-check"></i>
            </div>
            <span class="admin-alert-text">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card admin-table-card">
        <div class="recent-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Trek Name</th>
                        <th>Price</th>
                        <th>Difficulty</th>
                        <th>Status</th>
                        <th class="table-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($treks as $trek)
                        <tr>
                            <td>
                                <div class="stat-meta">
                                    <img src="{{ $trek->image ?? 'https://via.placeholder.com/300x200?text=NO+IMAGE' }}" alt="{{ $trek->title }}" class="admin-image">
                                    <div>
                                        <div class="admin-name">{{ $trek->title }}</div>
                                        <div class="admin-subtext">{{ $trek->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="table-amount">${{ number_format($trek->base_price) }}</div>
                                <div class="admin-subtext">Price</div>
                            </td>
                            <td>
                                @php
                                    $difficultyClass = [
                                        'Easy' => 'admin-inline-badge easy',
                                        'Moderate' => 'admin-inline-badge moderate',
                                        'Difficult' => 'admin-inline-badge difficult',
                                        'Extreme' => 'admin-inline-badge extreme',
                                    ][$trek->difficulty] ?? 'admin-inline-badge neutral';
                                @endphp
                                <span class="{{ $difficultyClass }}">
                                    <i class="fas fa-bolt"></i> {{ $trek->difficulty }}
                                </span>
                            </td>
                            <td>
                                @if($trek->status == 'Active')
                                    <div class="stat-meta">
                                        <span class="admin-status-dot active"></span>
                                        <span class="table-label">Active</span>
                                    </div>
                                @else
                                    <div class="stat-meta">
                                        <span class="admin-status-dot inactive"></span>
                                        <span class="table-date">Inactive</span>
                                    </div>
                                @endif
                            </td>
                            <td class="table-right">
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.treks.show', $trek->id) }}" class="admin-icon-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.treks.edit', $trek->id) }}" class="admin-icon-btn" title="Edit Trek">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.treks.destroy', $trek->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this trek?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-icon-btn danger" title="Delete Trek">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">
                                No Treks Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($treks->hasPages())
            <div class="admin-table-header">
                {{ $treks->links() }}
            </div>
        @endif
    </div>

    <div class="admin-footer-note">
        <div>Total Treks: {{ $treks->total() }}</div>
        <div class="admin-footer-status">
            <span><span class="admin-status-dot active"></span> Active</span>
            <span><span class="admin-status-dot inactive"></span> Inactive</span>
        </div>
    </div>
</x-dashboard-layout>
