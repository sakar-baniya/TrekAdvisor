@props(['status'])

@php
    $normalized = strtolower($status ?? '');
    $class = match ($normalized) {
        'confirmed', 'paid', 'success', 'completed' => 'is-success',
        'pending' => 'is-warning',
        'cancelled', 'failed', 'cancellation_requested' => 'is-danger',
        default => 'is-info',
    };
    $label = match ($normalized) {
        'success' => 'Paid',
        'pending' => 'Pending',
        'failed' => 'Failed',
        'cancellation_requested' => 'Cancellation Requested',
        default => ucwords(str_replace('_', ' ', (string) $status)),
    };
@endphp

<span {{ $attributes->merge(['class' => 'account-status ' . $class]) }}>{{ $label }}</span>
