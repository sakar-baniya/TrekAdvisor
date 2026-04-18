<x-auth-page 
    title="Admin Access" 
    heading="Admin Access" 
    subheading="Please authenticate to access the platform management dashboard."
    errorMessage="Invalid administrator credentials. Access denied.">

    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Admin Email</label>
            <div class="relative group">
                <i class="fas fa-user-shield absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       class="auth-input pl-14"
                       placeholder="admin@trekadvisor.com" />
            </div>
            @error('email') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Security Key</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors" href="{{ route('password.request') }}">
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
            @error('password') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase tracking-[0.2em] text-xs hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98]">
            Authenticate Access
        </button>
    </form>

    <x-slot name="footer">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
            Not an admin? 
            <a href="{{ route('login') }}" class="text-slate-900 hover:text-slate-700 transition-colors ml-1">
                Customer Sign In
            </a>
        </p>
    </x-slot>

    <x-slot name="back">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left text-[10px]"></i>
            Return to Site
        </a>
    </x-slot>
</x-auth-page>
