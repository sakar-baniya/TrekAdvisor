@php
    $formatPayment = function ($payments) {
        $latest = $payments?->sortByDesc(fn ($payment) => $payment->paid_at ?? $payment->created_at)->first();
        if (! $latest) {
            return ['label' => 'Unpaid', 'status' => 'pending'];
        }
        return match ($latest->status) {
            'success' => ['label' => 'Paid', 'status' => 'confirmed'],
            'pending' => ['label' => 'Pending', 'status' => 'pending'],
            'failed' => ['label' => 'Failed', 'status' => 'cancelled'],
            default => ['label' => ucfirst($latest->status), 'status' => 'pending'],
        };
    };
@endphp

<x-customer-layout 
    title="My Bookings" 
    subtitle="Manage upcoming and past trek or hotel bookings in one place."
    :breadcrumb="['My Bookings' => null]"
>
    <div x-data="{ tab: 'upcoming' }">
        <!-- Tab Navigation (Alpine) -->
        <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-xl w-fit mb-10">
            <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2 text-sm font-semibold rounded-lg transition-all">
                Upcoming
            </button>
            <button @click="tab = 'past'" :class="tab === 'past' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2 text-sm font-semibold rounded-lg transition-all">
                Past History
            </button>
        </div>

        <!-- Upcoming Panel -->
        <div x-show="tab === 'upcoming'" class="space-y-12 animate-fadeIn">
            <!-- Treks -->
            <section>
                <div class="flex items-center gap-3 mb-6">
                    <h2 class="text-base font-semibold text-slate-900">Upcoming Treks</h2>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse ($trekUpcoming as $booking)
                        @php $payment = $formatPayment($booking->payments); @endphp
                        <x-customer.booking-card 
                            :title="$booking->departure?->trek?->title ?? 'Trek Booking'" 
                            :status="$booking->status" 
                            icon="fa-mountain" 
                            :viewRoute="route('account.bookings.treks.show', $booking)"
                        >
                            <x-slot name="meta">
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-calendar text-slate-400"></i> {{ optional($booking->departure?->start_date)->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fas fa-coins text-slate-400"></i> NPR {{ number_format($booking->total_price) }}
                                </span>
                                <x-customer.status-badge :status="$payment['label']" />
                            </x-slot>
                        </x-customer.booking-card>
                    @empty
                        <x-customer.empty-state title="No upcoming treks" message="You don't have any treks confirmed yet." icon="fa-mountain" />
                    @endforelse
                </div>
            </section>

            <!-- Hotels -->
            <section>
                <div class="flex items-center gap-3 mb-6">
                    <h2 class="text-base font-semibold text-slate-900">Hotel Stays</h2>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse ($hotelUpcoming as $booking)
                        @php $payment = $formatPayment($booking->payments); @endphp
                        <x-customer.booking-card 
                            :title="$booking->hotelRoom?->hotel?->name ?? 'Hotel Booking'" 
                            :status="$booking->status" 
                            icon="fa-hotel" 
                            :viewRoute="route('account.bookings.hotels.show', $booking)"
                        >
                            <x-slot name="meta">
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fas fa-calendar-check text-slate-400"></i> {{ optional($booking->check_in)->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fas fa-credit-card text-slate-400"></i> {{ $payment['label'] }}
                                </span>
                            </x-slot>
                        </x-customer.booking-card>
                    @empty
                        <x-customer.empty-state title="No hotel stays" message="No upcoming hotel reservations found." icon="fa-hotel" />
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Past Panel -->
        <div x-show="tab === 'past'" style="display: none;" class="space-y-12 animate-fadeIn">
            <!-- Past Treks -->
            <section>
                <div class="flex items-center gap-3 mb-6">
                    <h2 class="text-base font-semibold text-slate-900">Past Bookings</h2>
                </div>

                <div class="grid grid-cols-1 gap-4 opacity-75">
                    @forelse ($trekPast as $booking)
                        <x-customer.booking-card 
                            :title="$booking->departure?->trek?->title ?? 'Trek Booking'" 
                            :status="$booking->status" 
                            icon="fa-mountain" 
                            :viewRoute="route('account.bookings.treks.show', $booking)"
                        >
                            <x-slot name="meta">
                                <span class="text-xs text-slate-500 italic">Completed on {{ optional($booking->departure?->end_date)->format('M d, Y') }}</span>
                            </x-slot>
                        </x-customer.booking-card>
                    @empty
                        <x-customer.empty-state title="No history" message="You haven't completed any adventures yet." icon="fa-history" />
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-customer-layout>
