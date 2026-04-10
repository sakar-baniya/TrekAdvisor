@props(['action', 'method' => 'POST', 'rating' => null, 'comment' => null, 'submitLabel' => 'Submit Review'])

<form method="POST" action="{{ $action }}" class="review-form">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <label class="review-form__label" for="review-rating">Rating</label>
    <select id="review-rating" name="rating" class="review-form__select" required>
        <option value="">Select rating</option>
        @for ($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" @selected((int)($rating ?? 0) === $i)>{{ $i }} stars</option>
        @endfor
    </select>

    <label class="review-form__label" for="review-comment">Comment</label>
    <textarea id="review-comment" name="comment" class="review-form__textarea" rows="4" placeholder="Share your experience">{{ old('comment', $comment) }}</textarea>

    <button type="submit" class="market-button">{{ $submitLabel }}</button>
</form>
