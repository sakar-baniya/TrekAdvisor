@php
    use Illuminate\Support\Facades\Storage;
    $avatarUrl = $user->avatar_path ? Storage::url($user->avatar_path) : null;
    $initials = strtoupper(substr($user->name ?? '', 0, 1));
@endphp

<x-customer-layout 
    title="Profile Settings" 
    subtitle="Update your personal details and public profile information."
    :breadcrumb="['Settings' => route('settings.profile.show'), 'Profile' => null]"
>
    @if (session('status'))
        <div class="mb-6 animate-fadeIn">
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg border border-emerald-100 text-xs font-semibold">
                <i class="fas fa-check-circle mr-2 opacity-70"></i> {{ ucfirst(str_replace('-', ' ', session('status'))) }} successful
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm text-center">
                <div class="relative inline-block group mb-4">
                    <div class="w-20 h-20 rounded-full bg-slate-900 flex items-center justify-center text-white text-2xl font-bold overflow-hidden">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <label for="avatar-input" class="absolute bottom-0 right-0 w-7 h-7 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 cursor-pointer hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-camera text-[10px]"></i>
                    </label>
                </div>
                <h3 class="text-base font-semibold text-slate-900">{{ $user->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ ucfirst($user->role) }} Account</p>

                @if($avatarUrl)
                    <form method="POST" action="{{ route('settings.avatar.destroy') }}" class="mt-3" onsubmit="return confirm('Are you sure you want to remove your photo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                            <i class="fas fa-trash-alt mr-1 text-[10px]"></i> Remove Photo
                        </button>
                    </form>
                @endif

                <form id="avatar-form" method="POST" action="{{ route('settings.avatar.store') }}" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatar-input" name="avatar" onchange="this.form.submit()">
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h4 class="text-xs font-semibold text-slate-500 mb-4">Quick Stats</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Member Since</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900 mb-6">Personal Information</h3>
                
                <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.input 
                            label="Full Name" 
                            name="name" 
                            :value="old('name', $user->name)" 
                            icon="fa-user" 
                            required 
                            :error="$errors->first('name')"
                        />
                        <x-ui.input 
                            label="Email Address" 
                            type="email" 
                            name="email" 
                            :value="old('email', $user->email)" 
                            icon="fa-envelope" 
                            required 
                            :error="$errors->first('email')"
                        />
                        <x-ui.input 
                            label="Phone Number" 
                            name="phone" 
                            :value="old('phone', $user->phone)" 
                            icon="fa-phone" 
                            :error="$errors->first('phone')"
                        />
                        <x-ui.input 
                            label="City" 
                            name="address" 
                            :value="old('address', $user->address)" 
                            icon="fa-map-marker-alt" 
                            :error="$errors->first('address')"
                        />
                    </div>

                    <div class="flex justify-end pt-4">
                        <x-ui.button type="submit">
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50/30 p-8 rounded-xl border border-red-100/50">
                <h3 class="text-sm font-semibold text-red-900 mb-1">Delete Account</h3>
                <p class="text-xs text-red-600/70 mb-6">Permanently remove your account and all associated booking history.</p>
                
                <x-ui.button variant="danger" x-data="" @click="$dispatch('open-modal', 'confirm-user-deletion')">
                    Deactivate Account
                </x-ui.button>
            </div>
        </div>
    </div>
</x-customer-layout>
