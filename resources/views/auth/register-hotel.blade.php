<x-auth-page 
    title="Hotel Partner Registration" 
    heading="Become a Hotel Partner" 
    subheading="List your property on TrekAdvisor and reach thousands of trekkers."
    maxWidth="xl"
    errorMessage="Please review your business details and correct any errors.">

    <form method="POST" action="{{ route('register.hotel') }}" class="space-y-5">
        @csrf

        <!-- Owner Name + Phone Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Owner name</label>
                <div class="relative group">
                    <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="auth-input pl-14" placeholder="Rajesh Hamal" />
                </div>
                @error('name') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Business phone</label>
                <div class="relative group">
                    <i class="fas fa-phone absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="\d{10}" maxlength="10" class="auth-input pl-14" placeholder="9800012345" />
                </div>
                @error('phone') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Business Email + Property Name Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Business email</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="auth-input pl-14" placeholder="hotel@example.com" />
                </div>
                @error('email') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="hotel_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Property name</label>
                <div class="relative group">
                    <i class="fas fa-building absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="hotel_name" type="text" name="hotel_name" value="{{ old('hotel_name') }}" required class="auth-input pl-14" placeholder="Hotel Everest View" />
                </div>
                @error('hotel_name') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Password Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5" x-data="{ show: false }">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required minlength="8" class="auth-input pl-14 pr-14" placeholder="Min. 8 chars" />
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-700 p-2">
                        <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5" x-data="{ show: false }">
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors text-sm"></i>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required minlength="8" class="auth-input pl-14 pr-14" placeholder="Repeat password" />
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-700 p-2">
                        <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase tracking-[0.2em] text-xs hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98]">
            Apply for Partnership
        </button>
    </form>

    <x-slot name="footer">
        <div class="flex flex-col md:flex-row items-center justify-center gap-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
            <p><a href="{{ route('register') }}" class="text-slate-900 hover:text-slate-700 transition-colors">Trekker signup</a></p>
            <span class="hidden md:inline text-slate-200">|</span>
            <p><a href="{{ route('login') }}" class="text-slate-900 hover:text-slate-700 transition-colors">Sign in</a></p>
            <span class="hidden md:inline text-slate-200">|</span>
            <p><a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-900">Home</a></p>
        </div>
    </x-slot>
</x-auth-page>
