<x-app-layout>
    <section class="account-shell">
        <div class="container">
            <div class="account-header">
                <div>
                    <p class="market-kicker">My Account</p>
                    <h1>Payments</h1>
                    <p>Your payment history and transaction status.</p>
                </div>
            </div>

            <section class="account-panel">
                <div class="account-table">
                    <div class="account-table__row account-table__head">
                        <span>Transaction</span>
                        <span>Gateway</span>
                        <span>Amount</span>
                        <span>Status</span>
                        <span>Paid At</span>
                        <span>Booking</span>
                    </div>
                    @forelse ($payments as $payment)
                        @php
                            $bookingLink = $payment->payable_type === 'trek'
                                ? route('account.bookings.treks.show', $payment->payable_id)
                                : ($payment->payable_type === 'hotel' ? route('account.bookings.hotels.show', $payment->payable_id) : '#');
                        @endphp
                        <div class="account-table__row">
                            <span>{{ $payment->transaction_id }}</span>
                            <span>{{ strtoupper($payment->gateway ?? 'N/A') }}</span>
                            <span>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                            <span><x-account.status-badge :status="$payment->status" /></span>
                            <span>{{ $payment->paid_at?->format('M d, Y') ?? '—' }}</span>
                            <a href="{{ $bookingLink }}" class="account-link">View</a>
                        </div>
                    @empty
                        <div class="account-empty">
                            <p>No payments found yet.</p>
                        </div>
                    @endforelse
                </div>

                <div class="account-pagination">
                    {{ $payments->links() }}
                </div>
            </section>
        </div>
    </section>
</x-app-layout>
