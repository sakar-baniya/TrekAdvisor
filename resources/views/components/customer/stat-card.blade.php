@props([
    'label' => '',
    'value' => '',
    'icon' => '',
])

<div class="bg-white p-5 rounded-xl border border-slate-200">
    <div class="flex items-center gap-4">
        <div class="text-slate-400 text-lg">
            <i class="fas {{ $icon }}"></i>
        </div>
        <div>
            <strong class="block text-2xl font-semibold text-slate-900 tracking-tight">{{ $value }}</strong>
            <span class="block text-sm text-slate-500">{{ $label }}</span>
        </div>
    </div>
</div>
