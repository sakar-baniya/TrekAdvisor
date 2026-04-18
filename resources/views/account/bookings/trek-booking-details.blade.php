@php
    $paymentStatus = $payment?->status ?? 'unpaid';
    $isLocked = in_array($booking->status, ['completed', 'cancelled']);
    $canEditPassengers = ! $isLocked;
@endphp

<x-customer-layout 
    title="Booking Details" 
    subtitle="Reference: {{ $booking->booking_reference }}"
    :breadcrumb="['My Bookings' => route('account.bookings.index'), 'Details' => null]"
>
    <div class="space-y-6 max-w-5xl">
        <!-- Status Banner -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-300 flex items-center justify-center text-lg">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 leading-none mb-1.5">Booking Status</span>
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
                @include('account.bookings.partials.trek-booking-summary', [
                    'booking' => $booking,
                    'payment' => $payment,
                    'paymentStatus' => $paymentStatus,
                ])

                @include('account.bookings.partials.trek-booking-passengers', [
                    'booking' => $booking,
                    'canEditPassengers' => $canEditPassengers,
                    'isLocked' => $isLocked,
                ])

                @include('account.bookings.partials.trek-booking-review', [
                    'booking' => $booking,
                    'review' => $review,
                ])
            </div>

            <!-- Sidebar Actions/Receipt -->
            <div class="space-y-6">
                @include('account.bookings.partials.trek-booking-actions', [
                    'booking' => $booking,
                    'payment' => $payment,
                ])
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-passenger-section]').forEach(function (section) {
                    var panel = section.closest('.bg-white');
                    var viewPane = section.querySelector('[data-passenger-view]');
                    var editPane = section.querySelector('[data-passenger-edit]');
                    var openButton = panel ? panel.querySelector('[data-passenger-edit-open]') : null;
                    var cancelButton = section.querySelector('[data-passenger-edit-cancel]');

                    if (!viewPane || !editPane || !openButton) return;

                    var setEditMode = function (isEditing) {
                        viewPane.classList.toggle('hidden', isEditing);
                        editPane.classList.toggle('hidden', !isEditing);
                    };

                    openButton.addEventListener('click', function () { setEditMode(true); });
                    if (cancelButton) {
                        cancelButton.addEventListener('click', function () { setEditMode(false); });
                    }
                });
            });
        </script>
    @endpush
</x-customer-layout>
