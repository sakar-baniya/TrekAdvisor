<x-settings.layout title="Security" subtitle="Manage your password and sign-in security.">
    @if (session('status') === 'password-updated')
        <div class="account-status is-success" style="margin-bottom: 1rem;" data-flash-message>Password updated successfully.</div>
    @endif

    <section class="settings-panel p-6">
        <div class="account-panel__head">
            <div>
                <h2>Password</h2>
                <p class="mt-1">Update your password from a dedicated screen.</p>
            </div>
        </div>
        <div class="settings-actions flex flex-wrap items-center gap-4 mt-2">
            <a href="{{ route('settings.security.password.show') }}" class="settings-btn settings-btn--primary">Change Password</a>
        </div>
    </section>
</x-settings.layout>
