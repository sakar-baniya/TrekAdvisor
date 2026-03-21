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
                                <input type="text" name="passengers[{{ $i }}][name]" placeholder="Enter full name" class="market-input" required>
                            </label>
                            <label>
                                <span>Passport Number</span>
                                <input type="text" name="passengers[{{ $i }}][passport_no]" placeholder="Passport #" class="market-input" required>
                            </label>
                            <label>
                                <span>Age</span>
                                <input type="number" name="passengers[{{ $i }}][age]" placeholder="Age" min="1" max="120" class="market-input" required>
                            </label>
                        </div>
                    </div>
                @endfor

                <button type="submit" class="market-search-btn market-search-btn--full">Complete Booking & Pay</button>
            </form>
        </div>
    </div>
</x-app-layout>
