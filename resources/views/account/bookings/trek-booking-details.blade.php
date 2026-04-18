@php
    $paymentStatus = $payment?->status ?? 'unpaid';
    $isLocked = in_array($booking->status, ['completed', 'cancelled']);
    $canEditPassengers = ! $isLocked;
@endphp

<x-layouts.customer>
    <div class="space-y-4 max-w-5xl">
        <!-- Back Link -->
        <div>
            <a href="{{ route('account.bookings.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to list
            </a>
        </div>

        <!-- Header Row -->
        <div class="flex flex-col md:flex-row md:items-baseline md:justify-between gap-4 mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Booking Details</h1>
            <p class="text-sm text-slate-500 font-mono select-all">Ref: {{ $booking->booking_reference }}</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-info-circle w-5 h-5 text-slate-400 text-center"></i>
                <span class="text-sm font-semibold text-slate-900">Booking Status</span>
            </div>
            <x-customer.status-badge :status="$booking->status" />
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
</x-layouts.customer>

