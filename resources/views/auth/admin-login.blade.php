<x-guest-layout>
    <a href="{{ route('home') }}" class="auth-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to camp</span>
    </a>

    <div class="auth-brand-area">

        <div class="auth-brand-mark">⛰</div>
        <span>TrekAdvisor</span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <div class="auth-header">
        <p class="auth-kicker">Admin Portal</p>
        <h1 class="auth-title">Administrator Sign In</h1>
        <p class="auth-subtitle">Operations access. Manage TrekAdvisor dashboard with a clear, focused sign-in.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.store') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="eg: sumeet.admin@gmail.com" maxlength="255" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <div class="auth-row" style="margin-bottom: 8px;">
                <x-input-label for="password" :value="__('Password')" style="margin-bottom: 0;" />
                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}" style="font-size: 0.85rem;">
                        {{ __('Forgot Access?') }}
                    </a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="Enter secure password" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="auth-row">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember this device') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Admin Authenticate') }}
            </button>
        </div>

        <div class="auth-footer">
            <a class="auth-link" href="{{ route('login') }}">
                Back to customer sign in
            </a>
        </div>
    </form>
</x-guest-layout>
