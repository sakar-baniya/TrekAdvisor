<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <a href="{{ route('admin.treks.index') }}" aria-label="Back to Treks" style="color: var(--text-muted); font-size: 1.25rem; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; background: white; border: 1px solid var(--border-light); text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="admin-eyebrow">Trek Management</p>
                    <h2 class="admin-page-title">{{ $trek->title }}</h2>
                </div>
            </div>
            <a href="{{ route('admin.treks.edit', $trek) }}" class="admin-primary-button">
                <i class="fas fa-pen"></i>
                <span>Edit Trek</span>
            </a>
        </div>
    </x-slot>

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-show-hero">
                <img src="{{ $trek->image ?: 'https://via.placeholder.com/1200x700?text=Trek' }}" alt="{{ $trek->title }}">
            </div>
            <div class="admin-show-copy">
                <div class="admin-list-card__meta">
                    <span>{{ $trek->difficulty }}</span>
                    <span>{{ $trek->duration_days ?? 'N/A' }} days</span>
                    <span>Max {{ $trek->max_altitude ? number_format($trek->max_altitude) . 'm' : 'Not set' }}</span>
                    <span>${{ number_format($trek->base_price, 2) }}</span>
                </div>
                <p>{{ $trek->description }}</p>
            </div>
        </article>

        <aside class="admin-side-stack">
            @if ($trek->gallery->isNotEmpty())
                <section class="admin-panel">
                    <div class="admin-panel__header">
                        <div>
                            <h3>Gallery</h3>
                            <p>Supporting trek photos</p>
                        </div>
                    </div>
                    <div class="admin-gallery">
                        @foreach ($trek->gallery as $image)
                            <img src="{{ $image->path }}" alt="{{ $trek->title }} gallery image {{ $loop->iteration }}">
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Overview</h3>
                        <p>Quick operational stats</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Status</span><strong>{{ $trek->status }}</strong></div>
                    <div><span>Slug</span><strong>{{ $trek->slug }}</strong></div>
                    <div><span>Departures</span><strong>{{ $trek->departures->count() }}</strong></div>
                    <div><span>Created</span><strong>{{ $trek->created_at->format('M d, Y') }}</strong></div>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Itinerary</h3>
                        <p>Saved day plan</p>
                    </div>
                </div>
                <div class="admin-note-stack">
                    @forelse ($trek->itineraries as $day)
                        <article class="admin-note-card">
                            <strong>Day {{ $day->day_number }}: {{ $day->title }}</strong>
                            <span>{{ $day->description }}</span>
                        </article>
                    @empty
                        <article class="admin-note-card">
                            <strong>No itinerary yet</strong>
                            <span>Add itinerary days from the edit screen.</span>
                        </article>
                    @endforelse
                </div>
            </section>
        </aside>
    </section>
</x-dashboard-layout>
