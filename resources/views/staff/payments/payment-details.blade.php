<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <h2 class="admin-page-title">Payment {{ $payment->transaction_id }}</h2>
            </div>
            <a href="{{ route('staff.payments.index') }}" class="admin-secondary-button">
                <span>Back to Queue</span>
            </a>
        </div>
    </x-slot>

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Payment Details</h3>
                    <p>Main transaction and customer details</p>
                </div>
            </div>
            <div class="admin-info-list">
                <div><span>Customer</span><strong>{{ $payment->user?->name }} ({{ $payment->user?->email }})</strong></div>
                <div><span>Type</span><strong>{{ ucfirst($payment->payable_type) }}</strong></div>
                <div><span>Amount</span><strong>{{ $payment->currency ?? 'NPR' }} {{ number_format($payment->amount, 2) }}</strong></div>
                <div><span>Gateway</span><strong>{{ $payment->gateway ? ucfirst($payment->gateway) : 'N/A' }}</strong></div>
                <div><span>Status</span><strong>{{ $payment->status }}</strong></div>
                <div><span>Created</span><strong>{{ $payment->created_at->format('M d, Y h:i A') }}</strong></div>
            </div>
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Reference</h3>
                        <p>Linked booking record</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Reference ID</span><strong>{{ $payment->payable_id }}</strong></div>
                    @if ($reference)
                        <div><span>Customer</span><strong>{{ $reference->user?->name ?? 'Unknown' }}</strong></div>
                        @if ($payment->payable_type === 'trek')
                            <div><span>Trek</span><strong>{{ $reference->departure?->trek?->title }}</strong></div>
                            <div><span>Booking Ref</span><strong>{{ $reference->booking_reference }}</strong></div>
                        @elseif ($payment->payable_type === 'hotel')
                            <div><span>Hotel</span><strong>{{ $reference->hotelRoom?->hotel?->name }}</strong></div>
                            <div><span>Booking Ref</span><strong>{{ $reference->booking_reference }}</strong></div>
                        @endif
                    @else
                        <div><span>Linked record</span><strong>Not found</strong></div>
                    @endif
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Gateway Response</h3>
                        <p>Raw gateway payload</p>
                    </div>
                </div>
                <div class="admin-code-block">
                    <pre>{{ $payment->gateway_response ?: 'No gateway response saved.' }}</pre>
                </div>
            </section>
        </aside>
    </section>
</x-dashboard-layout>
