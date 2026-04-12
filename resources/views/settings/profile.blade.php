@php
    use Illuminate\Support\Facades\Storage;

    $avatarUrl = $user->avatar_path ? Storage::url($user->avatar_path) : null;
    $initials = strtoupper(substr($user->name ?? '', 0, 2));
@endphp

<x-settings.layout title="Profile" subtitle="Manage your details and profile photo.">
    @if (session('status') === 'profile-updated')
        <div class="account-status is-success" style="margin-bottom: 1rem;" data-flash-message>Profile updated successfully.</div>
    @elseif (session('status') === 'avatar-updated')
        <div class="account-status is-success" style="margin-bottom: 1rem;" data-flash-message>Avatar updated successfully.</div>
    @elseif (session('status') === 'avatar-removed')
        <div class="account-status is-success" style="margin-bottom: 1rem;" data-flash-message>Avatar removed successfully.</div>
    @endif

    <section class="settings-panel p-6">
        <div class="account-panel__head">
            <div>
                <h2>Profile Photo</h2>
                <p class="mt-1">Upload a square image for the best results.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4 mt-2">
            <div style="width: 88px; height: 88px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: 700; font-size: 1.4rem; color: #0f172a;">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ $initials }}
                @endif
            </div>

            <div style="flex: 1; min-width: 260px;">
                <form method="POST" action="{{ route('settings.avatar.store') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <div class="flex flex-col">
                        <input type="file" name="avatar" accept="image/*" required>
                        <div class="settings-help mt-2">JPG/PNG, max 2MB, square recommended.</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="settings-btn settings-btn--primary">Upload Avatar</button>
                        @if ($avatarUrl)
                            <button type="button" class="settings-btn settings-btn--danger" onclick="document.getElementById('avatar-remove-form').requestSubmit()">Remove</button>
                        @endif
                    </div>
                </form>
                @error('avatar')
                    <span class="settings-error">{{ $message }}</span>
                @enderror

                @if ($avatarUrl)
                    <form id="avatar-remove-form" method="POST" action="{{ route('settings.avatar.destroy') }}" style="margin-top: 0.75rem;" onsubmit="event.preventDefault(); if (window.showConfirm) { showConfirm({ title: 'Remove profile photo?', message: 'This will remove your profile photo. You can upload a new one anytime.', buttonText: 'Remove', buttonClass: 'confirm-btn--secondary', form: this }); } else { if (confirm('Remove profile photo?\nThis will remove your profile photo. You can upload a new one anytime.')) { this.submit(); } }">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </section>

    <section class="settings-panel p-6">
        <div class="account-panel__head flex items-start justify-between gap-4">
            <div>
                <h2>Profile Details</h2>
                <p class="mt-1">Keep your contact details up to date.</p>
            </div>
            <button type="button" class="settings-btn settings-btn--secondary" id="profileEditToggle">Edit Profile</button>
        </div>

        <div id="profileReadOnly" class="mt-2">
            <div class="account-meta-grid gap-4">
                <span><strong>Name:</strong> {{ $user->name }}</span>
                <span><strong>Email:</strong> {{ $user->email }}</span>
                <span><strong>Phone:</strong> {{ $user->phone ?: 'Not provided' }}</span>
                <span><strong>Address:</strong> {{ $user->address ?: 'Not provided' }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.profile.update') }}" class="account-form settings-form mt-2" id="profileEditForm" style="display: none;">
            @csrf
            @method('PATCH')

            <div class="profile-details-form">
                <label>
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                    @error('name')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                    @error('email')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    <span>Phone</span>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="10-digit phone">
                    <small class="settings-help">Optional. Use a 10-digit phone number.</small>
                    @error('phone')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
                <div></div>
                <label class="profile-details-form__full">
                    <span>Address</span>
                    <textarea name="address" rows="4" maxlength="500">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <small class="settings-error">{{ $message }}</small>
                    @enderror
                </label>
            </div>

            <div class="profile-details-form__actions">
                <button type="submit" class="settings-btn settings-btn--primary">Save Profile</button>
                <button type="button" class="settings-btn settings-btn--outline" id="profileEditCancel">Cancel</button>
            </div>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editToggle = document.getElementById('profileEditToggle');
            var editCancel = document.getElementById('profileEditCancel');
            var readOnly = document.getElementById('profileReadOnly');
            var editForm = document.getElementById('profileEditForm');

            if (!editToggle || !editCancel || !readOnly || !editForm) {
                return;
            }

            var setEditing = function (isEditing) {
                readOnly.style.display = isEditing ? 'none' : 'block';
                editForm.style.display = isEditing ? 'block' : 'none';
                editToggle.style.display = isEditing ? 'none' : 'inline-flex';
            };

            editToggle.addEventListener('click', function () {
                setEditing(true);
            });

            editCancel.addEventListener('click', function () {
                setEditing(false);
            });

            setEditing(false);
        });
    </script>
</x-settings.layout>
