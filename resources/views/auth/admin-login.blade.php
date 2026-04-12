<x-guest-layout>
    <a href="{{ route('home') }}" class="auth-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>

    <div class="auth-brand-area auth-brand-area--signin">
        <div class="auth-brand-mark"><img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" alt="TrekAdvisor logo" /></div>
        <span>TrekAdvisor</span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <div class="auth-header auth-header--signin-simple">
        <h1 class="auth-title auth-title--signin">
            <i class="fas fa-user-shield"></i>
            <span>Admin Login</span>
        </h1>
        <div class="auth-divider" aria-hidden="true"></div>
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
            <div class="auth-row auth-row--tight">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="auth-link auth-link--subtle" href="{{ route('password.request') }}">
                        {{ __('Forgot Access?') }}
                    </a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                minlength="8" required autocomplete="current-password" placeholder="Enter secure password" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password" aria-controls="password" aria-pressed="false">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
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
