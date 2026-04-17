<x-app-layout>
    <!-- Background Hero -->
    <div class="absolute top-0 left-0 w-full h-[260px] bg-gradient-to-br from-slate-900 to-slate-800 z-0"></div>

    <div class="relative z-10 pt-[100px] pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto" x-data="{ editing: false }">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 md:p-8">
                
                <!-- Header with Avatar -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 pb-8 border-b border-slate-100 text-center sm:text-left">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-slate-900 to-slate-700 flex items-center justify-center text-white font-extrabold text-3xl shadow-md shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 uppercase tracking-wider">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8" x-show="!editing">
                    <button type="button" @click="editing = true" class="inline-flex justify-center items-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-lg font-bold text-white hover:bg-slate-800 transition-colors shadow-sm w-full sm:w-auto">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </button>
                    <a href="{{ request()->header('Referer') ? request()->header('Referer') : route('home') }}" class="inline-flex justify-center items-center px-5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg font-bold text-slate-700 hover:bg-slate-100 transition-colors shadow-sm w-full sm:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>

                <!-- Profile Form (for editing) -->
                <form method="POST" action="{{ route('settings.profile.update') }}" x-show="editing" style="display: none;" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Profile Information Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="mb-2" />
                            <x-text-input id="name" type="text" name="name" :value="old('name', $user->name)" required maxlength="255" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="mb-2" />
                            <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required maxlength="255" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" class="mb-2" />
                            <x-text-input id="phone" type="tel" name="phone" :value="old('phone', $user->phone ?? '')" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10" placeholder="10-digit phone" />
                            <p class="text-xs text-slate-500 mt-1">Optional. Use a 10-digit phone number.</p>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label :value="__('Account Type')" class="mb-2" />
                            <div class="w-full rounded-xl border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-medium cursor-not-allowed">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="address" :value="__('Address')" class="mb-2" />
                            <textarea id="address" name="address" maxlength="500" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 px-4 py-3 bg-slate-50 focus:bg-white transition-colors min-h-[100px]">{{ old('address', $user->address ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Edit Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-100">
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-slate-900 border border-transparent rounded-lg font-bold text-white hover:bg-slate-800 transition-colors shadow-sm w-full sm:w-auto">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                        <button type="button" @click="editing = false" class="inline-flex justify-center items-center px-6 py-3 bg-white border border-slate-300 rounded-lg font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm w-full sm:w-auto">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </button>
                    </div>
                </form>

                <!-- Profile View (default) -->
                <div x-show="!editing">
                    <!-- Profile Information Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-8">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Full Name</h3>
                            <p class="text-base font-semibold text-slate-900">{{ $user->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</h3>
                            <p class="text-base font-semibold text-slate-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Phone Number</h3>
                            @if($user->phone)
                                <p class="text-base font-semibold text-slate-900">{{ $user->phone }}</p>
                            @else
                                <p class="text-sm font-medium text-slate-400 italic">Not provided</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Account Type</h3>
                            <p class="text-base font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Address</h3>
                            @if($user->address)
                                <p class="text-base font-semibold text-slate-900">{{ $user->address }}</p>
                            @else
                                <p class="text-sm font-medium text-slate-400 italic">Not provided</p>
                            @endif
                        </div>
                    </div>

                    <!-- Account Timestamps -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:justify-between text-sm text-slate-500 gap-2">
                        <div>
                            <strong class="text-slate-700">Member since:</strong> {{ $user->created_at->format('M d, Y') }}
                        </div>
                        <div>
                            <strong class="text-slate-700">Last updated:</strong> {{ $user->updated_at->format('M d, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-6 p-4 text-sm font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                        Profile updated successfully!
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
