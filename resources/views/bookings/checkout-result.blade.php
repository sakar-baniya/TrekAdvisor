<x-app-layout>
    <div class="container booking-wrap">
        <div class="booking-progress booking-progress--step-3">
            <span>1. Info</span>
            <span>2. Passengers</span>
            <span class="is-active">3. Confirm</span>
        </div>

        @php
            $paymentStatus = $payment->status ?? 'Pending';
            $isPaid = strtolower((string) $paymentStatus) === 'success';
            $isCancelled = $checkoutCancelled ?? false;
            $gateway = strtolower((string) ($payment->gateway ?? 'stripe'));
            $gatewayLabel = strtoupper($gateway);
            $retryRoute = $gateway === 'esewa' ? route('esewa.retry', $payment) : route('stripe.retry', $payment);
        @endphp

        <div class="booking-success-card">
            <div class="booking-success-card__icon">
                <i class="fas {{ $isPaid ? 'fa-check-circle' : ($isCancelled ? 'fa-circle-pause' : 'fa-clock') }}"></i>
            </div>
            <h1>{{ $isPaid ? 'Payment Received!' : ($isCancelled ? 'Checkout Cancelled' : 'Payment Pending') }}</h1>
            <p>
                @if ($isPaid)
                    Your trek booking is confirmed and your {{ $gatewayLabel }} payment has been recorded successfully.
                @elseif ($isCancelled)
                    Your booking was saved as pending. You can retry the {{ $gatewayLabel }} checkout any time from the button below.
                @else
                    Your booking has been created and we are waiting for payment confirmation from {{ $gatewayLabel }}.
                @endif
            </p>

            <div class="booking-price-box booking-price-box--success">
                <div><span>Reference Number</span><strong>{{ $booking->booking_reference }}</strong></div>
                <div><span>Departure Date</span><strong>{{ $booking->departure->start_date->format('M d, Y') }}</strong></div>
                <div><span>Status</span><strong>{{ $booking->status }}</strong></div>
                <div class="total"><span>Total</span><strong>NPR {{ number_format($booking->total_price, 0) }}</strong></div>
            </div>

            <div class="booking-success-actions">
                @if (! $isPaid)
                    <a href="{{ $retryRoute }}" class="market-search-btn market-search-btn--full">Retry Payment</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="market-search-btn market-search-btn--full">View My Bookings</a>
                @endif
                <a href="{{ route('treks.index') }}" class="market-link market-link--center">Back to Treks</a>
            </div>
        </div>
    </div>
</x-app-layout>
