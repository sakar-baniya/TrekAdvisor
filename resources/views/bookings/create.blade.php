<x-app-layout>
    <div class="booking-flow-container">
        <div class="booking-steps">
            <div class="step active">1. Info</div>
            <div class="step">2. Passengers</div>
            <div class="step">3. Confirmation</div>
        </div>

        <div class="booking-card">
            <div class="trek-summary">
                <img src="{{ $departure->trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" alt="{{ $departure->trek->title }}">
                <div class="summary-details">
                    <h1>Booking: {{ $departure->trek->title }}</h1>
                    <p class="departure-date"><i class="fas fa-calendar"></i> {{ $departure->start_date->format('M d, Y') }} - {{ $departure->end_date->format('M d, Y') }}</p>
                    <p class="price-info"><i class="fas fa-tag"></i> ${{ number_format($departure->price) }} per person</p>
                </div>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" class="booking-form">
                @csrf
                <input type="hidden" name="departure_id" value="{{ $departure->id }}">
                
                <div class="form-group">
                    <label for="total_passengers">How many people are trekking?</label>
                    <div class="number-input">
                        <button type="button" onclick="decrement()">-</button>
                        <input type="number" name="total_passengers" id="total_passengers" value="1" min="1" max="{{ $departure->capacity - $departure->booked_seats }}" readonly>
                        <button type="button" onclick="increment()">+</button>
                    </div>
                    <p class="available-slots">{{ $departure->capacity - $departure->booked_seats }} slots available</p>
                </div>

                <div class="total-estimated">
                    <span>Estimated Total:</span>
                    <span id="estimated_price" data-price="{{ $departure->price }}">${{ number_format($departure->price) }}</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-next">Next: Passenger Details <i class="fas fa-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function increment() {
            let input = document.getElementById('total_passengers');
            let max = parseInt(input.getAttribute('max'));
            let val = parseInt(input.value);
            if(val < max) {
                input.value = val + 1;
                updatePrice();
            }
        }
        function decrement() {
            let input = document.getElementById('total_passengers');
            let val = parseInt(input.value);
            if(val > 1) {
                input.value = val - 1;
                updatePrice();
            }
        }
        function updatePrice() {
            let count = parseInt(document.getElementById('total_passengers').value);
            let price = parseFloat(document.getElementById('estimated_price').dataset.price);
            document.getElementById('estimated_price').innerText = '$' + (count * price).toLocaleString();
        }
    </script>

    <style>
        .booking-flow-container {
            max-width: 800px;
            margin: 4rem auto;
            padding: 0 2rem;
            font-family: 'Inter', sans-serif;
        }

        .booking-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }

        .booking-steps::before {
            content: '';
            position: absolute;
            top: 50%; left: 0; right: 0;
            height: 2px; background: #e2e8f0;
            z-index: 1;
        }

        .step {
            background: white;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            border: 2px solid #e2e8f0;
            color: #718096;
            font-weight: 700;
            font-size: 0.85rem;
            z-index: 2;
        }

        .step.active {
            border-color: #3182ce;
            color: #3182ce;
            background: #ebf8ff;
        }

        .booking-card {
            background: white;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 3rem;
        }

        .trek-summary {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid #edf2f7;
            align-items: center;
        }

        .trek-summary img {
            width: 150px; height: 100px;
            object-fit: cover;
            border-radius: 16px;
        }

        .summary-details h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #2d3748; }
        .summary-details p { color: #718096; font-size: 0.95rem; margin-bottom: 0.25rem; }
        .summary-details i { margin-right: 0.5rem; color: #3182ce; }

        .booking-form .form-group { margin-bottom: 3rem; text-align: center; }
        .booking-form label { display: block; font-size: 1.25rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem; }

        .number-input {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }

        .number-input button {
            width: 50px; height: 50px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            background: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .number-input button:hover { border-color: #3182ce; color: #3182ce; }

        .number-input input {
            width: 80px;
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            border: none;
            color: #3182ce;
        }

        .available-slots { margin-top: 1rem; color: #48bb78; font-weight: 600; font-size: 0.85rem; }

        .total-estimated {
            background: #f7fafc;
            padding: 2rem;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .total-estimated span:first-child { font-weight: 600; color: #4a5568; }
        .total-estimated span#estimated_price { font-size: 2rem; font-weight: 800; color: #2d3748; }

        .btn-next {
            width: 100%;
            background: #2d3748;
            color: white;
            padding: 1.25rem;
            border-radius: 16px;
            border: none;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-next:hover { background: #1a202c; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }
    </style>
</x-app-layout>
