<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors shadow-sm']) }}>
    {{ $slot }}
</button>
