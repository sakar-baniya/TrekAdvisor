{{-- resources/views/components/dashboard/profile-menu.blade.php --}}

@php
    $user = auth()->user();
@endphp

<div class="dashboard-profile-menu">
    <button type="button" class="dashboard-profile-menu__trigger" data-profile-menu-toggle>
        <div class="dashboard-profile-menu__avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <span class="dashboard-profile-menu__name">{{ $user->name }}</span>
        <i class="fas fa-chevron-down dashboard-profile-menu__chev"></i>
    </button>

    <div class="dashboard-profile-dropdown" data-profile-menu-dropdown>
        <div class="dashboard-profile-dropdown__header">
            <div class="dashboard-profile-dropdown__avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="dashboard-profile-dropdown__info">
                <p class="dashboard-profile-dropdown__name">{{ $user->name }}</p>
                <p class="dashboard-profile-dropdown__email">{{ $user->email }}</p>
            </div>
        </div>

        <ul class="dashboard-profile-dropdown__menu">
            <li class="dashboard-profile-dropdown__item">
                <a href="{{ route('profile.show') }}" class="dashboard-profile-dropdown__link">
                    <i class="fas fa-user-circle"></i>
                    <span>View Profile</span>
                </a>
            </li>

            <li class="dashboard-profile-dropdown__item">
                <a href="{{ route('profile.edit') }}" class="dashboard-profile-dropdown__link">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </a>
            </li>
        </ul>

        <div class="dashboard-profile-dropdown__logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dashboard-profile-dropdown__button">
                    <i class="fas fa-right-from-bracket"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (() => {
        const trigger = document.querySelector('[data-profile-menu-toggle]');
        const dropdown = document.querySelector('[data-profile-menu-dropdown]');

        if (!trigger || !dropdown) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('open');
            trigger.classList.toggle('open', isOpen);
        });

        document.addEventListener('click', () => {
            dropdown.classList.remove('open');
            trigger.classList.remove('open');
        });

        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                dropdown.classList.remove('open');
                trigger.classList.remove('open');
            }
        });
    })();
</script>
