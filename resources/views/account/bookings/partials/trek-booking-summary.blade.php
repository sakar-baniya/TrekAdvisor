<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm animate-fadeIn">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
            <i class="fas fa-mountain"></i>
        </div>
        <div>
            <h2 class="text-base font-semibold text-slate-900">{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h2>
            <p class="text-sm text-slate-500">Essential details for your Himalayan adventure.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Trek Dates</span>
                <span class="text-sm font-semibold text-slate-900">{{ optional($booking->departure?->start_date)->format('M d') }} — {{ optional($booking->departure?->end_date)->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <span class="text-xs text-slate-500">Total Travelers</span>
                <span class="text-sm font-semibold text-slate-900">{{ $booking->total_passengers }} Person{{ $booking->total_passengers > 1 ? 's' : '' }}</span>
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
        </div>
    </div>
</div>
