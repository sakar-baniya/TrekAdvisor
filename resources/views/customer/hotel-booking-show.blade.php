<x-app-layout>
    @php
        $statusColors = match (strtolower($booking->status)) {
            'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    @endphp

    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Dashboard
                    </a>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">Hotel Reservation</h1>
                    <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-xs">Reference: {{ $booking->booking_reference ?? 'Pending Reference' }}</p>
                </div>
                <div class="px-6 py-2 rounded-full border text-xs font-black uppercase tracking-widest shadow-sm {{ $statusColors }}">
                    {{ $booking->status }}
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-900/5 border border-slate-100 overflow-hidden">
                <div class="p-10 md:p-12">
                    <div class="flex flex-col md:flex-row gap-10">
                        <!-- Icon/Visual -->
                        <div class="w-20 h-20 rounded-3xl bg-slate-900 text-white flex items-center justify-center text-3xl shrink-0 shadow-xl shadow-slate-900/20">
                            <i class="fas fa-hotel"></i>
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-grow space-y-8">
                            <div>
                                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Stay' }}</h2>
                                <p class="text-slate-500 font-bold text-lg italic opacity-80">{{ $booking->hotelRoom?->room_type ? $booking->hotelRoom->room_type : 'Room accommodation' }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-t border-slate-50">
                                <div class="space-y-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Check-in</span>
                                            <strong class="text-slate-900">{{ optional($booking->check_in)->format('F d, Y') }}</strong>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                                            <i class="fas fa-calendar-times"></i>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Check-out</span>
                                            <strong class="text-slate-900">{{ optional($booking->check_out)->format('F d, Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <i class="fas fa-bed"></i>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Accommodation</span>
                                            <strong class="text-slate-900">{{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }} x {{ $booking->num_nights }} Night{{ $booking->num_nights > 1 ? 's' : '' }}</strong>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total NPR</span>
                                            <strong class="text-xl font-black text-slate-900 tracking-tight">NPR {{ number_format($booking->total_price, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                                <a href="{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}" class="w-full sm:w-auto inline-flex justify-center items-center py-4 px-8 bg-slate-900 text-white text-xs font-black rounded-2xl hover:bg-slate-800 transition-all uppercase tracking-widest shadow-xl shadow-slate-900/10">
                                    View Hotel
                                </a>
                                <a href="{{ route('customer.dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center py-4 px-8 bg-white border border-slate-200 text-slate-600 text-xs font-black rounded-2xl hover:bg-slate-50 transition-all uppercase tracking-widest">
                                    Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
