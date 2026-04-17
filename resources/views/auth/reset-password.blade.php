<x-guest-layout>
    <div class="mb-8 text-center">
        <p class="text-emerald-500 font-bold uppercase tracking-wider text-xs mb-2">Create New Password</p>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reset your password</h1>
        <div class="h-1 w-12 bg-slate-900 rounded mt-4 mx-auto" aria-hidden="true"></div>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="mb-2" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" maxlength="255" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="mb-2" />
            <div class="relative">
                <x-text-input id="password" class="pr-12" type="password" name="password" required autocomplete="new-password" minlength="8" />
                <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" data-toggle-password data-target="password" aria-label="Show password">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash hidden" data-icon="hide"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mb-2" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="pr-12"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" minlength="8" />
                <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" data-toggle-password data-target="password_confirmation" aria-label="Show password confirmation">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash hidden" data-icon="hide"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-full shadow-sm text-sm font-extrabold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all duration-200">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
