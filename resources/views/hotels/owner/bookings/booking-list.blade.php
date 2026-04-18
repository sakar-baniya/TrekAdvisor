<x-layouts.dashboard>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-display">Manage Bookings</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Review and manage guest reservations for your properties</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-200">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Total Active: {{ $bookings->total() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mb-10">
        <form action="{{ route('hotel_owner.bookings.index') }}" method="GET" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[240px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Search Guest or Ref</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Enter reference or guest name..." 
                        class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-4 py-3.5 text-sm font-medium text-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all placeholder:text-slate-400">
                </div>
            </div>
            
            <div class="w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Filter by Status</label>
                <select name="status" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-slate-900/10 transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    @foreach (['pending', 'confirmed', 'success', 'cancelled', 'completed'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-slate-900 text-white px-8 py-3.5 rounded-2xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                Apply Filters
            </button>

            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('hotel_owner.bookings.index') }}" class="px-6 py-3.5 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-20 text-center shadow-sm">
            <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-calendar-day text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">No bookings found</h3>
            <p class="text-slate-500 mt-2 max-w-xs mx-auto">We couldn't find any reservations matching your current search or filters.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-12">
            @foreach ($bookings as $booking)
                @php
                    $statusConfig = match(strtolower($booking->status)) {
                        'confirmed', 'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500'],
                        'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500'],
                        'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-100', 'dot' => 'bg-red-500'],
                        'completed' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-100', 'dot' => 'bg-slate-500'],
                        default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-100', 'dot' => 'bg-slate-500']
                    };
                @endphp
                <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500 overflow-hidden relative">
                    <!-- Card Header -->
                    <div class="p-8 pb-0">
                        <div class="flex justify-between items-start mb-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                <span class="w-1 h-1 rounded-full {{ $statusConfig['dot'] }}"></span>
                                {{ str_replace('_', ' ', $booking->status) }}
                            </span>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest group-hover:text-slate-400 transition-colors">#{{ $booking->booking_reference }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-slate-700 transition-colors line-clamp-1 capitalize">{{ $booking->user?->name ?? 'Guest' }}</h3>
                        <p class="text-xs font-semibold text-slate-400 mt-1 flex items-center gap-2">
                            <i class="fas fa-hotel text-[10px]"></i>
                            {{ $booking->hotelRoom?->hotel?->name ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Stats Section -->
                    <div class="p-8 pt-7 space-y-5">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100/50">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Check In</p>
                                <p class="text-xs font-bold text-slate-900">{{ optional($booking->check_in)->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Check Out</p>
                                <p class="text-xs font-bold text-slate-900">{{ optional($booking->check_out)->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-2">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Room Type</p>
                                <p class="text-xs font-bold text-slate-700">{{ $booking->hotelRoom?->room_type ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                <p class="text-xs font-black text-slate-900">NPR {{ number_format($booking->total_price) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 pt-0">
                        <a href="{{ route('hotel_owner.bookings.show', $booking) }}" class="flex items-center justify-center gap-2 w-full bg-slate-50 text-slate-900 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all duration-300">
                            Manage Reservation
                            <i class="fas fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            {{ $bookings->links() }}
        </div>
    @endif
</x-layouts.dashboard>

