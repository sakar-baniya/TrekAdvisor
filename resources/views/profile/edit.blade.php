<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile - {{ config('app.name', 'TrekAdvisor') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; }
        
        .profile-container { max-width: 600px; margin: 3rem auto; padding: 1.5rem; }
        .profile-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 2rem; }
        
        .profile-title { font-size: 1.75rem; font-weight: 700; color: #184E6C; margin: 0 0 0.5rem; }
        .profile-subtitle { color: #6F8798; font-size: 1rem; margin: 0 0 2rem; }
        
        .form-group { margin-bottom: 1.75rem; }
        .form-label { display: block; font-size: 0.95rem; font-weight: 600; color: #184E6C; margin-bottom: 0.75rem; }
        .form-input, .form-textarea { width: 100%; padding: 0.875rem 1rem; font-size: 0.95rem; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; transition: all 0.2s ease; }
        .form-input:focus, .form-textarea:focus { outline: none; border-color: #184E6C; box-shadow: 0 0 0 3px rgba(24, 78, 108, 0.1); }
        .form-input.error, .form-textarea.error { border-color: #ef4444; background: #fff5f5; }
        .form-error { display: block; color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; }
        .form-textarea { resize: vertical; min-height: 120px; }
        
        .form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn { padding: 0.875rem 1.75rem; border-radius: 8px; font-size: 0.95rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
        .btn-primary { background: #184E6C; color: white; }
        .btn-primary:hover { background: #0f3449; }
        .btn-secondary { background: #e5e7eb; color: #334155; }
        .btn-secondary:hover { background: #d1d5db; }
        
        .form-success { margin-top: 1.5rem; padding: 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: 0.95rem; }
        
        @media (max-width: 640px) {
            .profile-container { margin: 1.5rem auto; padding: 1rem; }
            .profile-card { padding: 1.5rem; }
            .profile-title { font-size: 1.5rem; }
            .form-actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body class="body-app">
    <div class="page-shell">
        <header class="site-header">
            @include('layouts.navigation')
        </header>

        <main class="page-content">
            <div class="profile-container">
                <div class="profile-card">
                    <h1 class="profile-title">Edit Profile</h1>
                    <p class="profile-subtitle">Update your account details</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') error @enderror"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-input @error('phone') error @enderror"
                                value="{{ old('phone', $user->phone ?? '') }}"
                            >
                            @error('phone')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea
                                id="address"
                                name="address"
                                class="form-textarea @error('address') error @enderror"
                            >{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Save Changes
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times" style="margin-right: 0.5rem;"></i> Cancel
                            </a>
                        </div>

                        <!-- Success Message -->
                        @if (session('status'))
                            <div class="form-success">
                                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                                Profile updated successfully!
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </main>
    </div>

    <footer class="site-footer">
        <div class="container section">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <span class="footer-brand-badge"><i class="fas fa-mountain"></i></span>
                        TrekAdvisor
                    </div>
                    <p class="footer-muted footer-text">
                        Trek the Himalayas, plan stays, and rent gear from one beautiful marketplace.
                    </p>
                </div>
                <div>
                    <p class="footer-title">Quick Links</p>
                    <ul class="footer-muted footer-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('treks.index') }}">Treks</a></li>
                        <li><a href="{{ route('hotels.index') }}">Hotels</a></li>
                        <li><a href="{{ route('gear.index') }}">Gear Rental</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Company</p>
                    <ul class="footer-muted footer-list">
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Legal</p>
                    <ul class="footer-muted footer-list">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-muted">&copy; {{ date('Y') }} TrekAdvisor. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
