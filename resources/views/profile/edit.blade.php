<x-app-layout>
    <x-slot name="header">
        <div class="account-header">
            <div>
                <p class="market-kicker">Settings</p>
                <h1>{{ __('Profile & Account') }}</h1>
                <p>Manage your personal information, password, and account preferences.</p>
            </div>
        </div>
    </x-slot>

    <div class="section">
        <div class="container profile-stack">
            <div class="profile-card">
                <div class="profile-card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-card profile-card--danger">
                <div class="profile-card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
