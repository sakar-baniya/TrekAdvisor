@section('page-title', 'Message View')
@section('page-subtitle', 'Subject: ' . $message->subject)
@section('page-back', route('staff.contact-messages.index'))

<x-layouts.dashboard>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Message Card -->
        <article class="lg:col-span-2 space-y-8">
            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">Inquiry Content</h3>
                        <p class="text-xs font-medium text-slate-500 mt-1">Full message body and sender identity</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Contact Name</span>
                            <p class="text-sm font-semibold text-slate-900">{{ $message->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email Address</span>
                            <a href="mailto:{{ $message->email }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $message->email }} <i class="fas fa-external-link-alt text-[10px] ml-1 opacity-50"></i>
                            </a>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Subject</span>
                            <p class="text-sm font-semibold text-slate-900">{{ $message->subject }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Platform Status</span>
                            @php
                                $status = $message->responded_at ? 'responded' : ($message->is_read ? 'read' : 'unread');
                                $statusColor = match($status) {
                                    'responded' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'read' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    default => 'bg-amber-50 text-amber-700 border-amber-100'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border {{ $statusColor }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 relative">
                        <i class="fas fa-quote-left absolute -top-3 left-4 text-xl text-slate-200"></i>
                        <p class="text-sm text-slate-700 leading-relaxed font-medium whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
            </section>

            @if ($message->responded_at)
                <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
                                <i class="fas fa-reply-all text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900 tracking-tight">Outgoing Response</h3>
                                <p class="text-xs font-medium text-slate-500 leading-none mt-1">
                                    Responded by {{ $message->respondedByStaff?->name ?? 'Admin' }} on {{ $message->responded_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 md:p-8">
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap italic">"{{ $message->staff_response }}"</p>
                    </div>
                </section>
            @endif
        </article>

        <!-- Sidebar Actions -->
        <aside class="space-y-8">
            <section class="bg-white p-6 md:p-8 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Reply</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Send a direct email response</p>
                </div>

                <form method="POST" action="{{ route('staff.contact-messages.respond', $message) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-500 ml-1">Response Note</label>
                        <textarea name="staff_response" 
                                  class="w-full bg-slate-50 border-slate-200 rounded-xl p-4 text-sm font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all focus:bg-white" 
                                  rows="8" 
                                  placeholder="Type your reply here...">{{ old('staff_response', $message->staff_response) }}</textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-3 px-6 py-3 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-all shadow-sm active:scale-[0.98]">
                        <i class="fas fa-paper-plane text-xs"></i>
                        <span>Save & Send Reply</span>
                    </button>
                </form>
            </section>

            <div class="p-6 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 shadow-sm">
                <i class="fas fa-magic text-blue-400 mb-4 text-xl"></i>
                <p class="text-xs font-medium text-blue-600 mb-2">Internal Note</p>
                <p class="text-xs leading-relaxed">Replying here will mark the message as "Responded" and trigger an automated email to the customer's provided address.</p>
            </div>
        </aside>
    </div>
</x-layouts.dashboard>

