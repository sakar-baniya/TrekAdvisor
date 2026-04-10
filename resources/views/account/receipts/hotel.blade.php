<x-app-layout>
    <section class="account-shell">
        <div class="container">
            <div class="account-receipt">
                <div class="account-receipt__head">
                    <div>
                        <h1>Hotel Booking Receipt</h1>
                        <p>Reference {{ $booking->booking_reference ?? 'Hotel stay' }}</p>
                    </div>
                    <span>{{ now()->format('M d, Y') }}</span>
                </div>

                <div class="account-receipt__actions">
                    <button type="button" class="market-button" onclick="window.print()">Download PDF</button>
                </div>

                <div class="account-receipt__section">
                    <h2>Booking</h2>
                    <p><strong>Hotel:</strong> {{ $booking->hotelRoom?->hotel?->name }}</p>
                    <p><strong>Dates:</strong> {{ optional($booking->check_in)->format('F d, Y') }} - {{ optional($booking->check_out)->format('F d, Y') }}</p>
                    <p><strong>Rooms:</strong> {{ $booking->num_rooms }} rooms</p>
                    <p><strong>Status:</strong> {{ $booking->status }}</p>
                </div>

                <div class="account-receipt__section">
                    <h2>Passengers</h2>
                    <p>No passenger details are recorded for hotel stays.</p>
                </div>

                <div class="account-receipt__section">
                    <h2>Payment</h2>
                    <p><strong>Amount:</strong> {{ $payment?->currency ?? 'USD' }} {{ number_format($booking->total_price, 2) }}</p>
                    <p><strong>Status:</strong> {{ $payment?->status ?? 'unpaid' }}</p>
                    <p><strong>Gateway:</strong> {{ strtoupper($payment?->gateway ?? 'N/A') }}</p>
                </div>

                <div class="account-actions">
                    <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="btn btn--primary">Back to Booking</a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
