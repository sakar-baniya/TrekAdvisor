<div class="space-y-4">
    <!-- Primary Actions -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col gap-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Manage Booking</h3>
        
        <x-ui.button class="w-full justify-center py-2.5 text-sm font-medium" onclick="window.location.href='{{ route('account.bookings.treks.receipt', $booking) }}'">
            <i class="fas fa-file-invoice mr-2"></i> Download Receipt
        </x-ui.button>
        
        <x-ui.button variant="secondary" class="w-full justify-center py-2.5 text-sm font-medium bg-white border border-slate-200 hover:bg-slate-50 transition-colors" onclick="window.location.href='{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}'">
            <i class="fas fa-eye w-5 h-5 text-slate-400 text-center mr-1"></i> View Trek Details
        </x-ui.button>

        @if ($payment && $payment->status === 'pending' && in_array($payment->gateway, ['stripe', 'esewa']))
            <x-ui.button variant="secondary" class="w-full justify-center py-2.5 text-sm font-medium bg-white border border-slate-200 hover:bg-slate-50 text-blue-700 hover:text-blue-800 transition-colors" onclick="window.location.href='{{ route($payment->gateway . '.retry', $payment) }}'">
                <i class="fas fa-credit-card w-5 h-5 text-slate-400 text-center mr-1"></i> Pay Now
            </x-ui.button>
        @endif
    </div>

    <!-- Danger Zone -->
    @if (!in_array($booking->status, ['completed', 'cancelled']))
        <div class="bg-red-50/50 rounded-2xl border border-red-100 p-6 flex flex-col gap-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-red-900 mb-1">Danger Zone</h3>
            <p class="text-[10px] text-red-600 font-medium mb-1 leading-relaxed">
                Requests within 14 days of departure may incur fees. Eligibility will be confirmed by our team.
            </p>

            @if ($booking->status === 'cancellation_requested')
                <form method="POST" action="{{ route('account.bookings.treks.cancel-withdraw', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button variant="secondary" class="w-full justify-center py-2.5 text-sm font-medium bg-white border border-slate-200 hover:bg-slate-50 transition-colors text-slate-700" type="submit" onclick="return confirm('Withdraw cancellation request? This will keep your booking active.')">
                        Withdraw Request
                    </x-ui.button>
                </form>
            @else
                <form method="POST" action="{{ route('account.bookings.treks.cancel', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full inline-flex items-center justify-center py-2.5 text-sm font-medium bg-white text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors" onclick="return confirm('Request cancellation? Your request will be reviewed by our team.')">
                        Request Cancellation
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
