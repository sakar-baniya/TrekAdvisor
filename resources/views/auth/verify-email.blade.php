<x-guest-layout>
    <div class="auth-header auth-header--centered">
        <p class="auth-kicker">Verify Email</p>
        <h1 class="auth-title">Check your inbox</h1>
        <p class="auth-subtitle">{{ __('Before getting started, please verify your email address using the link we just sent you.') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="auth-actions auth-actions-row auth-actions--centered">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="auth-submit">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="auth-link">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
