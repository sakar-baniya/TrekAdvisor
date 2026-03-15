<x-guest-layout>
    <div class="auth-header">
        <p class="auth-kicker">New Account</p>
        <h1 class="auth-title">Create your account</h1>
        <p class="auth-subtitle">Join TrekAdvisor to book treks, hotels, and gear rentals.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Name -->
        <div class="auth-field">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone (optional) -->
        <div class="auth-field">
            <x-input-label for="phone" :value="__('Phone (optional)')" />
            <x-text-input id="phone" class="auth-input" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Account Type -->
        <div class="auth-field">
            <x-input-label for="role" :value="__('Register As')" />
            <div class="role-grid">
                <label class="role-card">
                    <input type="radio" name="role" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <span class="role-card-text">
                        <span class="role-card-title">Customer</span>
                        <span class="role-card-subtitle">Book treks and stays.</span>
                    </span>
                </label>
                <label class="role-card">
                    <input type="radio" name="role" value="hotel_owner" {{ old('role') === 'hotel_owner' ? 'checked' : '' }}>
                    <span class="role-card-text">
                        <span class="role-card-title">Hotel Owner</span>
                        <span class="role-card-subtitle">List rooms and manage bookings.</span>
                    </span>
                </label>
            </div>
            <p class="role-note">Hotel owner accounts require admin approval.</p>
            <x-input-error :messages="$errors->get('role')" />
        </div>

        <!-- Password -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password" class="auth-input auth-input-password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
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

        <!-- Confirm Password -->
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="auth-input-wrap">
                <x-text-input id="password_confirmation" class="auth-input auth-input-password"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <button type="button" data-toggle-password data-target="password_confirmation" class="auth-password-toggle">
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

            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Create account') }}
            </button>
        </div>

        <div class="auth-footer">
            Already have an account?
            <a class="auth-link" href="{{ route('login') }}">
                Sign in
            </a>
        </div>
    </form>
</x-guest-layout>

