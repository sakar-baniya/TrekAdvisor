<section>
    <header>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('settings.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="mb-2" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="mb-2" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-200 text-sm p-4">
                    <p class="text-amber-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="mt-2 text-slate-900 underline font-bold hover:text-slate-700 transition-colors">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-1"
                ><i class="fas fa-check"></i> {{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
