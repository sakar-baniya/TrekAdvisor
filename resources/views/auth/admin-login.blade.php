<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <div class="auth-header auth-header--centered">
        <p class="auth-kicker">Admin Portal</p>
        <h1 class="auth-title">Administrator Sign In</h1>
        <p class="auth-subtitle">Access the TrekAdvisor admin dashboard to manage the platform.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.store') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" data-toggle-password data-target="password" class="auth-password-toggle">
                    <svg data-icon="show" class="icon-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M2.5 12s3.75-6.5 9.5-6.5S21.5 12 21.5 12s-3.75 6.5-9.5 6.5S2.5 12 2.5 12Z"/>
                        <circle cx="12" cy="12" r="3.5"/>
                    </svg>
                    <svg data-icon="hide" class="is-hidden icon-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 5l18 14"/>
                        <path d="M10.5 6.5A9.7 9.7 0 0 1 12 6c5.75 0 9.5 6 9.5 6a16.7 16.7 0 0 1-4.2 4.6"/>
                        <path d="M7.2 8.6A16.7 16.7 0 0 0 2.5 12s3.75 6.5 9.5 6.5c1.3 0 2.5-.25 3.6-.7"/>
                        <path d="M9.5 9.5a3.5 3.5 0 0 0 5 5"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="auth-row">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Sign In as Admin') }}
            </button>
        </div>

        <div class="auth-footer auth-footer--stacked">
            <div class="auth-footer-copy">
                <a class="auth-link" href="{{ route('login') }}">
                    Back to customer login
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
