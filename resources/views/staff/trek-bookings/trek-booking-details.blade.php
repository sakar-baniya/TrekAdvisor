@section('page-title', 'Booking Management')
@section('page-subtitle', 'Ref: ' . $booking->booking_reference)
@section('page-back', route('staff.trek-bookings.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
        <!-- Main Booking Content -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">Booking Information</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Primary details of the trek reservation</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-lg">
                        <i class="fas fa-mountain"></i>
                    </div>
                </div>

                <div class="px-6 py-8 grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-6">
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Customer</span>
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
                        <span class="text-xs font-medium text-slate-500">Trek Departure</span>
                        <p class="text-sm font-semibold text-slate-900 leading-tight">{{ $booking->departure?->trek?->title }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Travel Dates</span>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ optional($booking->departure?->start_date)->format('M d') }} - {{ optional($booking->departure?->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="space-y-1.5">
                        <span class="text-xs font-medium text-slate-500">Passengers</span>
                        <p class="text-sm font-semibold text-slate-900">{{ $booking->total_passengers }} Travelers</p>
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

            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">Passenger List</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Verified travelers for this departure</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-lg">
                        <i class="fas fa-passport"></i>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-medium text-slate-500">Full Name</th>
                                <th class="px-6 py-4 text-xs font-medium text-slate-500">Age</th>
                                <th class="px-6 py-4 text-xs font-medium text-slate-500">Passport No.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($booking->passengers as $passenger)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $passenger->full_name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $passenger->age ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-mono font-semibold text-slate-400">{{ $passenger->passport_number ?: '---' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center">
                                        <p class="text-xs font-medium text-slate-400 italic">No passenger details recorded.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </article>

        <!-- Sidebar Stats -->
         <aside class="space-y-8">
            <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Financial Summary</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Payment breakdown</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Base Price</span>
                        <span class="font-semibold text-slate-900">NPR {{ number_format($booking->price_per_person, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Travelers</span>
                        <span class="font-semibold text-slate-900">x {{ $booking->total_passengers }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Discount ({{ $booking->discount_percent }}%)</span>
                            <span class="font-semibold text-red-500">-NPR {{ number_format($booking->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-medium text-slate-500 mb-1">Total Paid</p>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight">NPR {{ number_format($booking->total_price, 2) }}</p>
                    </div>
                </div>
            </section>

            <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Status Update</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Manage reservation state</p>
                </div>

                <form method="POST" action="{{ route('staff.trek-bookings.status', $booking) }}" class="space-y-4">
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

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-all shadow-sm" onclick="return confirm('Are you sure you want to update the booking status?')">
                        <i class="fas fa-check-circle text-xs opacity-70"></i>
                        <span>Apply Changes</span>
                    </button>
                </form>
            </section>

        </aside>
    </div>
</x-layouts.dashboard>

