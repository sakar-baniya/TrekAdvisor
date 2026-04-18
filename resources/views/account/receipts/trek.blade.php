<x-customer-layout 
    title="Booking Receipt" 
    subtitle="Official transaction record for your trek."
    :breadcrumb="['My Bookings' => route('account.bookings.index'), 'Details' => route('account.bookings.treks.show', $booking), 'Receipt' => null]"
>
    <div class="max-w-4xl mx-auto space-y-6 animate-fadeIn">
        <!-- Receipt Card -->
        <div class="bg-white p-10 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden print:p-0 print:border-0 print:shadow-none">
            <!-- Decorative Branding -->
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                <i class="fas fa-mountain text-9xl"></i>
            </div>

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-slate-100 pb-8 mb-10">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900 tracking-tight mb-2">Receipt</h1>
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-semibold text-slate-400">Reference</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">{{ $booking->booking_reference }}</span>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-xs font-semibold text-slate-400 mb-1">Date Issued</p>
                    <p class="text-sm font-semibold text-slate-900">{{ now()->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Grid Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Traveler Info -->
                <div class="space-y-6">
                    <h2 class="text-xs font-semibold text-slate-900 border-b border-slate-50 pb-3">Traveler Details</h2>
                    @if ($booking->passengers->isEmpty())
                        <p class="text-xs font-medium text-slate-400 italic">No traveler details recorded.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($booking->passengers as $passenger)
                                <div class="flex justify-between items-start">
                                    <div class="space-y-0.5">
                                        <p class="text-sm font-semibold text-slate-900">{{ $passenger->full_name }}</p>
                                        <p class="text-xs text-slate-500">Passport: {{ $passenger->passport_number ?? 'N/A' }}</p>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400">Age: {{ $passenger->age ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Booking Summary -->
                <div class="space-y-6">
                    <h2 class="text-xs font-semibold text-slate-900 border-b border-slate-50 pb-3">Trek Information</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Activity</span>
                            <span class="text-xs font-semibold text-slate-900">{{ $booking->departure?->trek?->title }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Dates</span>
                            <span class="text-xs font-semibold text-slate-900">{{ optional($booking->departure?->start_date)->format('M d') }} — {{ optional($booking->departure?->end_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Booking Status</span>
                            <x-customer.status-badge :status="$booking->status" />
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
                <p class="text-xs text-slate-400 italic">This is a digitally generated receipt and does not require a signature.</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between px-6 py-4 bg-white/50 border border-slate-200 rounded-xl print:hidden">
            <a href="{{ route('account.bookings.treks.show', $booking) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 opacity-60"></i> Back to Booking
            </a>
            <x-ui.button onclick="window.print()">
                <i class="fas fa-print mr-2 opacity-70"></i> Print or Save PDF
            </x-ui.button>
        </div>
    </div>
</x-customer-layout>
