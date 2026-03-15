<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-header">
            <h2 class="admin-title">{{ __('Trek Details') }}</h2>
            <a href="{{ route('admin.treks.edit', $trek->id) }}" class="admin-action">
                <i class="fas fa-edit"></i> Edit Trek
            </a>
        </div>
    </x-slot>

    <div class="admin-detail-grid">
        <div class="admin-content">
            <div class="admin-hero">
                <div class="admin-hero-image">
                    <img src="{{ $trek->image ?? 'https://via.placeholder.com/1200x800?text=NO+IMAGE' }}" alt="{{ $trek->title }}">
                    <div class="admin-hero-overlay"></div>
                    <div class="admin-hero-content">
                        <div class="admin-hero-tags">
                            <span class="admin-hero-tag">{{ $trek->difficulty }}</span>
                            <span class="admin-hero-tag dark">ID: {{ $trek->id }}</span>
                        </div>
                        <h1 class="admin-hero-title">{{ $trek->title }}</h1>
                    </div>
                </div>
                <div class="admin-hero-body">
                    <h3 class="admin-section-title">About This Trek</h3>
                    <div class="content-body">
                        {!! nl2br(e($trek->description)) !!}
                    </div>
                </div>
            </div>

            @if($trek->gallery && $trek->gallery->count() > 0)
                <div class="admin-section admin-spacing-top">
                    <div class="admin-section-header">
                        <h3 class="admin-section-title">Gallery</h3>
                    </div>
                    <div class="admin-section-body">
                        <div class="admin-gallery">
                            @foreach($trek->gallery as $image)
                                <a href="{{ $image->path }}" target="_blank">
                                    <img src="{{ $image->path }}" alt="Gallery image">
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="admin-sidebar">
            <div class="admin-sidebar-card">
                <p class="summary-label">Price</p>
                <div class="summary-value">${{ number_format($trek->base_price) }}</div>

                <div class="admin-sidebar-row">
                    <span>Status</span>
                    <span>{{ $trek->status }}</span>
                </div>
                <div class="admin-sidebar-row">
                    <span>Difficulty</span>
                    <span>{{ $trek->difficulty }}</span>
                </div>
                <div class="admin-sidebar-row">
                    <span>Updated</span>
                    <span>{{ $trek->updated_at->format('Y.m.d') }}</span>
                </div>
            </div>

            <div class="admin-sidebar-card light admin-spacing-top">
                <h4 class="table-caption">System Info</h4>
                <div class="admin-section-body">
                    <div class="table-text">Slug: {{ $trek->slug }}</div>
                    <div class="table-text">Images: {{ $trek->gallery->count() + 1 }} Photos</div>
                </div>
            </div>

            <form action="{{ route('admin.treks.destroy', $trek->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this trek?')" class="admin-spacing-top">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-delete">Delete Trek</button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
