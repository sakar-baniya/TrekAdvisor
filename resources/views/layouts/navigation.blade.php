@php
    $navVariant = $navVariant ?? 'default';
    $navClusterClass = $navVariant === 'auth' ? 'site-nav-cluster site-nav-cluster--auth' : 'site-nav-cluster';
    $navClass = $navVariant === 'auth' ? 'site-nav site-nav--auth' : 'site-nav';
    $navMobileClass = $navVariant === 'auth' ? 'nav-mobile nav-mobile--auth' : 'nav-mobile';
    $user = auth()->user();
    $userRole = $user?->role;
@endphp

<div class="{{ $navClusterClass }}" data-nav-shell>
    <nav class="{{ $navClass }}" aria-label="Primary">
        <div class="container nav-inner">
            <div class="nav-section nav-left">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-badge">
                        <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" alt="TrekAdvisor logo" />
                    </span>
                    <span class="brand-wordmark">TrekAdvisor</span>
                </a>
            </div>
            <div class="nav-section nav-center">
                <div class="nav-links">
                    <a href="{{ route('treks.index') }}" class="nav-link {{ request()->routeIs('treks.*') ? 'is-active' : '' }}">Treks</a>
                    <a href="{{ route('hotels.index') }}" class="nav-link {{ request()->routeIs('hotels.*') ? 'is-active' : '' }}">Hotels</a>

                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'is-active' : '' }}">Travel Guide</a>
                </div>
            </div>
            <div class="nav-section nav-right" style="display:flex;align-items:center;gap:1.25rem;">
                <a href="{{ route('contact') }}" class="btn btn-cta d-none d-md-inline-flex">Contact</a>
                @auth
                    <div class="nav-account-menu d-none d-md-block" style="position:relative;">
                        <button type="button" class="btn btn-link nav-account-trigger" id="navAccountTrigger" style="display:flex;align-items:center;gap:0.5rem;">
                            <span>My Account</span>
                            <i class="fas fa-chevron-down" style="font-size:0.85em;"></i>
                        </button>
                        <div class="nav-account-dropdown" id="navAccountDropdown" style="display:none;position:absolute;right:0;top:calc(100%+8px);min-width:180px;background:#fff;border-radius:10px;box-shadow:0 8px 24px rgba(15,23,42,0.13);z-index:1201;padding:0.5rem 0;">
                            @if ($userRole === 'customer')
                                <a href="{{ route('settings.profile.show') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-user-circle opacity-50"></i> Profile
                                </a>
                                <a href="{{ route('account.bookings.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-clipboard-list opacity-50"></i> My Bookings
                                </a>
                                <a href="{{ route('account.payments.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-credit-card opacity-50"></i> Payments
                                </a>
                            @elseif ($userRole === 'staff')
                                <a href="{{ route('staff.dashboard') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-headset opacity-50"></i> Staff Dashboard
                                </a>
                                <a href="{{ route('staff.trek-bookings.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-mountain-sun opacity-50"></i> Trek Bookings
                                </a>
                            @elseif ($userRole === 'hotel_owner')
                                <a href="{{ route('hotel_owner.dashboard') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-hotel opacity-50"></i> Hotel Dashboard
                                </a>
                                <a href="{{ route('hotel_owner.hotels.index') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-building opacity-50"></i> My Hotels
                                </a>
                            @elseif ($userRole === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-chart-line opacity-50"></i> Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="dropdown-item" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;text-decoration:none;font-weight:600;">
                                    <i class="fas fa-compass opacity-50"></i> Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;" data-logout-confirm>
                                @csrf
                                <button
                                    type="submit"
                                    class="dropdown-item"
                                    style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.25rem;color:#0F172A;background:none;border:none;width:100%;text-align:left;font-weight:600;"
                                    data-confirm="signout"
                                >
                                    <i class="fas fa-sign-out-alt opacity-50"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-auth btn-auth--ghost">Login</a>
                @endauth
                <button
                    type="button"
                    class="nav-toggle d-inline-flex d-md-none"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-controls="site-nav-mobile"
                    aria-label="Toggle navigation menu"
                >
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        <script>
        // Navbar My Account dropdown logic
        document.addEventListener('DOMContentLoaded', function() {
            var trigger = document.getElementById('navAccountTrigger');
            var dropdown = document.getElementById('navAccountDropdown');
            if (trigger && dropdown) {
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });
            }
        });

        </script>
        </div>
    </nav>

    <div id="site-nav-mobile" class="{{ $navMobileClass }}" data-nav-mobile>
        <div class="nav-mobile-links">
            <a href="{{ route('treks.index') }}" class="nav-link">Treks</a>
            <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>

            <a href="{{ route('about') }}" class="nav-link">About</a>
            <a href="{{ route('blog') }}" class="nav-link">Travel Guide</a>
            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            @auth
                @if ($userRole === 'customer')
                    <a href="{{ route('account.bookings.index') }}" class="nav-link">My Bookings</a>
                    <a href="{{ route('settings.profile.show') }}" class="nav-link">Profile</a>
                    <a href="{{ route('account.payments.index') }}" class="nav-link">Payments</a>
                @elseif ($userRole === 'staff')
                    <a href="{{ route('staff.dashboard') }}" class="nav-link">Staff Dashboard</a>
                    <a href="{{ route('staff.trek-bookings.index') }}" class="nav-link">Trek Bookings</a>
                @elseif ($userRole === 'hotel_owner')
                    <a href="{{ route('hotel_owner.dashboard') }}" class="nav-link">Hotel Dashboard</a>
                    <a href="{{ route('hotel_owner.hotels.index') }}" class="nav-link">My Hotels</a>
                @elseif ($userRole === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" data-logout-confirm>
                    @csrf
                    <button type="submit" class="btn btn-link" data-confirm="signout">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            @endauth
        </div>
    </div>
</div>

@if (!request()->is('admin*') && !request()->is('dashboard*'))
<!-- Floating WhatsApp Button (hidden on admin/dashboard routes) -->
<a href="https://wa.me/9779816681137" class="floating-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
@endif
