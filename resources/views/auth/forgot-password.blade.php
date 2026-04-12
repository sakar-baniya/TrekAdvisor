<x-guest-layout>
    <div class="auth-header auth-header--split">
        <p class="auth-kicker">Reset Password</p>
        <h1 class="auth-title">Forgot your password?</h1>
        <p class="auth-subtitle">{{ __('Enter your email address and we will send you a reset link so you can choose a new password.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus maxlength="255" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <div class="auth-footer auth-footer--stacked">
            <div class="auth-footer-copy">
                <a class="auth-link" href="{{ route('login') }}">Back to login</a>
            </div>
        </div>
    </form>
</x-guest-layout>
