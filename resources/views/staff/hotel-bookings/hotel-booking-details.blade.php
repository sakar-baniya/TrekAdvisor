@section('page-title', 'Booking Management')
@section('page-subtitle', 'Ref: ' . $booking->booking_reference)
@section('page-back', route('staff.hotel-bookings.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
        <!-- Main Booking Content -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">Stay Information</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Primary details of the hotel reservation</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-lg">
                        <i class="fas fa-hotel"></i>
                    </div>
                </div>

                <div class="px-6 py-8 grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-6">
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Guest</span>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($booking->user?->name ?? 'G', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $booking->user?->name ?? 'Guest' }}</p>
                                <p class="text-xs text-slate-500 leading-none">{{ $booking->user?->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Property</span>
                        <p class="text-sm font-semibold text-slate-900 leading-tight">{{ $booking->hotelRoom?->hotel?->name }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->hotelRoom?->room_type }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Check-in / Check-out</span>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ optional($booking->check_in)->format('M d, Y') }} — {{ optional($booking->check_out)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Accommodation</span>
                        <p class="text-sm font-semibold text-slate-900">{{ $booking->num_rooms }} Room(s) · {{ $booking->num_nights }} Night(s)</p>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Payment Status</span>
                        <div class="flex items-center gap-2">
                             @php $payment = $booking->payments()->where('status', 'success')->latest()->first(); @endphp
                            @if ($payment)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Paid ({{ ucfirst($payment->gateway) }})</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-100">Action Required</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </article>

        <!-- Sidebar Stats -->
         <aside class="space-y-8">
            <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Pricing Summary</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Booking total breakdown</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Rate per Night</span>
                        <span class="font-semibold text-slate-900">NPR {{ number_format($booking->price_per_night, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Duration</span>
                        <span class="font-semibold text-slate-900">x {{ $booking->num_nights }} Night(s)</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Room(s)</span>
                        <span class="font-semibold text-slate-900">x {{ $booking->num_rooms }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-medium text-slate-500 mb-1">Final Total</p>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight">NPR {{ number_format($booking->total_price, 2) }}</p>
                    </div>
                </div>
            </section>

            <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Status Update</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Update booking state</p>
                </div>

                <form method="POST" action="{{ route('staff.hotel-bookings.status', $booking) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Current Lifecycle</label>
                        <select name="status" class="w-full bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all appearance-none cursor-pointer">
                            @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancellation_requested' => 'Cancellation Requested', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-all shadow-sm" onclick="return confirm('Update hotel booking status?')">
                        <i class="fas fa-check-circle text-xs opacity-70"></i>
                        <span>Apply Changes</span>
                    </button>
                </form>
            </section>
        </aside>
    </div>
</x-layouts.dashboard>

