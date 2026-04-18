@section('page-title', 'Payment Detail')
@section('page-subtitle', 'Transaction: ' . $payment->transaction_id)
@section('page-back', route('staff.payments.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Payment Details -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Financial Record</h3>
                        <p class="text-xs font-medium text-slate-500 mt-1">Primary transaction and customer identity</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-8">
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500">Customer Account</span>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold text-xs">
                                    {{ strtoupper(substr($payment->user?->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $payment->user?->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-slate-500 leading-none">{{ $payment->user?->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500">Payment Intent</span>
                            <p class="text-sm font-semibold text-slate-900">{{ ucfirst($payment->payable_type) }} Booking</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500">Settlement Amount</span>
                            <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $payment->currency ?? 'NPR' }} {{ number_format($payment->amount, 2) }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500">Gateway / Method</span>
                            <p class="text-sm font-semibold text-slate-900 italic">{{ $payment->gateway ? strtoupper($payment->gateway) : 'DIRECT/INTERNAL' }}</p>
                        </div>
                        <div class="space-y-1 text-black">
                            <span class="text-xs font-medium text-slate-500">Transaction Status</span>
                            @php
                                $statusVal = strtolower((string)$payment->status);
                                $statusColor = match(true) {
                                    in_array($statusVal, ['success', 'completed', 'paid']) => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    in_array($statusVal, ['pending', 'processing']) => 'bg-amber-50 text-amber-700 border-amber-100',
                                    default => 'bg-red-50 text-red-700 border-red-100'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-lg text-xs font-medium border {{ $statusColor }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500">Created At</span>
                            <p class="text-sm font-semibold text-slate-900 opacity-60">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </article>

        <!-- Sidebar Info -->
        <aside class="space-y-8">
            <section class="bg-white p-6 md:p-8 rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 transition-all">
                <div class="mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                        <i class="fas fa-link text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Reference</h3>
                        <p class="text-xs font-medium text-slate-500 leading-none mt-1">Linked platform record</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-1">
                        <span class="text-xs font-medium text-slate-500">Internal ID</span>
                        <p class="text-sm font-mono font-semibold text-slate-900">{{ $payment->payable_id }}</p>
                    </div>
                    
                    @if ($reference)
                        <div class="h-px bg-slate-50"></div>
                        @if ($payment->payable_type === 'trek')
                            <div class="space-y-1">
                                <span class="text-xs font-medium text-slate-500">Trek Booking</span>
                                <p class="text-sm font-semibold text-slate-900 leading-tight mb-2">{{ $reference->departure?->trek?->title }}</p>
                                <a href="{{ route('staff.trek-bookings.show', $reference) }}" class="inline-flex text-xs font-medium text-blue-600 hover:text-blue-800">
                                    View Booking <i class="fas fa-arrow-right ml-1 opacity-50"></i>
                                </a>
                            </div>
                        @elseif ($payment->payable_type === 'hotel')
                            <div class="space-y-1">
                                <span class="text-xs font-medium text-slate-500">Hotel Booking</span>
                                <p class="text-sm font-semibold text-slate-900 leading-tight mb-2">{{ $reference->hotelRoom?->hotel?->name }}</p>
                                <a href="{{ route('staff.hotel-bookings.show', $reference) }}" class="block mb-1">
                                    <span class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Ref: {{ $reference->booking_reference }}
                                    </span>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-xs font-medium text-center">
                            Linked record not found
                        </div>
                    @endif
                </div>
            </section>
        </aside>
    </div>
</x-layouts.dashboard>

