<x-app-layout>
    <div class="container booking-wrap">
        <div class="booking-progress booking-progress--step-1">
            <span class="is-active">1. Info</span>
            <span>2. Passengers</span>
            <span>3. Confirm</span>
        </div>

        <div class="booking-layout-card">
            <div class="booking-summary-hero">
                <img src="{{ $departure->trek->image ?? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b' }}" alt="{{ $departure->trek->title }}">
                <div>
                    <h1>{{ $departure->trek->title }}</h1>
                    <p><i class="fas fa-calendar"></i> {{ $departure->start_date->format('M d, Y') }} - {{ $departure->end_date->format('M d, Y') }}</p>
                    <p><i class="fas fa-tag"></i> ${{ number_format($departure->price, 0) }} per person</p>
                </div>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" class="booking-step-card">
                @csrf
                <input type="hidden" name="departure_id" value="{{ $departure->id }}">
                <h2>How many people are traveling?</h2>
                <p class="booking-subtext">Group discounts will apply automatically based on passenger count.</p>

                <div class="booking-counter">
                    <button type="button" onclick="decrement()">-</button>
                    <input type="number" name="total_passengers" id="total_passengers" value="1" min="1" max="{{ $departure->capacity - $departure->booked_seats }}" readonly>
                    <button type="button" onclick="increment()">+</button>
                </div>
                <p class="booking-subtext">{{ $departure->capacity - $departure->booked_seats }} slots available</p>

                <div class="booking-price-box">
                    <div><span>Price per person</span><strong>${{ number_format($departure->price, 0) }}</strong></div>
                    <div><span>Passengers</span><strong id="display_passengers">1</strong></div>
                    <div><span>Discount</span><strong id="display_discount">0%</strong></div>
                    <div class="total"><span>Estimated Total</span><strong id="estimated_price" data-price="{{ $departure->price }}">${{ number_format($departure->price, 0) }}</strong></div>
                </div>

                <div class="booking-note-card">
                    <strong>Before you continue</strong>
                    <ul>
                        <li>Group discounts are applied automatically.</li>
                        <li>You will enter passenger details on the next step.</li>
                        <li>Your booking reference will be created after confirmation.</li>
                    </ul>
                </div>

                <button type="submit" class="market-search-btn market-search-btn--full">Continue to Passenger Details</button>
            </form>
        </div>
    </div>

    <script>
        function discountFor(count) {
            if (count >= 10) return 15;
            if (count >= 6) return 10;
            if (count >= 3) return 5;
            return 0;
        }
        function increment() {
            let input = document.getElementById('total_passengers');
            let max = parseInt(input.getAttribute('max'));
            let val = parseInt(input.value);
            if (val < max) {
                input.value = val + 1;
                updatePrice();
            }
        }
        function decrement() {
            let input = document.getElementById('total_passengers');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                updatePrice();
            }
        }
        function updatePrice() {
            let count = parseInt(document.getElementById('total_passengers').value);
            let price = parseFloat(document.getElementById('estimated_price').dataset.price);
            let discount = discountFor(count);
            let subtotal = count * price;
            let total = subtotal - ((subtotal * discount) / 100);
            document.getElementById('display_passengers').innerText = count;
            document.getElementById('display_discount').innerText = discount + '%';
            document.getElementById('estimated_price').innerText = '$' + total.toLocaleString();
        }
        updatePrice();
    </script>
</x-app-layout>
