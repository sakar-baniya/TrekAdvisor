@props([
    'title' => 'Settings',
    'subtitle' => 'Manage your profile and security preferences.'
])

@php
    $role = auth()->user()?->role;
    $isDashboard = in_array($role, ['admin', 'staff', 'hotel_owner'], true);
    $layoutComponent = $isDashboard ? 'dashboard-layout' : 'app-layout';
    $shellClass = $isDashboard ? 'account-shell settings-shell--dashboard' : 'account-shell settings-shell settings-shell--customer';
    $containerClass = $isDashboard ? 'settings-container settings-container--dashboard' : 'settings-container settings-container--customer';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <style>
        .settings-shell { padding-top: 8px; }
        .settings-shell--dashboard { padding: 0 0 2rem !important; margin-top: 0; }
        .settings-shell--customer { padding: 0 !important; }
        .settings-container { max-width: 64rem; margin: 0 auto; width: 100%; padding: 0 16px; }
        .settings-container--dashboard { max-width: 1200px; padding: 0; }
        .settings-container--customer { max-width: 600px; margin: 3rem auto; padding: 1.5rem; }
        .p-6 { padding: 24px; }
        .p-4 { padding: 16px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .gap-6 { gap: 24px; }
        .space-y-4 { display: flex; flex-direction: column; gap: 16px; }
        .flex { display: flex; }
        .flex-col { display: flex; flex-direction: column; }
        .flex-wrap { flex-wrap: wrap; }
        .items-center { align-items: center; }
        .items-start { align-items: flex-start; }
        .justify-between { justify-content: space-between; }

        .settings-header h1 { margin: 0; font-size: 1.9rem; font-weight: 800; color: #0f172a; }
        .settings-subtitle { color: #64748b; }
        .settings-panel { border-radius: 16px; background: #ffffff; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08); }
        .settings-panel h2 { margin: 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; }
        .settings-panel p { color: #64748b; }
        .settings-tabs { display: inline-flex; gap: 6px; padding: 4px; background: #f1f5f9; border-radius: 999px; }
        .settings-tab { min-height: 36px; padding: 0 14px; border-radius: 999px; font-weight: 700; font-size: 0.88rem; color: #475569; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .settings-tab.is-active { background: #0f172a; color: #ffffff; }
        .settings-tab:focus-visible { outline: 3px solid rgba(15, 23, 42, 0.35); outline-offset: 2px; }
        .settings-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .settings-btn { min-height: 44px; padding: 0 18px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid transparent; cursor: pointer; text-decoration: none; }
        .settings-btn--primary { background: #0f172a; color: #ffffff; }
        .settings-btn--secondary { background: #e2e8f0; color: #0f172a; }
        .settings-btn--outline { background: transparent; color: #0f172a; border-color: #cbd5f5; }
        .settings-btn--danger { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .settings-btn:focus-visible { outline: 3px solid rgba(15, 23, 42, 0.35); outline-offset: 2px; }
        .settings-form label { display: block; font-weight: 700; color: #0f172a; }
        .settings-form input, .settings-form textarea { margin-top: 8px; }
        .settings-help { color: #64748b; font-size: 0.85rem; }
        .settings-error { display: block; margin-top: 6px; color: #ef4444; font-size: 0.85rem; }
        .profile-details-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px 20px; }
        .profile-details-form label { display: flex; flex-direction: column; gap: 6px; font-weight: 700; color: #0f172a; }
        .profile-details-form input,
        .profile-details-form textarea {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            font-size: 0.95rem;
            color: #0f172a;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            box-sizing: border-box;
        }
        .profile-details-form textarea { min-height: 110px; resize: vertical; }
        .profile-details-form input:focus,
        .profile-details-form textarea:focus {
            outline: none;
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }
        .profile-details-form__full { grid-column: 1 / -1; }
        .profile-details-form__actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
        @media (max-width: 768px) {
            .profile-details-form { grid-template-columns: 1fr; }
            .profile-details-form__actions { justify-content: flex-start; }
        }
    </style>

    <section class="{{ $shellClass }}">
        <div class="{{ $containerClass }}">
            <div class="settings-header mb-2">
                <div>
                    <h1>{{ $title }}</h1>
                    @if (!empty($subtitle))
                        <p class="settings-subtitle mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <section class="settings-panel p-4">
                    <div class="account-panel__head">
                        <div>
                            <h2>Settings</h2>
                        </div>
                    </div>

                    <div class="settings-tabs mt-2">
                        @php
                            $isProfile = request()->routeIs('settings.profile.*');
                            $isSecurity = request()->routeIs('settings.security.*');
                        @endphp
                        <a href="{{ route('settings.profile.show') }}" class="settings-tab {{ $isProfile ? 'is-active' : '' }}">Profile</a>
                        <a href="{{ route('settings.security.show') }}" class="settings-tab {{ $isSecurity ? 'is-active' : '' }}">Security</a>
                    </div>
                </section>

                {{ $slot }}
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-flash-message]').forEach(function (flash) {
                setTimeout(function () {
                    flash.style.opacity = '0';
                    flash.style.transition = 'opacity 0.3s ease';
                    setTimeout(function () {
                        if (flash && flash.parentNode) {
                            flash.parentNode.removeChild(flash);
                        }
                    }, 350);
                }, 2500);
            });
        });
    </script>
</x-dynamic-component>
