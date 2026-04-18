<x-layouts.dashboard>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Booking Details</p>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                    #{{ $booking->booking_reference }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">Management portal for this reservation</p>
            </div>
            
            <div>
                <a href="{{ route('hotel_owner.bookings.index') }}" 
                   class="inline-flex items-center bg-white border border-slate-200 hover:bg-slate-50 rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2 text-xs"></i>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
        <!-- Main Content (Left/Middle) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Guest Info Card -->
            <div class="bg-white border border-slate-200/70 rounded-2xl p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-6">Guest Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Guest</p>
                        <p class="text-sm font-medium text-slate-900">{{ $booking->user?->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->user?->email }}</p>
                    </div>
                    
                    <div class="pt-6 border-t border-slate-100">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Stay Dates</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border border-slate-200 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Check In</p>
                                <p class="text-sm font-semibold text-slate-900">{{ optional($booking->check_in)->format('M d, Y') }}</p>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Check Out</p>
                                <p class="text-sm font-semibold text-slate-900">{{ optional($booking->check_out)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Room</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                                <i class="fas fa-door-open text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $booking->hotelRoom?->room_type ?? 'Standard Room' }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }} · {{ $booking->num_nights }} Night{{ $booking->num_nights > 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="bg-white border border-slate-200/70 rounded-2xl p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Payment</h3>
                
                <div class="divide-y divide-slate-100">
                    <div class="flex justify-between py-3 text-sm">
                        <span class="text-slate-500 font-medium">Rate per Night</span>
                        <span class="text-slate-900 font-semibold">NPR {{ number_format($booking->price_per_night) }}</span>
                    </div>
                    <div class="flex justify-between py-3 text-sm">
                        <span class="text-slate-500 font-medium">Rooms × Nights</span>
                        <span class="text-slate-900 font-semibold">{{ $booking->num_rooms }} × {{ $booking->num_nights }}</span>
                    </div>
                    <div class="flex justify-between py-3 text-base">
                        <span class="text-slate-900 font-bold">Total</span>
                        <span class="text-slate-900 font-bold">NPR {{ number_format($booking->total_price) }}</span>
                    </div>
                </div>

                <button type="button" class="bg-slate-900 text-white hover:bg-slate-800 rounded-xl px-4 py-2.5 text-sm font-semibold w-full mt-4 transition-colors">
                    Mark as Paid
                </button>

                @if (filled($booking->hotelRoom?->hotel?->booking_policy))
                    <div class="mt-6 pt-6 border-t border-slate-100 uppercase tracking-wider text-[10px] font-bold text-slate-400 mb-2">
                        Booking Policy
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl italic">
                        {!! nl2br(e($booking->hotelRoom->hotel->booking_policy)) !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:col-span-1">
            <!-- Update Status Card -->
            <div class="bg-white border border-slate-200/70 rounded-2xl p-6 sticky top-24">
                <h3 class="text-base font-semibold text-slate-900 mb-6">Update Status</h3>
                
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 text-xs font-bold rounded-xl border border-green-100">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('hotel_owner.bookings.status', $booking) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-6">
                        <select name="status" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-slate-900/20 transition-all cursor-pointer">
                            @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancellation_requested' => 'Cancel Requested', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" 
                            class="bg-slate-900 text-white hover:bg-slate-800 rounded-xl px-4 py-2.5 text-sm font-semibold w-full transition-all active:scale-[0.98]">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

