{{-- resources/views/components/dashboard/stat-card.blade.php --}}

@props(['label', 'value', 'meta', 'icon' => 'fa-chart-line', 'trend' => null, 'trendDirection' => 'up'])

<div class="dashboard-stat-card">
    <div class="dashboard-stat-card__icon">
        <i class="fas {{ $icon }}"></i>
    </div>
    
    <div class="dashboard-stat-card__content">
        <p class="dashboard-stat-card__label">{{ $label }}</p>
        <h3 class="dashboard-stat-card__value">{{ $value }}</h3>
        
        @if($meta)
            <span class="dashboard-stat-card__meta">
                @if($trend)
                    <i class="fas fa-arrow-{{ $trendDirection === 'up' ? 'up' : 'down' }}" style="color: {{ $trendDirection === 'up' ? '#10b981' : '#ef4444' }};"></i>
                    <strong>{{ $trend }}</strong>
                @endif
                {{ $meta }}
            </span>
        @endif
    </div>
</div>
