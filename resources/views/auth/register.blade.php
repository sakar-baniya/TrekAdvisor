<x-guest-layout>
    <div class="mb-8 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">New Account</p>
        <h1 class="mt-3 text-3xl font-black text-slate-900">Create your account</h1>
        <p class="mt-2 text-sm text-slate-600">Join TrekAdvisor to book treks, hotels, and gear rentals.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (optional) -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone (optional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Account Type -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <div class="mt-2 grid grid-cols-2 gap-3">
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition hover:border-emerald-300">
                    <input type="radio" name="role" value="customer" class="mt-1 text-emerald-600 focus:ring-emerald-500" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <span>
                        <span class="block font-semibold text-slate-900">Customer</span>
                        <span class="block text-xs text-slate-500">Book treks and stays.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition hover:border-emerald-300">
                    <input type="radio" name="role" value="hotel_owner" class="mt-1 text-emerald-600 focus:ring-emerald-500" {{ old('role') === 'hotel_owner' ? 'checked' : '' }}>
                    <span>
                        <span class="block font-semibold text-slate-900">Hotel Owner</span>
                        <span class="block text-xs text-slate-500">List rooms and manage bookings.</span>
                    </span>
                </label>
            </div>
            <p class="mt-2 text-xs text-slate-500">Hotel owner accounts require admin approval.</p>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-base">
                {{ __('Create account') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center text-sm text-slate-600">
            Already have an account?
            <a class="font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('login') }}">
                Sign in
            </a>
        </div>
    </form>
</x-guest-layout>
