<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Trek Operations</h1>
            <p class="text-slate-500 font-medium mt-1">Manage departure dates, prices, and capacity for all expeditions.</p>
        </div>
        <a href="{{ route('admin.departures.create') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-semibold uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
            <i class="fas fa-plus mr-2"></i> Add Departure
        </a>
    </div>

    <!-- Alert System -->
    @if (session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
            <p class="text-[10px] font-semibold text-emerald-800 uppercase tracking-widest">Success</p>
            <p class="text-xs font-semibold text-emerald-600 mt-1 uppercase italic tracking-tight">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20">
             <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-widest leading-none">Global Filters</h3>
        </div>
        <div class="p-8">
            <form method="GET" action="{{ route('admin.departures.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                <div>
                    <x-input-label value="Departure Trek" class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <select name="trek_id" class="w-full rounded-2xl border-slate-200 text-xs font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3">
                        <option value="">All Treks</option>
                        @foreach ($treks as $id => $title)
                            <option value="{{ $id }}" @selected((string)$selectedTrek === (string)$id)>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Booking Status" class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <select name="status" class="w-full rounded-2xl border-slate-200 text-xs font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3">
                        <option value="">All Statuses</option>
                        @foreach (['available' => 'Available', 'full' => 'Fully Booked', 'completed' => 'Expedition Completed'] as $value => $label)
                            <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Calendar Month" class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <select name="month" class="w-full rounded-2xl border-slate-200 text-xs font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3">
                        <option value="">Any Month</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected((string)$selectedMonth === (string)$m)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-8 py-3.5 bg-slate-100 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                    Search Dates
                </button>
            </form>
        </div>
    </div>

    <!-- Departure Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
        @forelse ($departures as $dep)
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden flex flex-col italic-hover">
                 <div class="p-8 pb-4">
                     <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full {{ $dep->status === 'Available' ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                             <span class="text-[9px] font-semibold uppercase tracking-widest text-slate-400 italic italic">{{ $dep->status }}</span>
                        </div>
                        <span class="text-[9px] font-semibold text-slate-900 bg-slate-50 px-2 py-1 rounded-lg">#{{ $dep->id }}</span>
                     </div>
                     <h3 class="text-xl font-semibold text-slate-900 tracking-tight leading-tight line-clamp-2 min-h-[3.5rem]">{{ $dep->trek?->title ?? 'Trek Departure' }}</h3>
                     <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-widest mt-2">
                         <i class="far fa-calendar-alt mr-1"></i> {{ $dep->start_date->format('M d') }} - {{ $dep->end_date->format('M d, Y') }}
                     </p>
                 </div>

                 <div class="px-8 py-6 bg-slate-50/50 grid grid-cols-2 gap-y-4 gap-x-6 border-y border-slate-50">
                      <div>
                           <span class="block text-[8px] font-semibold text-slate-400 uppercase tracking-widest">Price</span>
                           <strong class="text-xs font-semibold text-slate-900 tracking-tight">NPR {{ number_format($dep->price, 0) }}</strong>
                      </div>
                      <div>
                           <span class="block text-[8px] font-semibold text-slate-400 uppercase tracking-widest">Available</span>
                           <strong class="text-xs font-semibold text-slate-900 tracking-tight">{{ max($dep->capacity - $dep->booked_seats, 0) }} / {{ $dep->capacity }} Seats</strong>
                      </div>
                 </div>

                 <div class="p-8 flex items-center justify-between gap-4">
                      <a href="{{ route('admin.departures.edit', $dep) }}" class="flex-1 inline-flex justify-center items-center py-3 bg-white border border-slate-200 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                           <i class="fas fa-pen mr-2 text-[9px]"></i> Edit Date
                      </a>
                      <a href="{{ route('admin.trek-bookings.index', ['departure_id' => $dep->id]) }}" class="w-11 h-11 inline-flex items-center justify-center bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition-all shadow-lg shadow-slate-900/20">
                           <i class="fas fa-receipt text-xs"></i>
                      </a>
                 </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 italic">
                <p class="text-slate-400 font-bold">No departures found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($departures->hasPages())
        <div class="mt-8 flex justify-center pb-12">
            {{ $departures->links() }}
        </div>
    @endif
</x-dashboard-layout>
