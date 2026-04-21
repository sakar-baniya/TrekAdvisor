@section('page-title', 'Message View')
@section('page-subtitle', 'Subject: ' . $message->subject)
@section('page-back', route('admin.contact-messages.index'))

<x-layouts.dashboard>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Main Message Card -->
        <article class="space-y-8">
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

                    </div>

                    <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 relative">
                        <i class="fas fa-quote-left absolute -top-4 left-6 text-2xl text-slate-200"></i>
                        <p class="text-sm text-slate-700 leading-relaxed font-medium whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-100 text-slate-500 italic">
                            <div class="flex items-start gap-4">
                                <i class="fas fa-info-circle mt-1 text-slate-400"></i>
                                <p class="text-xs leading-relaxed">
                                    To respond to this inquiry, please reply directly through your email client using the recipient address above. 
                                    Internal dashboard replies have been disabled for security and centralization.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
    </div>
</x-layouts.dashboard>

