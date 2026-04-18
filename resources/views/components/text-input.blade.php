@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 px-4 py-3 bg-slate-50 focus:bg-white transition-colors']) }}>
