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

    <style>
        .booking-flow-container { max-width: 800px; margin: 4rem auto; padding: 0 2rem; font-family: 'Inter', sans-serif; }
        .booking-steps { display: flex; justify-content: space-between; margin-bottom: 3rem; position: relative; }
        .booking-steps::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1; }
        .step { background: white; padding: 0.5rem 1.5rem; border-radius: 9999px; border: 2px solid #e2e8f0; color: #718096; font-weight: 700; font-size: 0.85rem; z-index: 2; }
        .step.active { border-color: #3182ce; color: #3182ce; background: #ebf8ff; }

        .booking-card { background: white; border-radius: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 3rem; }
        h2 { font-size: 2rem; font-weight: 800; color: #1a202c; margin-bottom: 0.5rem; }
        .subtitle { color: #718096; margin-bottom: 3rem; }

        .passenger-block { background: #f7fafc; padding: 2rem; border-radius: 24px; margin-bottom: 2rem; border: 1px solid #edf2f7; }
        .passenger-block h3 { font-size: 1.1rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem; }

        .grid-form { display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 1.5rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #4a5568; margin-bottom: 0.5rem; }
        .form-group input { width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-group input:focus { outline: none; border-color: #3182ce; box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1); }

        .btn-next { width: 100%; background: #3182ce; color: white; padding: 1.25rem; border-radius: 16px; border: none; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-top: 1rem; }
        .btn-next:hover { background: #2b6cb0; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(49, 130, 206, 0.2); }
    </style>
</x-app-layout>
