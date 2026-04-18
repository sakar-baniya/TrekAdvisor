@section('page-title', 'Payments Queue')
@section('page-subtitle', 'Pending and failed payments that need follow-up.')

<x-layouts.dashboard>
    <div class="space-y-6">

    <!-- Filters Card -->
    <section class="bg-white p-5 md:p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
        <form method="GET" action="{{ route('staff.payments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="search" name="search" value="{{ $search }}" 
                       class="w-full bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all font-display" 
                       placeholder="Search transaction or customer..." />
            </div>
            
            <select name="status" class="bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all appearance-none cursor-pointer font-display">
                <option value="">Pending + Failed</option>
                <option value="pending" @selected($status === 'pending')>Pending</option>
                <option value="failed" @selected($status === 'failed')>Failed</option>
            </select>

            <button type="submit" class="bg-slate-900 text-white rounded-lg px-4 py-2.5 text-sm font-semibold hover:bg-slate-800 transition-all shadow-sm">Apply Filters</button>
        </form>
    </section>

    <!-- Transaction Cards -->
    <div class="space-y-4 mb-10">
        @forelse ($payments as $payment)
            <div class="bg-white p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-all group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100">Txn: {{ substr((string)$payment->transaction_id, 0, 8) }}</span>
                                <h3 class="text-base font-semibold text-slate-900 leading-tight font-display">NPR {{ number_format($payment->amount, 2) }}</h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-1.5">
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-user text-slate-400"></i> {{ $payment->user?->name ?? 'Guest' }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fas fa-tag text-slate-400 text-[10px]"></i> {{ ucfirst($payment->payable_type) }}
                                </span>
                                <span class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-tight">
                                    {{ $payment->gateway ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                        @php
                            $statusVal = strtolower((string)$payment->status);
                            $badgeStyle = match(true) {
                                in_array($statusVal, ['success', 'completed', 'paid']) => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                in_array($statusVal, ['pending', 'processing']) => 'bg-amber-50 text-amber-700 border-amber-100',
                                in_array($statusVal, ['failed', 'cancelled']) => 'bg-red-50 text-red-700 border-red-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-100'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border {{ $badgeStyle }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                        <div class="h-4 w-px bg-slate-100 hidden md:block"></div>
                        <a href="{{ route('staff.payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                            Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border border-slate-200 text-center">
                <i class="fas fa-receipt text-4xl text-slate-100 mb-4 block"></i>
                <p class="text-sm font-medium text-slate-400">Noqueued payments found matching your filters.</p>
            </div>
        @endforelse
    </div>

    @if ($payments->hasPages())
        <div class="admin-pagination">{{ $payments->links() }}</div>
    @endif
    </div>
</x-layouts.dashboard>

