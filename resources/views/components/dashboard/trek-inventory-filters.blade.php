{{-- TrekInventoryFilters Component --}}
@props(['search', 'difficulty', 'status'])

<form method="GET" action="{{ route('admin.treks.index') }}" class="flex flex-wrap items-end gap-6">
    <!-- Search -->
    <div class="flex-1 min-w-[300px] space-y-2">
        <label for="trek-search" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Search Trek</label>
        <div class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
            <input id="trek-search" 
                   type="search" 
                   name="search" 
                   value="{{ $search }}" 
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all" 
                   placeholder="e.g. Everest Base Camp" 
                   autocomplete="off" />
        </div>
    </div>

    <!-- Difficulty -->
    <div class="w-full sm:w-48 space-y-2">
        <label for="trek-difficulty" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Difficulty</label>
        <select id="trek-difficulty" name="difficulty" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all appearance-none cursor-pointer">
            <option value="">All Levels</option>
            @foreach (["Easy", "Moderate", "Difficult", "Extreme"] as $option)
                <option value="{{ $option }}" @selected($difficulty === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <!-- Status -->
    <div class="w-full sm:w-48 space-y-2">
        <label for="trek-status" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Status</label>
        <select id="trek-status" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all appearance-none cursor-pointer">
            <option value="">All Statuses</option>
            @foreach (["Active", "Inactive"] as $option)
                <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2">
        <button type="submit" class="h-[46px] px-6 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
            Apply
        </button>
        <a href="{{ route('admin.treks.index') }}" class="h-[46px] w-[46px] flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm" title="Reset Filters">
            <i class="fas fa-redo-alt text-xs"></i>
        </a>
    </div>
</form>
