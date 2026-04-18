<div class="space-y-6">
    <!-- Primary Actions -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-4">Manage Booking</h3>
        
        <x-ui.button class="w-full justify-center" onclick="window.location.href='{{ route('account.bookings.treks.receipt', $booking) }}'">
            <i class="fas fa-file-invoice mr-2"></i> Download Receipt
        </x-ui.button>
        
        <x-ui.button variant="secondary" class="w-full justify-center" onclick="window.location.href='{{ route('treks.show', $booking->departure?->trek?->slug ?? '#') }}'">
            <i class="fas fa-eye mr-2"></i> View Trek Details
        </x-ui.button>

        @if ($payment && $payment->status === 'pending' && $payment->gateway === 'stripe')
            <x-ui.button variant="secondary" class="w-full bg-blue-50 text-blue-700 border-blue-100 hover:bg-blue-100 justify-center" onclick="window.location.href='{{ route('stripe.retry', $payment) }}'">
                <i class="fas fa-credit-card mr-2"></i> Pay Now
            </x-ui.button>
        @endif
    </div>

    <!-- Danger Zone -->
    @if (!in_array($booking->status, ['completed', 'cancelled']))
        <div class="bg-red-50/50 p-6 rounded-2xl border border-red-100">
            <h3 class="text-[10px] font-black text-red-900 uppercase tracking-widest mb-2">Danger Zone</h3>
            <p class="text-[10px] text-red-600 font-medium mb-4 leading-relaxed">
                Requests within 14 days of departure may incur fees. Eligibility will be confirmed by our team.
            </p>

            @if ($booking->status === 'cancellation_requested')
                <form method="POST" action="{{ route('account.bookings.treks.cancel-withdraw', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button variant="secondary" class="w-full justify-center bg-white" type="submit" onclick="return confirm('Withdraw cancellation request? This will keep your booking active.')">
                        Withdraw Request
                    </x-ui.button>
                </form>
            @else
                <form method="POST" action="{{ route('account.bookings.treks.cancel', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button variant="danger" class="w-full justify-center" type="submit" onclick="return confirm('Request cancellation? Your request will be reviewed by our team.')">
                        Request Cancellation
                    </x-ui.button>
                </form>
            @endif
        </div>
    @endif
</div>
