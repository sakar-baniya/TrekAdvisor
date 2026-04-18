<div class="bg-white rounded-2xl border border-slate-200 p-6 animate-fadeIn">
    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
        <i class="fas fa-mountain w-5 h-5 text-slate-400 text-center"></i>
        <div>
            <h2 class="text-lg font-semibold text-slate-900">{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h2>
            <p class="text-sm text-slate-500">Essential details for your Himalayan adventure.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-x-8 gap-y-4">
        <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Trek Dates</div>
            <div class="text-sm font-semibold text-slate-900">{{ optional($booking->departure?->start_date)->format('M d') }} — {{ optional($booking->departure?->end_date)->format('M d, Y') }}</div>
        </div>
        <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Total Travelers</div>
            <div class="text-sm font-semibold text-slate-900">{{ $booking->total_passengers }} Person{{ $booking->total_passengers > 1 ? 's' : '' }}</div>
        </div>
        <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Payment Status</div>
            <div class="mt-1"><x-customer.status-badge :status="$paymentStatus" /></div>
        </div>
        <div>
            <div class="text-xs font-medium uppercase tracking-wider text-slate-500 mb-1">Total Price</div>
            <div class="text-sm font-semibold text-slate-900">NPR {{ number_format($booking->total_price) }}</div>
        </div>
    </div>
</div>
