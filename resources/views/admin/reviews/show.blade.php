<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Reviews</p>
                <h2 class="admin-page-title">Review Details</h2>
            </div>
            <a href="{{ route('admin.reviews.index') }}" class="admin-secondary-button">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Reviews</span>
            </a>
        </div>
    </x-slot>

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Review</h3>
                    <p>Full comment and review target details</p>
                </div>
            </div>
            <div class="admin-info-list">
                <div><span>Reviewer</span><strong>{{ $review->user?->name }} ({{ $review->user?->email }})</strong></div>
                <div><span>Target</span><strong>{{ $review->reviewable?->title ?? $review->reviewable?->name ?? 'Deleted item' }}</strong></div>
                <div><span>Type</span><strong>{{ class_basename($review->reviewable_type) }}</strong></div>
                <div><span>Rating</span><strong>{{ $review->rating }}/5</strong></div>
                <div><span>Flagged</span><strong>{{ $review->is_flagged ? 'Yes' : 'No' }}</strong></div>
            </div>
            <div class="admin-review-full">
                {{ $review->comment ?: 'No comment left.' }}
            </div>
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Actions</h3>
                        <p>Moderation controls</p>
                    </div>
                </div>
                <div class="admin-status-form">
                    <form method="POST" action="{{ route('admin.reviews.flag', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="admin-secondary-button admin-primary-button--fit">
                            <i class="fas fa-flag"></i>
                            <span>{{ $review->is_flagged ? 'Remove Flag' : 'Flag Review' }}</span>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-danger-button">
                            <i class="fas fa-trash"></i>
                            <span>Delete Review</span>
                        </button>
                    </form>
                </div>
            </section>
        </aside>
    </section>
</x-dashboard-layout>
