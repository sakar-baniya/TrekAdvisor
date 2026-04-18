@props(['title', 'amount', 'description', 'icon' => 'fa-tasks', 'actionLabel' => 'View', 'actionUrl' => '#', 'tag' => 'Task', 'color' => 'primary'])

<div class="operational-card">
    <div style="display: flex; align-items: flex-start; gap: 1rem;">
        <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--dashboard-bg); color: var(--dashboard-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas {{ $icon }}"></i>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span class="badge badge--info">{{ $tag }}</span>
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--dashboard-accent);">{{ $amount }}</span>
            </div>
            <h4 style="margin: 0; font-size: 1rem; color: var(--dashboard-primary); font-weight: 700;">{{ $title }}</h4>
            <p style="margin: 0.5rem 0; font-size: 0.85rem; color: var(--dashboard-text-muted); line-height: 1.5;">{{ $description }}</p>
            <a href="{{ $actionUrl }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--dashboard-accent); font-weight: 700; text-decoration: none;">
                {{ $actionLabel }} <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
