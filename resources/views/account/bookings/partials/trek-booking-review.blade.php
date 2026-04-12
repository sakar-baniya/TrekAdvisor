@if ($booking->status === 'completed')
    <section class="account-panel">
        <div class="account-panel__head">
            <div>
                <h2>Review this trek</h2>
                <p>Share your experience with other trekkers.</p>
            </div>
        </div>

        @if ($review)
            <x-reviews.form :action="route('account.reviews.update', $review)" method="PATCH" :rating="$review->rating" :comment="$review->comment" submit-label="Update Review" />
            <form method="POST" action="{{ route('account.reviews.destroy', $review) }}" class="review-delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="account-outline-button">Delete Review</button>
            </form>
        @else
            @if ($booking->departure?->trek)
                <x-reviews.form :action="route('account.reviews.treks.store', $booking->departure->trek)" submit-label="Submit Review" />
            @endif
        @endif
    </section>
@else
    <div class="account-note account-note--muted">Review available after completion.</div>
@endif
