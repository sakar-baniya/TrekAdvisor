<section class="account-panel account-panel--summary">
    <div class="account-panel__head">
        <div>
            <h2>{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</h2>
            <p>Booking summary for this trek.</p>
        </div>
    </div>

    <div class="account-summary-grid">
        <div class="account-summary-card">
            <div class="account-summary-row">
                <span class="account-summary-label">Dates</span>
                <span>{{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Passengers</span>
                <span>{{ $booking->total_passengers }}</span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Trek</span>
                <span>{{ $booking->departure?->trek?->title ?? 'Trek Booking' }}</span>
            </div>
        </div>
        <div class="account-summary-card">
            <div class="account-summary-row">
                <span class="account-summary-label">Payment status</span>
                <span><x-account.status-badge :status="$paymentStatus" /></span>
            </div>
            <div class="account-summary-row">
                <span class="account-summary-label">Total price</span>
                <span class="account-summary-price">NPR {{ number_format($booking->total_price, 2) }}</span>
            </div>
            @if ($payment)
                <div class="account-summary-row">
                    <span class="account-summary-label">Payment method</span>
                    <span>{{ ucfirst($payment->gateway) }}</span>
                </div>
                <div class="account-summary-row">
                    <span class="account-summary-label">Paid at</span>
                    <span>{{ optional($payment->paid_at)->format('F d, Y') ?? 'Pending' }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
