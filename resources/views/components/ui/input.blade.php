@props([
    'label' => '',
    'icon' => null,
    'error' => null,
])

<div class="space-y-1.5 w-full">
    @if($label)
        <label {{ $attributes->has('id') ? 'for='.$attributes->get('id') : '' }} class="block text-xs font-semibold text-slate-700 pl-1">
            {{ $label }}
        </label>
    @endif

    @php
        $type = $attributes->get('type', 'text');
        $isPassword = $type === 'password';
    @endphp

    <div class="relative group" @if($isPassword) x-data="{ show: false }" @endif>
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                <i class="fas {{ $icon }} text-xs"></i>
            </div>
        @endif

        <input 
            @if($isPassword) :type="show ? 'text' : 'password'" @endif
            {{ $attributes->merge([
                'class' => 'block w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all ' . ($icon ? 'pl-10' : 'pl-4') . ' ' . ($isPassword ? 'pr-10' : 'pr-4') . ' ' . ($error ? 'border-red-300 bg-red-50/10' : '')
            ]) }}
        >

        @if($isPassword)
            <button 
                type="button" 
                @click="show = !show" 
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-900 transition-colors focus:outline-none"
            >
                <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
        @endif
    </div>

    @if($error)
        <p class="text-[11px] font-medium text-red-600 pl-1 mt-1 animate-fadeIn">
            {{ $error }}
        </p>
    @endif
</div>
