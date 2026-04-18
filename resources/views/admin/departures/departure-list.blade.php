@section('page-title', 'Trek Departures')
@section('page-subtitle', 'Manage departure dates, prices, and capacity for all expeditions.')

@section('page-actions')
    <a href="{{ route('admin.departures.create') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm">
        Add Departure
    </a>
@endsection

<x-layouts.dashboard>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Alert System -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 mb-1">Success</p>
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Global Filters Card -->
        <div class="bg-white border border-slate-200/70 rounded-2xl p-6">
            <h2 class="text-base font-semibold text-slate-900 mb-4">Global Filters</h2>
            <form method="GET" action="{{ route('admin.departures.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5 block">Departure Trek</label>
                    <select name="trek_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30">
                        <option value="">All Treks</option>
                        @foreach ($treks as $trek)
                            <option value="{{ $trek->id }}" @selected((string)$selectedTrek === (string)$trek->id)>{{ $trek->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5 block">Booking Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30">
                        <option value="">All Statuses</option>
                        @foreach (['available' => 'Available', 'full' => 'Fully Booked', 'completed' => 'Expedition Completed'] as $value => $label)
                            <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5 block">Calendar Month</label>
                    <select name="month" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30">
                        <option value="">Any Month</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected((string)$selectedMonth === (string)$m)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-slate-900 text-white hover:bg-slate-800 rounded-xl px-4 py-2.5 text-sm font-semibold w-full transition-all">
                    Search Dates
                </button>
            </form>
        </div>

        <!-- Departure Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($departures as $dep)
                @php
                    $statusClass = match(strtolower($dep->status)) {
                        'full', 'fully booked' => 'bg-red-50 text-red-700 border-red-200',
                        'available' => 'bg-green-50 text-green-700 border-green-200',
                        default => 'bg-amber-50 text-amber-700 border-amber-200'
                    };
                @endphp
                <div class="bg-white border border-slate-200/70 rounded-2xl p-5 hover:border-slate-300 hover:shadow-sm transition">
                    <div class="flex items-center justify-between">
                        <span class="{{ $statusClass }} rounded-full px-3 py-1 text-xs font-semibold border">
                            {{ ucfirst(strtolower($dep->status)) }}
                        </span>
                    </div>

                    <h3 class="text-base font-semibold text-slate-900 mt-3 mb-2 line-clamp-2">{{ $dep->trek?->title ?? 'Trek Departure' }}</h3>
                    
                    <div class="text-sm text-slate-600 flex items-center gap-2 mb-3">
                        <i class="far fa-calendar-alt w-4 h-4 text-slate-400"></i>
                        {{ $dep->start_date->format('M d') }} - {{ $dep->end_date->format('M d, Y') }}
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-3 border-t border-slate-100 mt-3">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1 block">Price</span>
                            <span class="text-sm font-semibold text-slate-900">NPR {{ number_format($dep->price, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1 block">Available</span>
                            <span class="text-sm font-semibold text-slate-900">{{ max($dep->capacity - $dep->booked_seats, 0) }} / {{ $dep->capacity }} Seats</span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.departures.edit', $dep) }}" class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 inline-flex items-center justify-center gap-2 transition-all">
                            <i class="fas fa-pen text-xs"></i> Edit Date
                        </a>
                        <a href="{{ route('admin.trek-bookings.index', ['departure_id' => $dep->id]) }}" class="bg-slate-900 text-white hover:bg-slate-800 rounded-xl px-3 py-2.5 inline-flex items-center justify-center transition-all shadow-sm">
                            <i class="fas fa-receipt w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-sm text-slate-500 font-medium">No departures found matching your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($departures->hasPages())
            <div class="mt-8 flex justify-center pb-12">
                {{ $departures->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>

