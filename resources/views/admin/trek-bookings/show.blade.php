<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading admin-page-heading--split">
            <div>
                <p class="admin-eyebrow">Trek Operations</p>
                <h2 class="admin-page-title">Booking {{ $booking->booking_reference }}</h2>
            </div>
            <a href="{{ route('admin.trek-bookings.index') }}" class="admin-secondary-button">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Bookings</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="admin-flash success">{{ session('success') }}</div>
    @endif

    <section class="admin-show-grid">
        <article class="admin-panel">
            <div class="admin-panel__header">
                <div>
                    <h3>Booking Details</h3>
                    <p>Main booking information and passenger list</p>
                </div>
            </div>

            <div class="admin-info-list">
                <div><span>Customer</span><strong>{{ $booking->user?->name }} ({{ $booking->user?->email }})</strong></div>
                <div><span>Trek</span><strong>{{ $booking->departure?->trek?->title }}</strong></div>
                <div><span>Departure</span><strong>{{ optional($booking->departure?->start_date)->format('M d') }} - {{ optional($booking->departure?->end_date)->format('M d, Y') }}</strong></div>
                <div><span>Total Passengers</span><strong>{{ $booking->total_passengers }}</strong></div>
                <div><span>Payment</span><strong>{{ $payment?->status ?? 'No payment found' }}{{ $payment?->gateway ? ' (' . ucfirst($payment->gateway) . ')' : '' }}</strong></div>
            </div>

            <div class="admin-panel__header">
                <div>
                    <h3>Passengers</h3>
                    <p>Traveller records saved with this booking</p>
                </div>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Passport</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($booking->passengers as $passenger)
                            <tr>
                                <td>{{ $passenger->name }}</td>
                                <td>{{ $passenger->age }}</td>
                                <td>{{ $passenger->passport_no ?: 'Not provided' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-table__empty">No passenger details found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <aside class="admin-side-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Pricing</h3>
                        <p>Booking total breakdown</p>
                    </div>
                </div>
                <div class="admin-info-list">
                    <div><span>Base Price</span><strong>${{ number_format($booking->price_per_person, 2) }} x {{ $booking->total_passengers }}</strong></div>
                    <div><span>Subtotal</span><strong>${{ number_format($booking->subtotal, 2) }}</strong></div>
                    <div><span>Discount</span><strong>{{ $booking->discount_percent }}% (-${{ number_format($booking->discount_amount, 2) }})</strong></div>
                    <div><span>Final Total</span><strong>${{ number_format($booking->total_price, 2) }}</strong></div>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Status</h3>
                        <p>Update the current booking state</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.trek-bookings.status', $booking) }}" class="admin-status-form">
                    @csrf
                    @method('PATCH')
                    <label class="admin-field">
                        <span>Booking Status</span>
                        <select name="status" class="admin-input">
                            @foreach (['Pending', 'Confirmed', 'Cancelled'] as $option)
                                <option value="{{ $option }}" @selected($booking->status === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="admin-primary-button admin-primary-button--fit">
                        <i class="fas fa-floppy-disk"></i>
                        <span>Save Status</span>
                    </button>
                </form>
            </section>
        </aside>
    </section>
</x-dashboard-layout>
