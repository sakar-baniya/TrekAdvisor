{{-- resources/views/components/dashboard/profile-edit-modal.blade.php --}}

@props(['user'])

<div class="dashboard-modal-overlay" data-profile-modal-overlay>
    <div class="dashboard-modal" data-profile-modal>
        <div class="dashboard-modal__header">
            <h2 class="dashboard-modal__title">Edit Profile</h2>
            <button type="button" class="dashboard-modal__close" data-profile-modal-close>
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="dashboard-modal__body">
            <form method="POST" action="{{ route('settings.profile.update') }}" id="profile-edit-form">
                @csrf
                @method('PATCH')

                <!-- Full Name -->
                <div class="dashboard-form-group">
                    <label for="modal-name" class="dashboard-form-label">Full Name</label>
                    <input 
                        type="text" 
                        id="modal-name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="dashboard-form-input @error('name') error @enderror"
                        required
                        maxlength="255"
                    />
                    @error('name')
                        <span class="dashboard-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="dashboard-form-group">
                    <label for="modal-email" class="dashboard-form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="modal-email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="dashboard-form-input @error('email') error @enderror"
                        required
                        maxlength="255"
                    />
                    @error('email')
                        <span class="dashboard-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="dashboard-form-group">
                    <label for="modal-phone" class="dashboard-form-label">Phone Number</label>
                    <input 
                        type="tel" 
                        id="modal-phone" 
                        name="phone" 
                        value="{{ old('phone', $user->phone ?? '') }}"
                        class="dashboard-form-input @error('phone') error @enderror"
                        pattern="\d{10}"
                        inputmode="numeric"
                        minlength="10"
                        maxlength="10"
                        placeholder="10-digit phone"
                    />
                    <small class="dashboard-form-hint">Optional. Use a 10-digit phone number.</small>
                    @error('phone')
                        <span class="dashboard-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div class="dashboard-form-group">
                    <label for="modal-address" class="dashboard-form-label">Address</label>
                    <textarea 
                        id="modal-address" 
                        name="address" 
                        class="dashboard-form-textarea @error('address') error @enderror"
                        rows="3"
                        maxlength="500"
                    >{{ old('address', $user->address ?? '') }}</textarea>
                    @error('address')
                        <span class="dashboard-form-error">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>

        <div class="dashboard-modal__footer">
            <button type="button" class="dashboard-btn dashboard-btn-secondary" data-profile-modal-close>
                Cancel
            </button>
            <button type="submit" form="profile-edit-form" class="dashboard-btn dashboard-btn-primary">
                Save Changes
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        const overlay = document.querySelector('[data-profile-modal-overlay]');
        const modal = document.querySelector('[data-profile-modal]');
        const closeBtn = document.querySelector('[data-profile-modal-close]');

        if (!overlay || !modal) return;

        function openModal() {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });

        closeBtn?.addEventListener('click', closeModal);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('open')) {
                closeModal();
            }
        });

        // Expose to global scope
        window.openProfileModal = openModal;
    })();
</script>
