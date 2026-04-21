@section('page-title', 'User Accounts')
@section('page-subtitle', 'Manage user roles, partner approvals, and platform permissions.')

@section('page-actions')
    <a href="{{ route('admin.users.create-staff') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-800 transition-all shadow-sm">
        <i class="fas fa-plus mr-2 opacity-70"></i> Add Staff
    </a>
@endsection

<x-layouts.dashboard>




    <!-- Filters Section -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-10">
        <div class="px-5 py-4 border-b border-slate-100 bg-white">
            <h3 class="text-base font-semibold text-slate-900">Find Users</h3>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-6">
                <div class="flex-1 space-y-1.5">
                    <label for="search" class="text-xs font-medium text-slate-500 ml-1">Keyword Search</label>
                    <div class="relative group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors"></i>
                        <input id="search" 
                               type="search" 
                               name="search" 
                               value="{{ $search }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-11 pr-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all transition-colors" 
                               placeholder="Search by identity or email..." />
                    </div>
                </div>
                <div class="w-full lg:w-72 space-y-1.5">
                    <label for="role" class="text-xs font-medium text-slate-500 ml-1">Account Role</label>
                    <select name="role" id="role" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="">All Account Types</option>
                        @foreach (['admin' => 'Administrators', 'staff' => 'Internal Staff', 'customer' => 'Retail Customers', 'hotel_owner' => 'Hotel Owners'] as $value => $label)
                            <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="h-[43px] px-8 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-all shadow-sm">
                    Apply Filters
                </button>
            </form>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-medium text-slate-500 border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-4">Verified User</th>
                        <th class="px-6 py-4">Platform Role</th>
                        <th class="px-6 py-4">Partner Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50/50">
                    @foreach ($users as $user)
                        <tr class="group hover:bg-slate-50/30 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium border border-slate-200 bg-slate-50 text-slate-600">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-10 py-6">
                                @if ($user->role === 'hotel_owner')
                                    @php
                                        $approved = $user->approval_status === 'approved';
                                    @endphp
                                    <div class="flex items-center gap-2.5">
                                         <span class="w-2 h-2 rounded-full {{ $approved ? 'bg-emerald-500 shadow-sm shadow-emerald-500/20' : 'bg-amber-400 animate-pulse shadow-sm shadow-amber-400/20' }}"></span>
                                         <span class="text-xs font-medium {{ $approved ? 'text-emerald-700' : 'text-amber-700' }}">
                                             {{ $approved ? 'Approved' : 'Pending Verification' }}
                                         </span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-300 italic opacity-40">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    @if ($user->role === 'hotel_owner' && $user->approval_status !== 'approved')
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100"
                                                    onclick="return confirm('Approve this hotel owner account? They will gain full access to the Hotel Owner dashboard.')">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="text-xs font-semibold border-slate-200 rounded-lg py-1.5 pl-3 pr-8 focus:ring-slate-900/10 focus:border-slate-900/30 bg-slate-50/50 appearance-none cursor-pointer">
                                            @foreach(['admin', 'staff', 'customer', 'hotel_owner'] as $r)
                                                <option value="{{ $r }}" @selected($user->role === $r)>{{ str_replace('_', ' ', $r) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-all shadow-sm">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    </form>

                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all border border-red-100"
                                                    onclick="return confirm('Are you sure you want to PERMANENTLY delete this user account? This action cannot be undone.')">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="mt-8 flex justify-center pb-12">
            {{ $users->links() }}
        </div>
    @endif
</x-layouts.dashboard>

