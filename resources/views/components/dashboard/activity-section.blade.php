{{-- resources/views/components/dashboard/activity-section.blade.php --}}

@props(['title', 'subtitle' => null, 'items' => [], 'emptyMessage' => 'No items found'])

<div class="dashboard-section">
    <div class="dashboard-section__header">
        <div>
            <h3 class="dashboard-section__title">{{ $title }}</h3>
            @if($subtitle)
                <p class="dashboard-section__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        {{ $headerAction ?? '' }}
    </div>

    @if(count($items) > 0)
        <div class="dashboard-section__content">
            {{ $slot }}
        </div>
    @else
        <div class="dashboard-section__empty">
            <p>{{ $emptyMessage }}</p>
        </div>
    @endif
</div>
