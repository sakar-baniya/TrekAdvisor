<x-layouts.customer 
    title="Change Password" 
    subtitle="Confirm your current password to update it."
    :breadcrumb="['Settings' => route('settings.profile.show'), 'Security' => route('settings.security.show'), 'Change Password' => null]"
    :hideFooter="true"
>
    <div class="max-w-2xl">
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-6">Update Password</h3>
            
            <form method="POST" action="{{ route('settings.security.password') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="space-y-5">
                    <x-ui.input 
                        label="Current Password" 
                        type="password" 
                        name="current_password" 
                        icon="fa-lock" 
                        required 
                        autocomplete="current-password"
                        :error="$errors->first('current_password')"
                    />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-ui.input 
                            label="New Password" 
                            type="password" 
                            name="password" 
                            icon="fa-key" 
                            required 
                            autocomplete="new-password"
                            :error="$errors->first('password')"
                        />
                        <x-ui.input 
                            label="Confirm New Password" 
                            type="password" 
                            name="password_confirmation" 
                            icon="fa-check-double" 
                            required 
                            autocomplete="new-password"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-8">
                    <a href="{{ route('settings.security.show') }}" class="text-[11px] font-semibold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Cancel
                    </a>
                    <x-ui.button type="submit">
                        Update Password
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.customer>

