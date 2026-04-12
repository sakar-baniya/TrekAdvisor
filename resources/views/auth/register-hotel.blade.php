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
        <h1 class="auth-title">Join as a Hotel Partner</h1>
        <p class="auth-subtitle">List your hotel and reach trekkers from around the globe.</p>
    </div>

    <form method="POST" action="{{ route('register.hotel') }}" class="auth-form">
        @csrf

        <div class="auth-grid auth-grid--two">
            <div class="auth-field">
                <x-input-label for="name" :value="__('Owner Full Name')" />
                <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="eg: Rajesh Hamal" maxlength="80" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="auth-field">
                <x-input-label for="phone" :value="__('Business Phone (optional)')" />
                <x-text-input id="phone" class="auth-input" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="eg: 9800012345" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" />
                <x-input-error :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="auth-grid auth-grid--two">
            <div class="auth-field">
                <x-input-label for="email" :value="__('Business Email')" />
                <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="eg: property@everest.com" maxlength="255" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="auth-field">
                <x-input-label for="hotel_name" :value="__('Hotel/Property Name')" />
                <x-text-input id="hotel_name" class="auth-input" type="text" name="hotel_name" :value="old('hotel_name')" required placeholder="eg: Hotel Everest View" maxlength="255" />
                <x-input-error :messages="$errors->get('hotel_name')" />
            </div>
        </div>

        <!-- Password -->
        <div class="auth-grid auth-grid--two">
            <div class="auth-field">
                <x-input-label for="password" :value="__('Password')" />
                <div class="auth-input-wrap">
                    <x-text-input id="password" class="auth-input auth-input-password"
                                    type="password"
                                    name="password" minlength="8"
                                    required autocomplete="new-password" placeholder="Min. 8 chars" />
                    <button type="button" class="auth-password-toggle" data-toggle-password data-target="password" aria-label="Show password">
                        <i class="fas fa-eye" data-icon="show"></i>
                        <i class="fas fa-eye-slash is-hidden" data-icon="hide"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="auth-field">
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

        <div class="auth-status" style="margin-bottom: 24px; padding: 16px; background: #fffbeb; border-radius: 12px; font-size: 0.85rem; color: #92410e; border: 1px solid #fef3c7;">
            <i class="fas fa-info-circle"></i> Hotel partner accounts require manual verification by our team before you can list properties.
        </div>

        <div>
            <button type="submit" class="auth-submit">
                {{ __('Apply for Partnership') }}
            </button>
        </div>

        <div class="auth-footer" style="margin-top: 32px; border-top: 1px solid #d9e4ee; padding-top: 24px;">
            <div class="auth-footer-copy">
                Not a hotel owner?
                <a class="auth-link" href="{{ route('register') }}" style="font-weight: 700;">
                    Register as a Trekker
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
