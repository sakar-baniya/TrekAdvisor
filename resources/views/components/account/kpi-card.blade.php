@props(['icon', 'label', 'value'])

<article {{ $attributes->merge(['class' => 'account-stat-card']) }}>
    <div class="account-stat-card__icon"><i class="{{ $icon }}"></i></div>
    <div class="account-stat-card__body">
        <strong>{{ $value }}</strong>
        <span>{{ $label }}</span>
    </div>
</article>
