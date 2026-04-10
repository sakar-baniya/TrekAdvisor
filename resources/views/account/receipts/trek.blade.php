<x-app-layout>
    <section class="account-shell">
        <div class="container">
            <div class="account-receipt">
                <div class="account-receipt__head">
                    <div>
                        <h1>Trek Booking Receipt</h1>
                        <p>Reference {{ $booking->booking_reference }}</p>
                    </div>
                    <span>{{ now()->format('M d, Y') }}</span>
                </div>

                <div class="account-receipt__actions">
                    <button type="button" class="market-button" onclick="window.print()">Download PDF</button>
                </div>

                <div class="account-receipt__section">
                    <h2>Booking</h2>
                    <p><strong>Trek:</strong> {{ $booking->departure?->trek?->title }}</p>
                    <p><strong>Dates:</strong> {{ optional($booking->departure?->start_date)->format('F d, Y') }} - {{ optional($booking->departure?->end_date)->format('F d, Y') }}</p>
                    <p><strong>Passengers:</strong> {{ $booking->total_passengers }}</p>
                    <p><strong>Status:</strong> {{ $booking->status }}</p>
                </div>

                <div class="account-receipt__section">
                    <h2>Passengers</h2>
                    @if ($booking->passengers->isEmpty())
                        <p>No passenger details recorded.</p>
                    @else
                        <div class="receipt-list">
                            @foreach ($booking->passengers as $passenger)
                                <div class="receipt-list__row">
                                    <span class="receipt-list__label">{{ $passenger->full_name }}</span>
                                    <span class="receipt-list__value">Age: {{ $passenger->age ?? 'N/A' }}</span>
                                    <span class="receipt-list__value">Passport: {{ $passenger->passport_number ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="account-receipt__section">
                    <h2>Payment</h2>
                    <p><strong>Amount:</strong> {{ $payment?->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</p>
                    <p><strong>Status:</strong> {{ $payment?->status ?? 'unpaid' }}</p>
                    <p><strong>Gateway:</strong> {{ strtoupper($payment?->gateway ?? 'N/A') }}</p>
                </div>

                <div class="account-actions">
                    <a href="{{ route('account.bookings.treks.show', $booking) }}" class="btn btn--primary">Back to Booking</a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
