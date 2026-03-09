<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-900 uppercase tracking-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Treks -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4 text-slate-900">
                <i class="fas fa-mountain text-xl"></i>
                <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Active</span>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Treks</p>
            <h3 class="text-3xl font-black text-slate-900">{{ \App\Models\Trek::count() }}</h3>
        </div>

        <!-- Hotel Approvals -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4 text-blue-600">
                <i class="fas fa-hotel text-xl"></i>
                <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Pending</span>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Hotel Approvals</p>
            <h3 class="text-3xl font-black text-slate-900">{{ \App\Models\Hotel::where('status', 'Pending')->count() }}</h3>
        </div>

        <!-- Today's Activity -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4 text-amber-600">
                <i class="fas fa-calendar-check text-xl"></i>
                <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Live</span>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Today's Bookings</p>
            <h3 class="text-3xl font-black text-slate-900">{{ \App\Models\Payment::whereDate('created_at', today())->count() }}</h3>
        </div>

        <!-- Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4 text-emerald-600">
                <i class="fas fa-wallet text-xl"></i>
                <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Growth</span>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</p>
            <h3 class="text-3xl font-black text-slate-900">${{ number_format(\App\Models\Payment::sum('amount') / 100, 2) }}</h3>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Recent Activity</h4>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Latest 5 Bookings</span>
        </div>
        <div class="overflow-x-auto text-dark">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order Ref</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer Name</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Paid Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(\App\Models\Payment::latest()->take(5)->get() as $payment)
                        <tr class="hover:bg-gray-50/20 transition-colors">
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-900 uppercase">#{{ substr($payment->transaction_id, -8) }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-600">{{ $payment->user->name }}</span>
                            </td>
                            <td class="px-8 py-5 text-sm font-black text-slate-900">
                                ${{ number_format($payment->amount / 100, 2) }}
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $payment->status === 'succeeded' ? 'bg-emerald-500 text-white' : 'bg-gray-400 text-white' }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right text-[10px] font-bold text-gray-400 uppercase">
                                {{ $payment->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">
                                No activity found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
