<x-guest-layout>
    <div class="mb-8 text-center">
        <p class="text-emerald-500 font-bold uppercase tracking-wider text-xs mb-2">Verify Email</p>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Check your inbox</h1>
        <div class="h-1 w-12 bg-slate-900 rounded mt-4 mx-auto" aria-hidden="true"></div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col items-center gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf

            <div>
                <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-full shadow-sm text-sm font-extrabold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all duration-200">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full text-center mt-2">
            @csrf

            <button
                type="button"
                class="text-sm font-bold text-slate-900 hover:underline transition-colors"
                onclick="if (window.showConfirm) { showConfirm({ title: 'Sign Out', message: 'Are you sure you want to sign out?', buttonText: 'Sign Out', buttonClass: 'bg-red-600 hover:bg-red-700 text-white', form: this.closest('form') }); } else { this.closest('form').submit(); }"
            >
                {{ __('Sign Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
