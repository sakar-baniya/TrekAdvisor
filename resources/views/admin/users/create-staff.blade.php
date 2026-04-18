@section('page-title', 'Add Staff User')
@section('page-subtitle', 'Create an internal account for platform management.')
@section('page-back', route('admin.users.index'))

<x-layouts.dashboard>

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-xs font-medium text-red-700 mb-1">Error</p>
                <p class="text-xs text-red-700">Please check the fields below and try again.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store-staff') }}" class="space-y-6">
            @csrf

            <section class="bg-white p-5 md:p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Identity Details</h3>
                    <p class="text-sm text-slate-500 mt-1">Basic credentials and system role</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all" 
                               required placeholder="e.g. Tenzing Norgay" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all" 
                               required placeholder="name@trekadvisor.com" />
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Platform Role</label>
                        <select name="role" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all appearance-none cursor-pointer" required>
                            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff (Editor Access)</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrator (Full Access)</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="bg-white p-5 md:p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Security</h3>
                    <p class="text-sm text-slate-500 mt-1">Initial password configuration</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Password</label>
                        <input type="password" name="password" 
                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all" 
                               required />
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-500 ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900/30 transition-all" 
                               required />
                    </div>
                </div>
            </section>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-all shadow-sm">
                    Create Platform Account
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>

