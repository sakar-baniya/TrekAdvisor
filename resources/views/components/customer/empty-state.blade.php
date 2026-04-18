@props([
    'title' => 'No items found',
    'message' => 'Try adjusting your filters or checking back later.',
    'icon' => 'fa-clipboard-list',
    'actionText' => '',
    'actionRoute' => '',
])

<div class="py-16 px-6 text-center bg-white rounded-xl border border-dashed border-slate-200">
    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-xl flex items-center justify-center text-2xl mx-auto mb-5">
        <i class="fas {{ $icon }}"></i>
    </div>
    <h3 class="text-base font-semibold text-slate-900 mb-1">{{ $title }}</h3>
    <p class="text-slate-500 text-sm mb-8 max-w-xs mx-auto text-balance">{{ $message }}</p>
    
    @if($actionText && $actionRoute)
        <a href="{{ $actionRoute }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
            {{ $actionText }}
        </a>
    @endif
</div>
