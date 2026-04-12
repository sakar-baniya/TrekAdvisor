<x-app-layout>
    <div class="container booking-wrap">
        <div class="booking-progress booking-progress--step-2">
            <span>1. Info</span>
            <span class="is-active">2. Passengers</span>
            <span>3. Confirm</span>
        </div>

        <div class="booking-layout-card">
            <form action="{{ route('bookings.confirm') }}" method="POST" class="booking-step-card booking-step-card--wide">
                @csrf
                <h1>Passenger Details</h1>
                <p class="booking-subtext">Please provide the details for everyone on this trek booking.</p>

                @for($i = 0; $i < $bookingData['total_passengers']; $i++)
                    <div class="booking-passenger-block">
                        <h3>Passenger #{{ $i + 1 }}</h3>
                        <div class="booking-passenger-grid">
                            <label>
                                <span>Full Name</span>
                                <input type="text" name="passengers[{{ $i }}][full_name]" placeholder="Enter full name" class="market-input" required>
                            </label>
                            <label>
                                <span>Passport Number</span>
                                <input type="text" name="passengers[{{ $i }}][passport_number]" placeholder="Passport #" class="market-input" required>
                            </label>
                            <label>
                                <span>Age</span>
                                <input type="number" name="passengers[{{ $i }}][age]" placeholder="Age" min="1" max="120" class="market-input" required>
                            </label>
                        </div>
                    </div>
                @endfor

                <div class="booking-note-card">
                    <strong>Need to know</strong>
                    <ul>
                        <li>Passenger names should match travel documents.</li>
                        <li>Passport information helps prepare the booking record.</li>
                        <li>You can review totals after confirmation.</li>
                    </ul>
                </div>

                <div class="booking-note-card">
                    <strong>Payment method</strong>
                    <div style="margin-top: 12px; display: flex; gap: 16px; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                            <input type="radio" name="payment_method" value="stripe" checked>
                            <span>Stripe (Card)</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                            <input type="radio" name="payment_method" value="esewa">
                            <span>eSewa</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <p style="color: #dc2626; margin-top: 8px; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="market-search-btn market-search-btn--full">Complete Booking & Pay</button>
            </form>
        </div>
    </div>
</x-app-layout>
