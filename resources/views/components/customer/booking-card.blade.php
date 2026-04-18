@props([
    'title' => '',
    'status' => '',
    'icon' => 'fa-mountain',
    'viewRoute' => '#',
])

<div class="bg-white p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-colors">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
                <i class="fas {{ $icon }}"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-slate-900 leading-tight">{{ $title }}</h3>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-1.5">
                    {{ $meta ?? '' }}
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
            <x-customer.status-badge :status="$status" />
            <div class="h-4 w-px bg-slate-100 hidden md:block"></div>
            <a href="{{ $viewRoute }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                Details
            </a>
        </div>
    </div>
</div>
