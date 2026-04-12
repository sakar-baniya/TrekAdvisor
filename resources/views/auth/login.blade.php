<x-guest-layout>
    <a href="{{ route('home') }}" class="auth-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>

    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-status">
            {{ session('status') }}
        </div>
    @endif

    <div class="auth-brand-area auth-brand-area--signin">
        <div class="auth-brand-mark"><img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" alt="TrekAdvisor logo" /></div>
        <span>TrekAdvisor</span>
    </div>

    <div class="auth-header auth-header--signin-simple">
        <h1 class="auth-title auth-title--signin">
            <i class="fas fa-user"></i>
            <span>Login</span>
        </h1>
        <div class="auth-divider" aria-hidden="true"></div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form auth-form--signin" aria-label="Login form">
        @csrf

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="eg: sandeep.nepal@gmail.com" maxlength="255" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                minlength="8" required autocomplete="current-password" placeholder="Enter your password" />
                <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password" aria-controls="password" aria-pressed="false">
                    <i class="fas fa-eye" data-icon="show"></i>
                    <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        @if (Route::has('password.request'))
            <div class="auth-row auth-row--forgot">
                <a class="auth-link auth-link--subtle" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            </div>
        @endif

        <div class="auth-submit-wrap">
            <button type="submit" class="auth-submit">
                {{ __('Login') }}
            </button>
        </div>

        <div class="auth-footer">
            New to TrekAdvisor?
            <a class="auth-link" href="{{ route('register') }}">
                Sign up today
            </a>
        </div>

    </form>
</x-guest-layout>
