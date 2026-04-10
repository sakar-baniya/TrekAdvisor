@props(['user'])

<div class="position-relative">
    <button type="button" class="topbar-control" id="profileMenuTrigger">
        <div class="topbar-control__avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <span class="topbar-control__name">{{ $user->name }}</span>
        <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem; opacity: 0.3;"></i>
    </button>

    <div class="v-dropdown shadow-lg" id="profileDropdown">
        <div class="v-dropdown__header">
            <h4 class="mb-0 fs-6 fw-bold text-navy">{{ $user->name }}</h4>
            <p class="mb-0 text-muted" style="font-size: 0.75rem;">{{ $user->email }}</p>
        </div>

        <div class="v-dropdown__menu" style="display: flex; flex-direction: column; gap: 0; padding: 8px 0;">
            <a href="{{ route('profile.show') }}" class="v-dropdown__link" style="min-height:44px;">
                <i class="fas fa-cog opacity-50"></i>
                <span>Profile Settings</span>
            </a>
            <a href="{{ route('home') }}" class="v-dropdown__link" style="min-height:44px;">
                <i class="fas fa-home opacity-50"></i>
                <span>Back to Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button
                    type="button"
                    class="v-dropdown__link w-100 border-0 bg-transparent text-start"
                    style="min-height:44px;"
                    onclick="if (window.showConfirm) { showConfirm({ title: 'Sign Out', message: 'Are you sure you want to sign out?', buttonText: 'Sign Out', buttonClass: 'confirm-btn--secondary', form: this.closest('form') }); } else { this.closest('form').submit(); }"
                >
                    <i class="fas fa-sign-out-alt opacity-50"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>
