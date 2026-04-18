<x-customer-layout 
    title="Stay Receipt" 
    subtitle="Official record for your hotel reservation."
    :breadcrumb="['My Bookings' => route('account.bookings.index'), 'Details' => route('account.bookings.hotels.show', $booking), 'Receipt' => null]"
>
    <div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
        <!-- Receipt Card -->
        <div class="bg-white p-10 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden print:p-0 print:border-0 print:shadow-none">
            <!-- Decorative Branding -->
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                <i class="fas fa-hotel text-9xl"></i>
            </div>

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-slate-100 pb-8 mb-10">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900 tracking-tight mb-2">Reservation Receipt</h1>
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-semibold text-slate-400">Reference</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">{{ $booking->booking_reference ?? 'HOTEL-STAY' }}</span>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-xs font-semibold text-slate-400 mb-1">Date Issued</p>
                    <p class="text-sm font-semibold text-slate-900">{{ now()->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Grid Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Hotel Info -->
                <div class="space-y-6">
                    <h2 class="text-xs font-semibold text-slate-900 border-b border-slate-50 pb-3">Hotel Information</h2>
                    <div class="space-y-4">
                        <div class="space-y-0.5">
                            <p class="text-xs text-slate-500">Property</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->hotelRoom?->hotel?->name }}</p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-xs text-slate-500">Room Type</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->hotelRoom?->room_type ?? 'Standard Room' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stay Summary -->
                <div class="space-y-6">
                    <h2 class="text-xs font-semibold text-slate-900 border-b border-slate-50 pb-3">Stay Details</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Check-In</span>
                            <span class="text-xs font-semibold text-slate-900">{{ optional($booking->check_in)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Check-Out</span>
                            <span class="text-xs font-semibold text-slate-900">{{ optional($booking->check_out)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Reservation</span>
                            <span class="text-xs font-semibold text-slate-900">{{ $booking->num_rooms }} Room{{ $booking->num_rooms > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="mt-12 bg-slate-950 p-8 rounded-xl text-white">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <p class="text-xs font-medium text-slate-400 mb-1">Payment Method</p>
                        <p class="text-sm font-semibold">{{ $payment?->gateway ? ucfirst($payment->gateway) : 'Manual Settlement' }}</p>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-xs font-medium text-slate-400 mb-1">Total Amount Paid</p>
                        <p class="text-4xl font-semibold">{{ $payment?->currency ?? 'NPR' }} {{ number_format($booking->total_price) }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 text-center border-t border-slate-50 pt-8">
                <p class="text-xs text-slate-400 italic">Please present this receipt at check-in if requested.</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between px-6 py-4 bg-white/50 border border-slate-200 rounded-xl print:hidden">
            <a href="{{ route('account.bookings.hotels.show', $booking) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 opacity-60"></i> Back to Booking
            </a>
            <x-ui.button onclick="window.print()">
                <i class="fas fa-print mr-2 opacity-70"></i> Print or Save PDF
            </x-ui.button>
        </div>
    </div>
</x-customer-layout>
