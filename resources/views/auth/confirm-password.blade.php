<x-guest-layout>
    <div class="auth-header auth-header--centered">
        <p class="auth-kicker">Secure Area</p>
        <h1 class="auth-title">Confirm your password</h1>
        <p class="auth-subtitle">{{ __('This action needs an extra confirmation before you continue.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="auth-input"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Confirm Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
