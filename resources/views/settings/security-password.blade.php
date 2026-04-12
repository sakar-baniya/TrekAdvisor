<x-settings.layout title="Change Password" subtitle="Confirm your current password to update it.">
    @if (session('status') === 'password-updated')
        <div class="account-status is-success" style="margin-bottom: 1rem;" data-flash-message>Password updated successfully.</div>
    @endif

    <section class="settings-panel p-6">
        <div class="account-panel__head">
            <div>
                <h2>Update Password</h2>
                <p class="mt-1">Enter your current password to confirm this change.</p>
            </div>
            <a href="{{ route('settings.security.show') }}" class="settings-btn settings-btn--outline">Back</a>
        </div>

        <form method="POST" action="{{ route('settings.security.password') }}" class="account-form settings-form">
            @csrf
            @method('PATCH')

            <div class="account-form__grid">
                <label>
                    <span>Current Password</span>
                    <input type="password" name="current_password" required autocomplete="current-password">
                    @error('current_password')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>New Password</span>
                    <input type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Confirm Password</span>
                    <input type="password" name="password_confirmation" required autocomplete="new-password">
                </label>
            </div>

            <div class="settings-actions mt-4">
                <button type="submit" class="settings-btn settings-btn--primary">Update Password</button>
                <a href="{{ route('settings.security.show') }}" class="settings-btn settings-btn--outline">Cancel</a>
            </div>
        </form>
    </section>
</x-settings.layout>
