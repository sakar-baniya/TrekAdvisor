<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm animate-fadeIn">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
            <i class="fas fa-bed"></i>
        </div>
        <div>
            <h2 class="text-base font-semibold text-slate-900">{{ $booking->hotelRoom?->hotel?->name ?? 'Hotel Booking' }}</h2>
            <p class="text-sm text-slate-500">{{ $booking->hotelRoom?->room_type ? $booking->hotelRoom->room_type : 'Standard Room' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Check-In</span>
                <span class="text-sm font-semibold text-slate-900">{{ optional($booking->check_in)->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Check-Out</span>
                <span class="text-sm font-semibold text-slate-900">{{ optional($booking->check_out)->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Stay Duration</span>
                <span class="text-sm font-semibold text-slate-900">{{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }} × {{ $booking->num_nights }} Night{{ $booking->num_nights > 1 ? 's' : '' }}</span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Payment Status</span>
                <x-customer.status-badge :status="$paymentStatus" />
            </div>
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Total Price</span>
                <span class="text-sm font-bold text-slate-900 uppercase tracking-tight">NPR {{ number_format($booking->total_price) }}</span>
            </div>
            @if (!$payment)
                <div class="p-3 bg-amber-50/50 rounded-lg border border-amber-100/50">
                    <p class="text-[11px] font-medium text-amber-700 leading-tight">
                        <i class="fas fa-info-circle mr-1.5 opacity-70"></i> Confirm settlement with hotel front desk.
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if (filled($booking->hotelRoom?->hotel?->booking_policy))
        <div class="mt-8 pt-6 border-t border-slate-100">
            <h4 class="text-xs font-semibold text-slate-500 mb-3">Booking Policy</h4>
            <div class="text-[11px] leading-relaxed text-slate-500 bg-slate-50/50 p-4 rounded-lg border border-slate-100">
                {!! nl2br(e($booking->hotelRoom->hotel->booking_policy)) !!}
            </div>
        </div>
    @endif
</div>
