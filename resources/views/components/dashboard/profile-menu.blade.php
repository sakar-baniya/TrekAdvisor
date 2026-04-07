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

        <ul class="list-unstyled mb-0 py-1">
            <li>
                <a href="{{ route('profile.show') }}" class="v-dropdown__link">
                    <i class="fas fa-cog opacity-50"></i> Profile Settings
                </a>
            </li>
            <li class="border-top my-1 opacity-25"></li>
            <li>
                <a href="{{ route('home') }}" class="v-dropdown__link">
                    <i class="fas fa-home opacity-50"></i> Back to Website
                </a>
            </li>
        </ul>

        <div class="border-top py-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="v-dropdown__link w-100 border-0 bg-transparent text-start">
                    <i class="fas fa-sign-out-alt opacity-50"></i> Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
