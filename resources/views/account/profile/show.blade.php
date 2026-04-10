<x-app-layout>
    <section class="account-shell">
        <div class="container">
            <div class="account-header">
                <div>
                    <p class="market-kicker">My Account</p>
                    <h1>Profile</h1>
                    <p>Update your contact details and password.</p>
                </div>
            </div>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>Profile Details</h2>
                        <p>Keep your details up to date for bookings and receipts.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('account.profile.update') }}" class="account-form">
                    @csrf
                    @method('PATCH')

                    <div class="account-form__grid">
                        <label>
                            <span>Name</span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </label>
                        <label>
                            <span>Email</span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </label>
                        <label>
                            <span>Phone</span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </label>
                        <label class="account-form__full">
                            <span>Address</span>
                            <textarea name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        </label>
                    </div>

                    <button type="submit" class="market-button">Save Profile</button>
                </form>
            </section>

            <section class="account-panel">
                <div class="account-panel__head">
                    <div>
                        <h2>Change Password</h2>
                        <p>Use a strong password to protect your account.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('account.profile.password') }}" class="account-form">
                    @csrf
                    @method('PATCH')

                    <div class="account-form__grid">
                        <label>
                            <span>Current Password</span>
                            <input type="password" name="current_password" required>
                        </label>
                        <label>
                            <span>New Password</span>
                            <input type="password" name="password" required>
                        </label>
                        <label>
                            <span>Confirm Password</span>
                            <input type="password" name="password_confirmation" required>
                        </label>
                    </div>

                    <button type="submit" class="market-button">Update Password</button>
                </form>
            </section>
        </div>
    </section>
</x-app-layout>
