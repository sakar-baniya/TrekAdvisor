@php
    $paymentStatus = $payment?->status ?? 'manual_confirmation';
    $isLocked = in_array($booking->status, ['completed', 'cancelled']);
@endphp

<x-layouts.customer 
    title="Booking Details" 
    subtitle="Reference: {{ $booking->booking_reference }}"
    :breadcrumb="['My Bookings' => route('account.bookings.index'), 'Details' => null]"
>
    <div class="space-y-6 max-w-5xl">
        <!-- Status Banner -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-300 flex items-center justify-center text-lg">
                    <i class="fas fa-hotel"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 leading-none mb-1.5">Reservation Status</span>
                    <x-customer.status-badge :status="$booking->status" />
                </div>
            </div>
            
            <a href="{{ route('account.bookings.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 opacity-60"></i> Back to list
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-8">
                @include('account.bookings.partials.hotel-booking-summary', [
                    'booking' => $booking,
                    'payment' => $payment,
                    'paymentStatus' => $paymentStatus,
                ])

                @include('account.bookings.partials.hotel-booking-review', [
                    'booking' => $booking,
                    'review' => $review,
                    'isLocked' => $isLocked,
                ])
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                @include('account.bookings.partials.hotel-booking-actions', [
                    'booking' => $booking,
                    'payment' => $payment,
                ])
            </div>
        </div>
    </div>
</x-layouts.customer>

