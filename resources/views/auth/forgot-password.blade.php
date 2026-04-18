<x-layouts.auth 
    title="Forgot Password" 
    heading="Forgot password?" 
    subheading="No worries! Enter your email and we'll send you a recovery link.">

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
            <div class="relative group">
                <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       class="auth-input pl-14"
                       placeholder="you@example.com" />
            </div>
            @error('email') <p class="text-[10px] font-semibold text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-semibold uppercase tracking-[0.2em] text-xs hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98] flex items-center justify-center gap-2">
            Send Reset Link
            <i class="fas fa-paper-plane text-[10px]"></i>
        </button>
    </form>

    <x-slot name="footer">
        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-slate-900 hover:text-slate-700 transition-colors ml-1">
                Sign In
            </a>
        </p>
    </x-slot>

    <x-slot name="back">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left text-[10px]"></i>
            Back to Basecamp
        </a>
    </x-slot>
</x-layouts.auth>

