@props([
    'status' => 'pending',
])

@php
    $status = strtolower($status);
    $configs = [
        'confirmed' => ['color' => 'bg-blue-50 text-blue-700 border-blue-100', 'dot' => 'bg-blue-500'],
        'success'   => ['color' => 'bg-blue-50 text-blue-700 border-blue-100', 'dot' => 'bg-blue-500'],
        'paid'      => ['color' => 'bg-blue-50 text-blue-700 border-blue-100', 'dot' => 'bg-blue-500'],
        'pending'   => ['color' => 'bg-amber-50 text-amber-700 border-amber-100', 'dot' => 'bg-amber-500'],
        'cancelled' => ['color' => 'bg-red-50 text-red-700 border-red-100', 'dot' => 'bg-red-500'],
        'failed'    => ['color' => 'bg-red-50 text-red-700 border-red-100', 'dot' => 'bg-red-500'],
    ];

    $config = $configs[$status] ?? ['color' => 'bg-slate-50 text-slate-700 border-slate-100', 'dot' => 'bg-slate-400'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-xs font-medium {{ $config['color'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ ucfirst($status) }}
</span>
