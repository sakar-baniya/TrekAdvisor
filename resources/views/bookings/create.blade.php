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

</x-app-layout>
