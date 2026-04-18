<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-4 py-2 bg-slate-900 border border-transparent rounded-lg font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-colors shadow-sm']) }}>
    {{ $slot }}
</button>
