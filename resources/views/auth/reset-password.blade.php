<x-guest-layout>
    <div class="auth-header auth-header--centered">
        <p class="auth-kicker">Create New Password</p>
        <h1 class="auth-title">Reset your password</h1>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" maxlength="255" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password" type="password" name="password" required autocomplete="new-password" minlength="8" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password_confirmation" class="auth-input auth-input-password"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" minlength="8" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password_confirmation" aria-label="Show password confirmation">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
