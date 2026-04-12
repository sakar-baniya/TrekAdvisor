<x-guest-layout>
    <a href="{{ route('home') }}" class="auth-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to camp</span>
    </a>

    <div class="auth-brand-area" >
        <div class="auth-brand-mark">⛰</div>
        <span>TrekAdvisor</span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <div class="auth-header">
        <p class="auth-kicker">New Account</p>
        <h1 class="auth-title">Join TrekAdvisor</h1>
        <p class="auth-subtitle">Create your account to book treks, manage stays, and explore the Himalayas.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-grid auth-grid--two" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div class="auth-field" style="margin-bottom: 0;">
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="eg: Suman Shrestha" maxlength="80" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="auth-field" style="margin-bottom: 0;">
                <x-input-label for="phone" :value="__('Phone (optional)')" />
                <x-text-input id="phone" class="auth-input" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="eg: 9800012345" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" />
                <x-input-error :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="auth-field" style="margin-bottom: 24px;">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="eg: suman.nepal@gmail.com" maxlength="255" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="auth-grid auth-grid--two" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div class="auth-field" style="margin-bottom: 0;">
                <x-input-label for="password" :value="__('Password')" />
                <div class="auth-input-wrap">
                    <x-text-input id="password" class="auth-input auth-input-password"
                                    type="password"
                                    name="password" minlength="8"
                                    required autocomplete="new-password" placeholder="Create a password" />
                    <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password">
                        <i class="fas fa-eye" data-icon="show"></i>
                        <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="auth-field" style="margin-bottom: 0;">
                <x-input-label for="password_confirmation" :value="__('Confirm')" />
                <div class="auth-input-wrap">
                    <x-text-input id="password_confirmation" class="auth-input auth-input-password"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Verify password" minlength="8" />
                    <button type="button" class="auth-password-toggle" data-toggle-password data-target="password_confirmation" aria-label="Show password confirmation">
                        <i class="fas fa-eye" data-icon="show"></i>
                        <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <button type="submit" class="auth-submit">
            {{ __('Secure Sign Up') }}
        </button>

        <div class="auth-footer" style="margin-top: 32px; border-top: 1.5px solid #f1f5f9; padding-top: 24px;">
            <div class="auth-footer-copy">
                Are you a service provider? 
                <a class="auth-link" href="{{ route('register.hotel') }}">
                    Partner with TrekAdvisor
                </a>
            </div>
            <div class="auth-footer-copy" style="margin-top: 12px;">
                Already have an account?
                <a class="auth-link" href="{{ route('login') }}">
                    Sign in
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
