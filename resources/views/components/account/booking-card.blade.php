@props(['title', 'status'])

<article {{ $attributes->merge(['class' => 'account-booking-card']) }}>
    <div class="account-booking-card__top">
        <div>
            <h3>{{ $title }}</h3>
            <div class="account-meta-grid">
                {!! $meta ?? '' !!}
            </div>
        </div>
        <x-account.status-badge :status="$status" />
    </div>
    @if (!empty(trim($actions ?? '')))
        <div class="account-actions">
            {{ $actions }}
        </div>
    @endif
    @if (!empty(trim($review ?? '')))
        <div class="account-review-actions">
            {{ $review }}
        </div>
    @endif
</article>
