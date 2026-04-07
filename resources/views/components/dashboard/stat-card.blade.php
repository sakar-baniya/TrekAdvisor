@props(['label', 'value', 'meta' => null, 'icon' => 'fa-chart-line', 'trend' => null, 'trendDirection' => 'up'])

<div class="stat-card">
    <div class="stat-card__header">
        <div class="stat-card__icon">
            <i class="fas {{ $icon }}"></i>
        </div>
        
        @if($trend)
            @php
                $trendClass = match($trendDirection) {
                    'up' => 'stat-card__trend--up',
                    'down' => 'stat-card__trend--down',
                    default => 'stat-card__trend--neutral'
                };
            @endphp
            <div class="stat-card__trend {{ $trendClass }}">
                <i class="fas fa-{{ $trendDirection === 'up' ? 'arrow-trend-up' : ($trendDirection === 'down' ? 'arrow-trend-down' : 'minus') }}"></i>
                {{ $trend }}
            </div>
        @endif
    </div>
    
    <div>
        <p class="stat-card__label mb-1">{{ $label }}</p>
        <h3 class="stat-card__value">{{ $value }}</h3>
    </div>
    
    @if($meta)
        <div class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">
            {{ $meta }}
        </div>
    @endif
</div>
