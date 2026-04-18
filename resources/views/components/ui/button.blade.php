@props([
    'variant' => 'primary', // primary, secondary, outline, danger
    'type' => 'button',
])

@php
    $baseStyles = 'inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 shadow-sm',
        'secondary' => 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50',
        'outline' => 'bg-transparent border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white',
        'danger' => 'bg-white border border-red-200 text-red-600 hover:bg-red-50',
    ];

    $classes = $baseStyles . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>
