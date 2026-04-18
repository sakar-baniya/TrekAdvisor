<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-slate-900 overflow-hidden relative py-24">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-slate-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-slate-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <p class="text-slate-400 font-bold tracking-widest uppercase text-sm mb-4">Support Center</p>
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">We would love to hear from you</h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">Reach out for booking support, trek planning questions, or partnership conversations.</p>
        </div>
    </section>

    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Form Column -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10">
                        <div class="mb-10">
                            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Send a Message</h2>
                            <p class="text-slate-500">Share a few details and our support team will reply within one business day.</p>
                        </div>

                        @if (session('success'))
                            <div class="mb-8 p-4 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl flex items-center gap-3 font-semibold">
                                <i class="fas fa-check-circle text-slate-900 text-xl"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3 font-semibold">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form class="space-y-6" method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-ui.input id="name" name="name" label="Full Name" :value="old('name')" placeholder="Your full name" required />
                                </div>
                                <div>
                                    <x-ui.input id="email" type="email" name="email" label="Email Address" :value="old('email')" placeholder="you@example.com" required />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="subject" :value="__('Subject')" class="mb-2" />
                                <select id="subject" name="subject" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 px-4 py-3 bg-slate-50 focus:bg-white transition-colors">
                                    <option value="">Select a topic</option>
                                    <option value="General Inquiry" @selected(old('subject') === 'General Inquiry')>General Inquiry</option>
                                    <option value="Booking Support" @selected(old('subject') === 'Booking Support')>Booking Support</option>
                                    <option value="Trek Question" @selected(old('subject') === 'Trek Question')>Trek Question</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="message" :value="__('Message')" class="mb-2" />
                                <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 px-4 py-3 bg-slate-50 focus:bg-white transition-colors min-h-[150px]">{{ old('message') }}</textarea>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-slate-900 border border-transparent rounded-full font-extrabold text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all shadow-lg hover:scale-[1.02]">
                                    Send Message <i class="fas fa-paper-plane ml-2 text-xs"></i>
                                </button>
                            </div>
                        </form>
                    </article>
                </div>

                <!-- Info Column -->
                <aside class="space-y-8">
                    <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden group">
                        <!-- BG Icon decoration -->
                        <i class="fas fa-mountain absolute -right-4 -bottom-4 text-8xl text-white/5 group-hover:rotate-12 transition-transform duration-500"></i>
                        
                        <h2 class="text-2xl font-bold mb-8 relative z-10">Contact Details</h2>
                        
                        <div class="space-y-8 relative z-10">
                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl">
                                    <i class="fas fa-map-marker-alt text-slate-400"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Kathmandu, Nepal</strong>
                                    <span class="text-slate-400 text-sm">Tourism support office</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl">
                                    <i class="fas fa-envelope text-slate-400"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg line-clamp-1">hello@trekadvisor.com</strong>
                                    <span class="text-slate-400 text-sm">Email support</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl">
                                    <i class="fas fa-phone-alt text-slate-400"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg line-clamp-1">+977 9800000000</strong>
                                    <span class="text-slate-400 text-sm">Mon-Fri, 9AM-6PM</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-slate-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-semibold">Response time: Within 24 Hours</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Helpful Link card -->
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-slate-900 mb-4">Looking for help?</h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('faq') }}" class="text-slate-600 hover:text-slate-900 flex items-center justify-between group">
                                    <span>Browse FAQ</span>
                                    <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('treks.index') }}" class="text-slate-600 hover:text-slate-900 flex items-center justify-between group">
                                    <span>Explore Treks</span>
                                    <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-app-layout>
