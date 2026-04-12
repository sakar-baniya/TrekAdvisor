<x-guest-layout>
    <div class="auth-header auth-header--centered">
        <p class="auth-kicker">Secure Area</p>
        <h1 class="auth-title">Confirm your password</h1>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Confirm Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
