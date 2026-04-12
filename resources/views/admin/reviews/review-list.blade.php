<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">Reviews</p>
                <h2 class="admin-page-title">{{ $flaggedOnly ? 'Flagged Reviews' : 'All Reviews' }}</h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel__header">
            <div>
                <h3>Filters</h3>
                <p>Filter by review target or star rating</p>
            </div>
        </div>

        <form method="GET" action="{{ $flaggedOnly ? route('admin.reviews.flagged') : route('admin.reviews.index') }}" class="admin-filter-grid">
            <select name="type" class="admin-input">
                <option value="">All types</option>
                <option value="trek" @selected($type === 'trek')>Treks</option>
                <option value="hotel" @selected($type === 'hotel')>Hotels</option>

            </select>
            <select name="rating" class="admin-input">
                <option value="">All ratings</option>
                @foreach (range(1, 5) as $star)
                    <option value="{{ $star }}" @selected((string) $rating === (string) $star)>{{ $star }} stars</option>
                @endforeach
            </select>
            <div class="admin-filter-tabs">
                <a href="{{ route('admin.reviews.index') }}" class="admin-filter-tab {{ ! $flaggedOnly ? 'is-active' : '' }}">All Reviews</a>
                <a href="{{ route('admin.reviews.flagged') }}" class="admin-filter-tab {{ $flaggedOnly ? 'is-active' : '' }}">Flagged Reviews</a>
            </div>
            <button type="submit" class="admin-primary-button admin-primary-button--fit">Apply</button>
        </form>
    </section>

    <section class="admin-card-list">
        @forelse ($reviews as $review)
            <article class="admin-panel">
                <div class="admin-review-card">
                    <div class="admin-review-card__top">
                        <div>
                            <strong>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</strong>
                            <p>{{ $review->user?->name ?? 'Unknown user' }} on "{{ $review->reviewable?->title ?? $review->reviewable?->name ?? 'Deleted item' }}"</p>
                        </div>
                        @if ($review->is_flagged)
                            <span class="admin-badge is-warning">Flagged</span>
                        @endif
                    </div>
                    <p class="admin-list-card__excerpt">{{ \Illuminate\Support\Str::limit($review->comment, 160) }}</p>
                    <div class="admin-list-card__meta">
                        <span>Posted {{ $review->created_at->diffForHumans() }}</span>
                        <span>{{ class_basename($review->reviewable_type) }}</span>
                    </div>
                    <div class="admin-list-card__actions">
                        <a href="{{ route('admin.reviews.show', $review) }}" class="admin-secondary-button">
                            <i class="fas fa-eye"></i>
                            <span>View</span>
                        </a>
                        <form method="POST" action="{{ route('admin.reviews.flag', $review) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="admin-secondary-button">
                                <i class="fas fa-flag"></i>
                                <span>{{ $review->is_flagged ? 'Unflag' : 'Flag' }}</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="admin-danger-button" data-confirm="delete-review">
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
                        <h3>No reviews found</h3>
                        <p>Try adjusting the filters.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </section>

    @if ($reviews->hasPages())
        <div class="admin-pagination">{{ $reviews->links() }}</div>
    @endif
</x-dashboard-layout>
