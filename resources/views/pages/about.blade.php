<x-layouts.app>
    <main class="max-w-5xl mx-auto px-4 pt-12 pb-32 space-y-24">
        <!-- Hero -->
        <header class="text-center">
            <h1 class="text-slate-900 text-4xl md:text-5xl font-bold tracking-tight mb-6">About TrekAdvisor</h1>
            <p class="text-slate-600 text-lg md:text-xl font-medium max-w-3xl mx-auto leading-[1.6]">
                Built for calmer, smarter adventure planning in the heart of the Himalayas. We bridge the gap between mountain exploration and modern technology.
            </p>
        </header>

        <!-- Mission & Vision Grid -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-16">
            <div class="bg-slate-50/50 rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100/50">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Our Mission</h2>
                <p class="text-slate-600 leading-relaxed text-lg">
                    To make trekking discovery, planning, and booking feel clear, trusted, and beautifully organized for modern adventurers seeking authentic experiences.
                </p>
            </div>

            <div class="bg-slate-50/50 rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100/50">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Our Vision</h2>
                <p class="text-slate-600 leading-relaxed text-lg">
                    To create a global platform where people can confidently explore routes and stays in one thoughtful, integrated Himalayan experience.
                </p>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section>
            <h2 class="text-slate-900 text-3xl font-bold tracking-tight mb-12">Why Choose Us</h2>
            <div class="space-y-8 md:space-y-10">
                <!-- Feature 1 -->
                <div class="flex items-start gap-6 p-4 -ml-4 rounded-2xl hover:bg-slate-50 transition-colors group">
                    <div class="flex-shrink-0 w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-900 shadow-sm transition-transform group-hover:scale-105">
                        <i class="fas fa-mountain text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Expert Routes</h3>
                        <p class="text-slate-600 leading-relaxed text-lg">Curated treks with useful context, difficulty metrics, and real-time departure visibility for safety.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex items-start gap-6 p-4 -ml-4 rounded-2xl hover:bg-slate-50 transition-colors group">
                    <div class="flex-shrink-0 w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-900 shadow-sm transition-transform group-hover:scale-105">
                        <i class="fas fa-hotel text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Thoughtful Stays</h3>
                        <p class="text-slate-600 leading-relaxed text-lg">Hotel discovery is presented with comfort, clarity, and clean pricing cues for every Himalayan traveler.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>

