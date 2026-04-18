@props(['user'])

@php
    use Illuminate\Support\Facades\Storage;
    $avatarUrl = $user->avatar_path ? Storage::url($user->avatar_path) : null;
@endphp

<div class="relative" x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = !open" 
            type="button" 
            id="user-menu-button" 
            aria-expanded="false" 
            aria-haspopup="true"
            class="flex items-center gap-2 text-slate-900 font-semibold hover:text-slate-700 transition">
        <span>My Account</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="absolute right-0 z-50 mt-2 bg-white rounded-2xl shadow-lg border border-slate-200 p-2 w-64 origin-top-right focus:outline-none" 
         role="menu" 
         aria-orientation="vertical" 
         aria-labelledby="user-menu-button" 
         tabindex="-1"
         style="display: none;">
        
        <div>
            <p class="text-xs text-slate-500 px-3 pt-2">Signed in as</p>
            <p class="text-sm font-semibold text-slate-900 px-3 pb-2 border-b border-slate-100 mb-1 truncate">{{ $user->name }}</p>
        </div>

        <div>
            <a href="{{ route('settings.profile.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900" role="menuitem">
                <i class="fas fa-cog w-4 h-4 text-slate-500 flex items-center justify-center"></i>
                Settings
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900" role="menuitem">
                <i class="fas fa-home w-4 h-4 text-slate-500 flex items-center justify-center"></i>
                Back to Website
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="flex w-full items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 text-left"
                    onclick="return confirm('Are you sure you want to sign out?')">
                <i class="fas fa-sign-out-alt w-4 h-4 text-slate-500 flex items-center justify-center"></i>
                Sign Out
            </button>
        </form>
    </div>
</div>
