<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-slate-900 overflow-hidden relative py-24">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-slate-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <p class="text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4">Support Center</p>
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">Frequently asked questions</h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">Clear answers to common planning, preparation, and booking questions.</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                @foreach ($faqs as $faq)
                    <article class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-md hover:border-slate-300 group">
                        <div class="flex items-start gap-5">
                            <div class="mt-1 bg-slate-900 text-white w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 transition-colors">
                                <i class="fas fa-question text-xs"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">{{ $faq['question'] }}</h3>
                                <p class="text-slate-600 leading-relaxed">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Still have questions? -->
            <div class="mt-20 text-center">
                <div class="inline-block p-1 rounded-2xl bg-white shadow-xl border border-slate-100">
                    <div class="px-8 py-6 rounded-xl bg-slate-900 text-white">
                        <h4 class="text-xl font-bold mb-2">Still have questions?</h4>
                        <p class="text-slate-400 mb-6">We're here to help you plan your perfect trek.</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-slate-900 font-bold rounded-lg hover:bg-slate-100 transition-colors">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
