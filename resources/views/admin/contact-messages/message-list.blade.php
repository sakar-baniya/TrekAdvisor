@section('page-title', 'Contact Inbox')
@section('page-subtitle', 'Recent contact submissions and platform inquiries.')

<x-layouts.dashboard>
    <div class="space-y-6">

    <!-- Filters -->
    <section class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm mb-10">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <input type="search" name="search" value="{{ $search }}" 
                       class="w-full bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all font-sans" 
                       placeholder="Search name, email, or subject..." />
            </div>
            <button type="submit" class="bg-slate-900 text-white rounded-xl px-4 py-3 text-sm font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">Apply Filters</button>
        </form>
    </section>

    <!-- Inbox Cards -->
    <div class="space-y-4 mb-10">
        @forelse ($messages as $message)
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:border-slate-200 transition-all group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-base font-bold text-slate-900 leading-tight">{{ $message->subject }}</h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                                    <i class="far fa-user text-slate-300"></i> {{ $message->name }}
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                                    <i class="far fa-envelope text-slate-300"></i> {{ $message->email }}
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <i class="far fa-clock text-slate-300"></i> {{ $message->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                        <a href="{{ route('admin.contact-messages.show', $message) }}" class="inline-flex items-center px-5 py-2.5 border border-slate-200 text-slate-700 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                            Read Message
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-[2.5rem] border border-slate-100 shadow-sm text-center">
                <i class="fas fa-inbox text-4xl text-slate-100 mb-4 block"></i>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Your inbox is currently empty.</p>
            </div>
        @endforelse
    </div>

    @if ($messages->hasPages())
        <div class="admin-pagination">{{ $messages->links() }}</div>
    @endif
    </div>
</x-layouts.dashboard>

