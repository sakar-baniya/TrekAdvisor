<x-layouts.auth 
    title="Login" 
    heading="Welcome back" 
    subheading="Sign in to your account and prepare for your next trek.">

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                       autocomplete="username" 
                       class="auth-input pl-14"
                       placeholder="you@trekking.com" />
            </div>
            @error('email') 
                <p x-data="{ show: true }" 
                   x-show="show" 
                   x-init="setTimeout(() => show = false, 5000)"
                   x-transition:leave="transition ease-in duration-500"
                   x-transition:leave-start="opacity-100"
                   x-transition:leave-end="opacity-0"
                   class="text-[10px] font-semibold text-red-600 uppercase tracking-widest mt-1 italic">
                    {{ $message }}
                </p> 
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                <input id="password" 
                       :type="show ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       class="auth-input pl-14 pr-14"
                       placeholder="••••••••" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-900 transition-colors p-2">
                    <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password') 
                <p x-data="{ show: true }" 
                   x-show="show" 
                   x-init="setTimeout(() => show = false, 5000)"
                   x-transition:leave="transition ease-in duration-500"
                   x-transition:leave-start="opacity-100"
                   x-transition:leave-end="opacity-0"
                   class="text-[10px] font-semibold text-red-600 uppercase tracking-widest mt-1 italic">
                    {{ $message }}
                </p> 
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900 focus:ring-offset-0 transition-all cursor-pointer">
                <span class="ml-2 text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Keep me signed in</span>
            </label>
        </div>

        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-semibold uppercase tracking-[0.2em] text-xs hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98]">
            Sign In
        </button>
    </form>

    <x-slot name="footer">
        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
            New to TrekAdvisor? 
            <a href="{{ route('register') }}" class="text-slate-900 hover:text-slate-700 transition-colors ml-1">
                Create Account
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

