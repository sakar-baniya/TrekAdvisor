@section('page-title', 'Contact Inbox')
@section('page-subtitle', 'Recent contact submissions and platform inquiries.')

<x-layouts.dashboard>
    <div class="space-y-6">

    <!-- Filters Card -->
    <section class="bg-white p-5 md:p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
        <form method="GET" action="{{ route('staff.contact-messages.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <input type="search" name="search" value="{{ $search }}" 
                       class="w-full bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all font-display" 
                       placeholder="Search name, email, or subject..." />
            </div>
            
            <button type="submit" class="bg-slate-900 text-white rounded-lg px-4 py-2.5 text-sm font-semibold hover:bg-slate-800 transition-all shadow-sm">Apply Filters</button>
        </form>
    </section>

    <!-- Inbox Cards -->
    <div class="space-y-4 mb-10">
        @forelse ($messages as $message)
            <div class="bg-white p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition-all group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-base">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-base font-semibold text-slate-900 leading-tight font-display">{{ $message->subject }}</h3>
                                @if (!$message->is_read)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-tighter">New</span>
                                @endif
                                @if ($message->responded_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-tighter">Responded</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-1.5">
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-user text-slate-400"></i> {{ $message->name }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-envelope text-slate-400"></i> {{ $message->email }}
                                </span>
                                <span class="flex items-center gap-2 text-xs text-slate-400">
                                    <i class="far fa-clock text-slate-400 text-[10px]"></i> {{ $message->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                        <a href="{{ route('staff.contact-messages.show', $message) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                            Read Message
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border border-slate-200 text-center">
                <i class="fas fa-inbox text-4xl text-slate-100 mb-4 block"></i>
                <p class="text-sm font-medium text-slate-400">Your inbox is currently empty.</p>
            </div>
        @endforelse
    </div>

    @if ($messages->hasPages())
        <div class="admin-pagination">{{ $messages->links() }}</div>
    @endif
    </div>
</x-layouts.dashboard>

