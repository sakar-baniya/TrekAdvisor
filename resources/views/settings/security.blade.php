<x-layouts.customer 
    title="Security Settings" 
    subtitle="Protect your account with modern security standards."
    :breadcrumb="['Settings' => route('settings.profile.show'), 'Security' => null]"
    :hideFooter="true"
>
    @if (session('status'))
        <div class="mb-6 animate-fadeIn">
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-200 text-xs font-bold uppercase tracking-wider">
                <i class="fas fa-check-circle mr-2"></i> {{ str_replace('-', ' ', session('status')) }} success!
            </div>
        </div>
    @endif

    <div class="max-w-3xl space-y-6">
        <!-- Password Section -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-lg">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Password</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Change your password and keep your account secure.</p>
                </div>
            </div>
            
            <a href="{{ route('settings.security.password.show') }}" class="inline-flex items-center px-6 py-2.5 bg-slate-900 text-white text-[11px] font-semibold rounded-xl hover:bg-slate-800 transition-colors shadow-sm uppercase tracking-widest">
                Update Password
            </a>
        </div>

        <!-- 2FA Placeholder (Good for premium feel) -->
        <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 border-dashed opacity-60">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 rounded-xl bg-white text-slate-300 flex items-center justify-center text-lg">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-400">Two-Factor Authentication</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1 italic">Coming soon: Add an extra layer of security to your account.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.customer>

