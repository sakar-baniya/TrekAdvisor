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
    @if (session('status'))
        <div class="auth-status">
            {{ session('status') }}
        </div>
    @endif

    <div class="auth-header">
        <p class="auth-kicker">Welcome Back</p>
        <h1 class="auth-title">Sign in to TrekAdvisor</h1>
        <p class="auth-subtitle">Plan and book your Himalayan journey and keep every booking in one place.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="eg: sandeep.nepal@gmail.com" />
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
                                required autocomplete="current-password" placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="auth-row">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Keep me signed in') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Secure Sign In') }}
            </button>
        </div>

        <div class="auth-footer">
            New to TrekAdvisor?
            <a class="auth-link" href="{{ route('register') }}">
                Sign up today
            </a>
        </div>

        <div class="auth- поддержка-note">
            <strong>Staff & Admins:</strong> Use <a href="{{ route('admin.login') }}" class="auth-link">Admin Portal</a>
        </div>
    </form>
</x-guest-layout>
