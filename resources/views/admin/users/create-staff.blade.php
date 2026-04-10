<x-dashboard-layout>
    <x-slot name="header">
        <div class="admin-page-heading">
            <div>
                <p class="admin-eyebrow">User Management</p>
                <h2 class="admin-page-title">Add Staff User</h2>
            </div>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="admin-flash error">Please fix the form errors and try again.</div>
    @endif

    <form method="POST" action="{{ route('admin.users.store-staff') }}">
        @csrf

        <div class="admin-form-stack">
            <section class="admin-panel">
                <div class="admin-panel__header">
                    <div>
                        <h3>Staff Details</h3>
                        <p>Create an admin or staff account for the dashboard</p>
                    </div>
                </div>

                <div class="admin-form-grid admin-form-grid--two">
                    <label class="admin-field">
                        <span>Name *</span>
                        <input type="text" name="name" value="{{ old('name') }}" class="admin-input" required />
                        @error('name') <small class="admin-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="admin-field">
                        <span>Email *</span>
                        <input type="email" name="email" value="{{ old('email') }}" class="admin-input" required />
                        @error('email') <small class="admin-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="admin-field">
                        <span>Phone</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="admin-input" />
                        @error('phone') <small class="admin-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="admin-field">
                        <span>Role *</span>
                        <select name="role" class="admin-input" required>
                            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        </select>
                        @error('role') <small class="admin-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="admin-field">
                        <span>Password *</span>
                        <input type="password" name="password" class="admin-input" required />
                        @error('password') <small class="admin-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="admin-field">
                        <span>Confirm Password *</span>
                        <input type="password" name="password_confirmation" class="admin-input" required />
                    </label>
                </div>
            </section>

            <div class="admin-form-actions">
                <a href="{{ route('admin.users.index') }}" class="admin-secondary-button">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Users</span>
                </a>
                <button type="button" class="admin-primary-button" data-confirm="create-user">
                    <i class="fas fa-user-plus"></i>
                    <span>Create User</span>
                </button>
            </div>
        </div>
    </form>
</x-dashboard-layout>
