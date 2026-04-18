<x-customer-layout 
    title="Payment History" 
    subtitle="Your payment history and transaction status."
    :breadcrumb="['Payments' => null]"
>
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm animate-fadeIn">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500">Transaction ID</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500">Gateway</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500">Paid At</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $payment)
                        @php
                            $bookingLink = $payment->payable_type === 'trek'
                                ? route('account.bookings.treks.show', $payment->payable_id)
                                : ($payment->payable_type === 'hotel' ? route('account.bookings.hotels.show', $payment->payable_id) : '#');
                        @endphp
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-slate-900 font-mono tracking-tight">{{ $payment->transaction_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-[10px] font-medium text-slate-600">{{ ucfirst($payment->gateway ?? 'N/A') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-900">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <x-customer.status-badge :status="$payment->status" />
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500">{{ $payment->paid_at?->format('M d, Y') ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ $bookingLink }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                                    View Booking
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12">
                                <x-customer.empty-state 
                                    title="No transactions" 
                                    message="Your payment history will appear here once you make a booking." 
                                    icon="fa-credit-card" 
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-customer-layout>
