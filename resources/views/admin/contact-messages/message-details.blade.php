@section('page-title', 'Message View')
@section('page-subtitle', 'Subject: ' . $message->subject)
@section('page-back', route('admin.contact-messages.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Message Card -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-8 md:p-10 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Inquiry Content</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Full message body and sender identity</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>

                <div class="p-8 md:p-10 space-y-10">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Contact Name</span>
                            <p class="text-sm font-semibold text-slate-900">{{ $message->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Email Address</span>
                            <a href="mailto:{{ $message->email }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $message->email }} <i class="fas fa-external-link-alt text-[10px] ml-1 opacity-50"></i>
                            </a>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Subject</span>
                            <p class="text-sm font-semibold text-slate-900">{{ $message->subject }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Platform Status</span>
                            @php
                                $status = $message->responded_at ? 'responded' : ($message->is_read ? 'read' : 'unread');
                                $statusColor = match($status) {
                                    'responded' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'read' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    default => 'bg-amber-50 text-amber-700 border-amber-100'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border {{ $statusColor }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 relative">
                        <i class="fas fa-quote-left absolute -top-4 left-6 text-2xl text-slate-200"></i>
                        <p class="text-sm text-slate-700 leading-relaxed font-medium whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
            </section>

            @if ($message->responded_at)
                <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 md:p-10 border-b border-slate-50 bg-slate-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
                                <i class="fas fa-reply-all text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Outgoing Response</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1">
                                    Responded by {{ $message->respondedByStaff?->name ?? 'Admin' }} on {{ $message->responded_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 md:p-10">
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap italic">"{{ $message->staff_response }}"</p>
                    </div>
                </section>
            @endif
        </article>

        <!-- Sidebar Actions -->
        <aside class="space-y-8">
            <section class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="mb-8 border-b border-slate-50 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Reply</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Send a direct email response</p>
                </div>

                <form method="POST" action="{{ route('admin.contact-messages.respond', $message) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Response Note</label>
                        <textarea name="staff_response" 
                                  class="w-full bg-slate-50 border-slate-100 rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all" 
                                  rows="8" 
                                  placeholder="Type your reply here...">{{ old('staff_response', $message->staff_response) }}</textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 bg-slate-900 text-white text-[10px] font-extrabold uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98]">
                        <i class="fas fa-paper-plane"></i>
                        <span>Save & Send Reply</span>
                    </button>
                </form>
            </section>

            <div class="p-8 rounded-[2rem] bg-blue-50 border border-blue-100 text-blue-700 shadow-lg shadow-blue-900/5">
                <i class="fas fa-magic text-blue-400 mb-4 text-xl"></i>
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-500 mb-2">Internal Note</p>
                <p class="text-xs font-semibold leading-relaxed">Replying here will mark the message as "Responded" and trigger an automated email to the customer's provided address.</p>
            </div>
        </aside>
    </div>
</x-layouts.dashboard>

