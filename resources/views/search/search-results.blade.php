<x-layouts.dashboard>
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Search Results</h1>
                <p class="text-slate-500 font-medium mt-1">Found results matching your query</p>
            </div>
            <div class="px-6 py-3 bg-slate-100 rounded-2xl text-slate-900 border border-slate-200">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2">Query</span>
                <strong class="text-lg leading-none">"{{ $query ?: '...' }}"</strong>
            </div>
        </div>

        @if (!$query)
            <div class="py-20 text-center bg-white rounded-3xl border border-slate-200 border-dashed">
                <div class="text-5xl text-slate-200 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No search term entered</h3>
                <p class="text-slate-500 font-medium">Please enter a keyword in the sidebar search to see results.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Treks Column -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <i class="fas fa-mountain text-sm"></i>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 tracking-tight">Treks</h4>
                    </div>
                    
                    @if ($treks->isEmpty())
                        <div class="py-12 text-center">
                            <p class="text-sm font-bold text-slate-300 italic">No treks found.</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($treks as $trek)
                                <li>
                                    <a href="{{ route('treks.show', $trek->slug) }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-200 hover:bg-white transition-all group">
                                        <span class="font-bold text-slate-700 group-hover:text-slate-900 underline decoration-slate-300 decoration-1 underline-offset-4">{{ $trek->title }}</span>
                                        <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover:text-slate-900 group-hover:translate-x-1 transition-all"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Hotels Column -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <i class="fas fa-hotel text-sm"></i>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 tracking-tight">Hotels</h4>
                    </div>

                    @if ($hotels->isEmpty())
                        <div class="py-12 text-center">
                            <p class="text-sm font-bold text-slate-300 italic">No hotels found.</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($hotels as $hotel)
                                <li>
                                    <a href="{{ route('hotels.show', $hotel) }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-200 hover:bg-white transition-all group">
                                        <span class="font-bold text-slate-700 group-hover:text-slate-900 underline decoration-slate-300 decoration-1 underline-offset-4">{{ $hotel->name }}</span>
                                        <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover:text-slate-900 group-hover:translate-x-1 transition-all"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Users Column (Admin Only) -->
                @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <h4 class="text-lg font-extrabold text-slate-900 tracking-tight">Users</h4>
                        </div>

                        @if ($users->isEmpty())
                            <div class="py-12 text-center">
                                <p class="text-sm font-bold text-slate-300 italic">No users found.</p>
                            </div>
                        @else
                            <ul class="space-y-3">
                                @foreach ($users as $result)
                                    <li class="p-4 rounded-2xl bg-slate-50 border border-transparent">
                                        <div class="flex items-center justify-between mb-1">
                                            <strong class="text-slate-900 font-bold block">{{ $result->name }}</strong>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold uppercase tracking-tighter bg-slate-200 text-slate-600">
                                                {{ $result->role }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-slate-500 font-semibold">{{ $result->email }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layouts.dashboard>

