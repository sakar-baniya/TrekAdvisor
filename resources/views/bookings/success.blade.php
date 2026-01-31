<x-app-layout>
    <div class="booking-flow-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Booking Confirmed!</h1>
            <p class="success-msg">Congratulations! Your trek to the mountains is officially booked. Get ready for the adventure of a lifetime.</p>
            
            <div class="booking-info">
                <div class="info-row">
                    <span>Reference Number:</span>
                    <strong>{{ $booking->booking_reference }}</strong>
                </div>
                <div class="info-row">
                    <span>Arrival Date:</span>
                    <strong>{{ $booking->departure->start_date->format('M d, Y') }}</strong>
                </div>
                <div class="info-row">
                    <span>Total Paid:</span>
                    <strong>${{ number_format($booking->total_price) }}</strong>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('customer.dashboard') }}" class="btn-dashboard">View My Bookings</a>
                <a href="{{ route('treks.index') }}" class="btn-home">Back to Gallery</a>
            </div>
        </div>
    </div>

    <style>
        .booking-flow-container { max-width: 600px; margin: 6rem auto; padding: 0 2rem; font-family: 'Inter', sans-serif; }
        
        .success-card { background: white; border-radius: 40px; padding: 4rem; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        
        .success-icon { font-size: 5rem; color: #48bb78; margin-bottom: 2rem; }
        
        h1 { font-size: 2.5rem; font-weight: 800; color: #1a202c; margin-bottom: 1rem; }
        .success-msg { color: #718096; line-height: 1.6; margin-bottom: 3rem; }

        .booking-info { background: #f7fafc; padding: 2rem; border-radius: 24px; text-align: left; margin-bottom: 3rem; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 1rem; }
        .info-row:last-child { margin-bottom: 0; padding-top: 1rem; border-top: 1px dashed #e2e8f0; }
        .info-row span { color: #718096; }
        .info-row strong { color: #2d3748; }

        .success-actions { display: flex; flex-direction: column; gap: 1rem; }
        
        .btn-dashboard { background: #3182ce; color: white; padding: 1.25rem; border-radius: 16px; font-weight: 700; text-decoration: none; transition: all 0.2s; }
        .btn-dashboard:hover { background: #2b6cb0; }

        .btn-home { color: #718096; font-weight: 600; text-decoration: none; transition: color 0.2s; }
        .btn-home:hover { color: #2d3748; }
    </style>
</x-app-layout>
