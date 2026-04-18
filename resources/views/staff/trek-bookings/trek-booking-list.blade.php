@section('page-title', 'Trek Bookings')
@section('page-subtitle', 'Manage all upcoming and past trek reservations.')

<x-layouts.dashboard>
    <div class="space-y-6">

    <!-- Filters Card -->
    <section class="bg-white p-5 md:p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
        <form method="GET" action="{{ route('staff.trek-bookings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="search" name="search" value="{{ $search }}" 
                   class="bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all font-display" 
                   placeholder="Search reference or customer" />

            <select name="trek_id" class="bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all appearance-none cursor-pointer font-display">
                <option value="">All treks</option>
                @foreach ($treks as $trek)
                    <option value="{{ $trek->id }}" @selected($selectedTrek == $trek->id)>{{ $trek->title }}</option>
                @endforeach
            </select>

            <select name="status" class="bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all appearance-none cursor-pointer font-display">
                <option value="">All status</option>
                @foreach (['Pending', 'Confirmed', 'Cancelled'] as $option)
                    <option value="{{ $option }}" @selected($selectedStatus === strtolower($option))>{{ $option }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-900 text-white rounded-lg px-4 py-2.5 text-sm font-semibold hover:bg-slate-800 transition-all shadow-sm">Apply Filters</button>
        </form>
    </section>

    <!-- Booking Cards -->
    <div class="space-y-4 mb-10">
        @forelse ($bookings as $booking)
            <div class="bg-white p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-all group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100">#{{ $booking->booking_reference }}</span>
                                <h3 class="text-base font-semibold text-slate-900 leading-tight font-display">{{ $booking->departure?->trek?->title ?? 'Unknown Trek' }}</h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-1.5">
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-user text-slate-400"></i> {{ $booking->user?->name ?? 'Guest' }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-calendar text-slate-400"></i> {{ optional($booking->departure?->start_date)->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-2 text-xs font-semibold text-slate-900">
                                    NPR {{ number_format($booking->total_price) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                        @php
                            $statusVal = strtolower($booking->status);
                            $badgeStyle = match($statusVal) {
                                'confirmed', 'success' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'cancelled', 'cancel requested', 'cancellation_requested' => 'bg-red-50 text-red-700 border-red-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-100'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border {{ $badgeStyle }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                        <div class="h-4 w-px bg-slate-100 hidden md:block"></div>
                        <a href="{{ route('staff.trek-bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                            Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border border-slate-200 text-center">
                <i class="fas fa-mountain text-4xl text-slate-100 mb-4 block"></i>
                <p class="text-sm font-medium text-slate-400">No trek bookings found matching your criteria.</p>
            </div>
        @endforelse
    </div>

    @if ($bookings->hasPages())
        <div class="admin-pagination">{{ $bookings->links() }}</div>
    @endif
    </div>
</x-layouts.dashboard>

