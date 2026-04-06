<x-app-layout>
    <style>
        .profile-container { max-width: 600px; margin: 3rem auto; padding: 1.5rem; }
        .profile-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 2rem; }
        
        .profile-header { display: flex; gap: 1.5rem; align-items: flex-start; margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e7eb; }
        .profile-avatar { width: 80px; height: 80px; background: linear-gradient(135deg, #184E6C 0%, #2d6f91 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.75rem; flex-shrink: 0; }
        .profile-header-info { flex: 1; }
        .profile-header-info h2 { margin: 0; color: #184E6C; font-size: 1.5rem; font-weight: 700; }
        .profile-header-info p { margin: 0.25rem 0 0; color: #6F8798; font-size: 0.95rem; }
        
        .profile-actions { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .profile-btn { padding: 0.75rem 1.5rem; border-radius: 8px; font-size: 0.95rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
        .btn-primary { background: #184E6C; color: white; }
        .btn-primary:hover { background: #0f3449; }
        .btn-secondary { background: #e5e7eb; color: #334155; }
        .btn-secondary:hover { background: #d1d5db; }
        
        .profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; }
        .profile-field { }
        .profile-field-label { font-size: 0.85rem; font-weight: 600; color: #567487; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; display: block; }
        .profile-field-value { font-size: 1rem; color: #184E6C; font-weight: 500; }
        .profile-field-value.empty { color: #9CA3AF; font-style: italic; }
        
        .profile-field-input { width: 100%; padding: 0.75rem; font-size: 0.95rem; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; transition: all 0.2s ease; display: none; }
        .profile-field-input:focus { outline: none; border-color: #184E6C; box-shadow: 0 0 0 3px rgba(24, 78, 108, 0.1); }
        
        .profile-field-textarea { width: 100%; padding: 0.75rem; font-size: 0.95rem; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; transition: all 0.2s ease; display: none; resize: vertical; min-height: 100px; }
        .profile-field-textarea:focus { outline: none; border-color: #184E6C; box-shadow: 0 0 0 3px rgba(24, 78, 108, 0.1); }
        
        .profile-field.editing .profile-field-value { display: none; }
        .profile-field.editing .profile-field-input { display: block; }
        .profile-field.editing .profile-field-textarea { display: block; }
        
        .profile-full-width { grid-column: 1 / -1; }
        
        .profile-timestamps { padding-top: 2rem; border-top: 1px solid #e5e7eb; color: #6F8798; font-size: 0.9rem; line-height: 1.6; }
        
        .profile-edit-actions { display: flex; gap: 1rem; margin-top: 1.5rem; display: none; }
        .profile-edit-actions.show { display: flex; }
        
        .form-error { display: block; color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; }
        
        @media (max-width: 640px) {
            .profile-container { margin: 1.5rem auto; padding: 1rem; }
            .profile-card { padding: 1.5rem; }
            .profile-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .profile-header { flex-direction: column; align-items: center; text-align: center; }
            .profile-actions { flex-direction: column; }
            .btn { width: 100%; }
            .profile-edit-actions { flex-direction: column; }
        }
    </style>
    
    <div style="background: linear-gradient(135deg, #184E6C 0%, #2d6f91 100%); height: 260px; width: 100%; position: absolute; top: 0; left: 0; z-index: 0;"></div>

    <div style="position: relative; z-index: 10; padding-top: 100px;">
        <div class="profile-container">
                <div class="profile-card">
                    <!-- Header with Avatar -->
                    <div class="profile-header">
                        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="profile-header-info">
                            <h2>{{ $user->name }}</h2>
                            <p>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="profile-actions">
                        <button type="button" id="editBtn" class="profile-btn btn-primary" onclick="toggleEdit()">
                            <i class="fas fa-edit" style="margin-right: 0.5rem;"></i> Edit Profile
                        </button>
                        <a href="{{ request()->header('Referer') ? request()->header('Referer') : route('home') }}" class="profile-btn btn-secondary">
                            <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Back
                        </a>
                    </div>

                    <!-- Profile Form (for editing) -->
                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}" style="display: none;">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Information Grid -->
                        <div class="profile-grid">
                            <div class="profile-field" data-field="name">
                                <span class="profile-field-label">Full Name</span>
                                <div class="profile-field-value">{{ $user->name }}</div>
                                <input type="text" class="profile-field-input" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="profile-field" data-field="email">
                                <span class="profile-field-label">Email Address</span>
                                <div class="profile-field-value">{{ $user->email }}</div>
                                <input type="email" class="profile-field-input" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="profile-field" data-field="phone">
                                <span class="profile-field-label">Phone Number</span>
                                <div class="profile-field-value {{ !$user->phone ? 'empty' : '' }}">
                                    {{ $user->phone ?? 'Not provided' }}
                                </div>
                                <input type="tel" class="profile-field-input" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                @error('phone')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="profile-field" data-field="role">
                                <span class="profile-field-label">Account Type</span>
                                <div class="profile-field-value">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                            </div>

                            <div class="profile-field profile-full-width" data-field="address">
                                <span class="profile-field-label">Address</span>
                                <div class="profile-field-value {{ !$user->address ? 'empty' : '' }}">
                                    {{ $user->address ?? 'Not provided' }}
                                </div>
                                <textarea class="profile-field-textarea" name="address">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Edit Action Buttons -->
                        <div class="profile-edit-actions" id="editActions">
                            <button type="submit" class="profile-btn btn-primary">
                                <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Save Changes
                            </button>
                            <button type="button" class="profile-btn btn-secondary" onclick="toggleEdit()">
                                <i class="fas fa-times" style="margin-right: 0.5rem;"></i> Cancel
                            </button>
                        </div>
                    </form>

                    <!-- Profile View (default) -->
                    <div id="profileView">
                        <!-- Profile Information Grid -->
                        <div class="profile-grid">
                            <div class="profile-field">
                                <span class="profile-field-label">Full Name</span>
                                <div class="profile-field-value">{{ $user->name }}</div>
                            </div>

                            <div class="profile-field">
                                <span class="profile-field-label">Email Address</span>
                                <div class="profile-field-value">{{ $user->email }}</div>
                            </div>

                            <div class="profile-field">
                                <span class="profile-field-label">Phone Number</span>
                                <div class="profile-field-value {{ !$user->phone ? 'empty' : '' }}">
                                    {{ $user->phone ?? 'Not provided' }}
                                </div>
                            </div>

                            <div class="profile-field">
                                <span class="profile-field-label">Account Type</span>
                                <div class="profile-field-value">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                            </div>

                            <div class="profile-field profile-full-width">
                                <span class="profile-field-label">Address</span>
                                <div class="profile-field-value {{ !$user->address ? 'empty' : '' }}">
                                    {{ $user->address ?? 'Not provided' }}
                                </div>
                            </div>
                        </div>

                        <!-- Account Timestamps -->
                        <div class="profile-timestamps">
                            <strong>Member since:</strong> {{ $user->created_at->format('F d, Y') }}<br>
                            <strong>Last updated:</strong> {{ $user->updated_at->format('F d, Y \a\t g:i A') }}
                        </div>
                    </div>

                    @if (session('status'))
                        <div style="margin-top: 1.5rem; padding: 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: 0.95rem;">
                            <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                            Profile updated successfully!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    <script>
        function toggleEdit() {
            const profileView = document.getElementById('profileView');
            const profileForm = document.getElementById('profileForm');
            const editBtn = document.getElementById('editBtn');
            const editActions = document.getElementById('editActions');
            const fields = document.querySelectorAll('.profile-field');

            // Toggle visibility
            profileView.style.display = profileView.style.display === 'none' ? 'block' : 'none';
            profileForm.style.display = profileForm.style.display === 'none' ? 'block' : 'none';
            
            // Toggle button
            editBtn.style.display = editBtn.style.display === 'none' ? 'inline-flex' : 'none';
            editActions.classList.toggle('show');

            // Toggle field editing mode
            fields.forEach(field => {
                if (field.dataset.field !== 'role') {
                    field.classList.toggle('editing');
                }
            });
        }
    </script>
</x-app-layout>
