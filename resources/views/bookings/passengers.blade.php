<x-app-layout>
    <div class="booking-flow-container">
        <div class="booking-steps">
            <div class="step">1. Info</div>
            <div class="step active">2. Passengers</div>
            <div class="step">3. Confirmation</div>
        </div>

        <div class="booking-card">
            <h2>Passenger Details</h2>
            <p class="subtitle">Please provide the details for all guests on this trek.</p>

            <form action="{{ route('bookings.confirm') }}" method="POST" class="passenger-form">
                @csrf
                @for($i = 0; $i < $bookingData['total_passengers']; $i++)
                    <div class="passenger-block">
                        <h3>Adventurer #{{ $i + 1 }}</h3>
                        <div class="grid-form">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="passengers[{{ $i }}][name]" placeholder="Enter full name" required>
                            </div>
                            <div class="form-group">
                                <label>Passport Number</label>
                                <input type="text" name="passengers[{{ $i }}][passport_no]" placeholder="Passport #" required>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="passengers[{{ $i }}][age]" placeholder="Age" min="1" max="120" required>
                            </div>
                        </div>
                    </div>
                @endfor

                <div class="form-actions">
                    <button type="submit" class="btn-next">Complete Booking & Pay <i class="fas fa-check"></i></button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
