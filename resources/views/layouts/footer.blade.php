<!-- Footer -->
<footer class="bg-slate-900 text-slate-300 py-12 mt-auto border-t border-slate-800 print:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Branding -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 text-white font-bold text-xl tracking-tight">
                    <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" class="w-8 h-8 rounded-full bg-slate-800 p-1" alt="TrekAdvisor logo" />
                    TrekAdvisor
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Trek the Himalayas and plan stays from one beautiful marketplace.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4 tracking-wide uppercase text-sm">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-white hover:text-white">Home</a></li>
                    <li><a href="{{ route('treks.index') }}" class="text-white hover:text-white">Treks</a></li>
                    <li><a href="{{ route('hotels.index') }}" class="text-white hover:text-white">Hotels</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="text-white font-semibold mb-4 tracking-wide uppercase text-sm">Services</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="text-white hover:text-white">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white hover:text-white">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="text-white hover:text-white">FAQ</a></li>
                    <li><a href="{{ route('travel-guide') }}" class="text-white hover:text-white">Travel Guide</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold mb-4 tracking-wide uppercase text-sm">Contact</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li>Pokhara, Nepal</li>
                    <li>info@trekadvisor.com</li>
                    <li>+977 9800000000</li>
                </ul>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="border-t border-slate-800 pt-8 text-sm text-slate-500 text-center flex flex-col sm:flex-row justify-between items-center">
            <p>&copy; {{ date('Y') }} TrekAdvisor. All rights reserved.</p>
        </div>
    </div>
</footer>
