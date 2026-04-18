<x-app-layout>
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Indicator (Final Step) -->
            <div class="flex items-center justify-between mb-12 relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-200 -z-0"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Info</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Passengers</span>
                </div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold shadow-lg shadow-slate-900/20">3</div>
                    <span class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-900">Confirm</span>
                </div>
            </div>

            @php
                $paymentStatus = $payment->status ?? 'Pending';
                $isPaid = strtolower((string) $paymentStatus) === 'success';
                $isCancelled = $checkoutCancelled ?? false;
                $gateway = strtolower((string) ($payment->gateway ?? 'stripe'));
                $gatewayLabel = strtoupper($gateway);
                $retryRoute = $gateway === 'esewa' ? route('esewa.retry', $payment) : route('stripe.retry', $payment);
                
                $themeColor = $isPaid ? 'emerald' : ($isCancelled ? 'red' : 'amber');
                $icon = $isPaid ? 'fa-check-circle' : ($isCancelled ? 'fa-circle-xmark' : 'fa-clock');
            @endphp

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-900/5 border border-slate-100 overflow-hidden text-center p-10 md:p-16">
                <!-- Status Icon -->
                <div class="w-24 h-24 rounded-full bg-{{ $themeColor }}-100 text-{{ $themeColor }}-600 flex items-center justify-center text-5xl mb-8 mx-auto animate-in zoom-in duration-500">
                    <i class="fas {{ $icon }}"></i>
                </div>

                <h1 class="text-3xl md:text-4xl font-semibold text-slate-900 tracking-tight mb-4">
                    {{ $isPaid ? 'Payment Received!' : ($isCancelled ? 'Checkout Cancelled' : 'Payment Pending') }}
                </h1>
                
                <p class="text-slate-500 font-medium max-w-lg mx-auto leading-relaxed mb-12">
                    @if ($isPaid)
                        Your trek booking is confirmed and your {{ $gatewayLabel }} payment has been recorded successfully. You are all set for your adventure!
                    @elseif ($isCancelled)
                        Your booking was saved as pending. You can retry the {{ $gatewayLabel }} checkout at any time from your dashboard or using the button below.
                    @else
                        Your booking has been created and we are waiting for payment confirmation from {{ $gatewayLabel }}. This usually takes a few minutes.
                    @endif
                </p>

                <!-- Summary Info -->
                <div class="bg-slate-50 rounded-3xl p-8 space-y-4 border border-slate-100 mb-12 text-left shadow-inner">
                    <div class="flex justify-between items-center text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        <span>Reference</span>
                        <span class="text-slate-900">{{ $booking->booking_reference }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        <span>Trek Date</span>
                        <span class="text-slate-900">{{ $booking->departure->start_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        <span>Status</span>
                        <span class="text-slate-900">{{ $booking->status }}</span>
                    </div>
                    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Total NPR</span>
                        <strong class="text-3xl font-semibold text-slate-900 tracking-tight">NPR {{ number_format($booking->total_price, 0) }}</strong>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @if (! $isPaid)
                        <a href="{{ $retryRoute }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white text-sm font-semibold rounded-2xl shadow-xl hover:bg-slate-800 transition-all uppercase tracking-widest">
                            Retry Payment
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white text-sm font-semibold rounded-2xl shadow-xl hover:bg-slate-800 transition-all uppercase tracking-widest">
                            My Dashboard
                        </a>
                    @endif
                    <a href="{{ route('treks.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-900 uppercase tracking-widest underline underline-offset-8 decoration-slate-200">
                        Back to Treks
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
