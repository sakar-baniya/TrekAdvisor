<div class="space-y-6">
    <!-- Primary Actions -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-semibold text-slate-400 mb-4">Manage Reservation</h3>
        
        <x-ui.button class="w-full justify-center" onclick="window.location.href='{{ route('account.bookings.hotels.receipt', $booking) }}'">
            <i class="fas fa-file-invoice mr-2 opacity-70"></i> Download Receipt
        </x-ui.button>
        
        <x-ui.button variant="secondary" class="w-full justify-center" onclick="window.location.href='{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}'">
            <i class="fas fa-eye mr-2 opacity-70"></i> View Hotel Details
        </x-ui.button>

        @if ($payment && $payment->status === 'pending' && $payment->gateway === 'stripe')
            <x-ui.button variant="secondary" class="w-full bg-blue-50/50 text-blue-700 border-blue-100 hover:bg-blue-100 justify-center" onclick="window.location.href='{{ route('stripe.retry', $payment) }}'">
                <i class="fas fa-credit-card mr-2 opacity-70"></i> Pay Now
            </x-ui.button>
        @endif
    </div>

    <!-- Danger Zone -->
    @if (!in_array($booking->status, ['completed', 'cancelled']))
        <div class="bg-red-50/30 p-6 rounded-xl border border-red-100/50">
            <h3 class="text-xs font-semibold text-red-900 mb-2">Cancellation</h3>
            <p class="text-[11px] text-red-600/70 mb-4 leading-relaxed">
                Cancellations within 48 hours of check-in may incur fees.
            </p>

            @if ($booking->status === 'cancellation_requested')
                <form method="POST" action="{{ route('account.bookings.hotels.cancel-withdraw', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button variant="secondary" class="w-full justify-center bg-white" type="submit" onclick="return confirm('Withdraw cancellation request?')">
                        Withdraw Request
                    </x-ui.button>
                </form>
            @else
                <form method="POST" action="{{ route('account.bookings.hotels.cancel', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button variant="danger" class="w-full justify-center" type="submit" onclick="return confirm('Request cancellation?')">
                        Request Cancellation
                    </x-ui.button>
                </form>
            @endif
        </div>
    @endif
</div>
