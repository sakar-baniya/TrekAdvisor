{{-- resources/views/components/dashboard/pending-task.blade.php --}}

@props(['title', 'amount', 'description', 'icon' => 'fa-tasks', 'actionLabel' => 'View', 'actionUrl' => '#', 'tag' => 'Task'])

<div class="dashboard-pending-task">
    <div class="dashboard-pending-task__icon">
        <i class="fas {{ $icon }}"></i>
    </div>

    <div class="dashboard-pending-task__content">
        <span class="dashboard-pending-task__tag">{{ $tag }}</span>
        <h4>{{ $title }}</h4>
        <p class="dashboard-pending-task__amount">{{ $amount }}</p>
        <p class="dashboard-pending-task__description">{{ $description }}</p>
    </div>

    <a href="{{ $actionUrl }}" class="dashboard-pending-task__link">
        {{ $actionLabel }}
        <i class="fas fa-arrow-right"></i>
    </a>
</div>
