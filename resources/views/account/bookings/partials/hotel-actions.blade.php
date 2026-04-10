<section class="account-panel account-panel--actions">
    <div class="account-panel__head">
        <div>
            <h2>Actions</h2>
            <p>Download your receipt or manage this booking.</p>
        </div>
    </div>

    <div class="account-actions account-actions--priority">
        <div class="account-actions__group">
            <a href="{{ route('account.bookings.hotels.receipt', $booking) }}" class="btn btn--primary">Download Receipt</a>
            <a href="{{ route('hotels.show', $booking->hotelRoom?->hotel ?? '#') }}" class="btn btn--secondary">View Hotel</a>
            @if ($payment && $payment->status === 'pending' && $payment->gateway === 'stripe')
                <a href="{{ route('stripe.retry', $payment) }}" class="btn btn--secondary">Pay Now</a>
            @endif
        </div>
        @if (!in_array($booking->status, ['completed', 'cancelled']))
            @if ($booking->status === 'cancellation_requested')
                <form method="POST" action="{{ route('account.bookings.hotels.cancel-withdraw', $booking) }}" data-confirm-form class="account-actions__group account-actions__group--right">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn--secondary" data-confirm-open data-confirm-title="Withdraw cancellation request?" data-confirm-message="This will keep your booking active and remove the cancellation request.">Withdraw Cancellation</button>
                </form>
            @else
                <form method="POST" action="{{ route('account.bookings.hotels.cancel', $booking) }}" data-confirm-form class="account-actions__group account-actions__group--right">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn--danger" data-confirm-open data-confirm-title="Request cancellation?" data-confirm-message="Your cancellation request will be reviewed and may incur fees based on hotel policy.">Request Cancellation</button>
                </form>
            @endif
        @endif
    </div>

    <p class="account-note">Cancellations requested within 48 hours of check-in may incur fees. Our team will confirm eligibility and refund details.</p>

    @include('account.bookings.partials.confirm-modal')
</section>
