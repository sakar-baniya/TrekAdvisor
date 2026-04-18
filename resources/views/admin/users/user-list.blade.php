<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Identity & Access</h1>
            <p class="text-slate-500 font-medium mt-1">Manage user roles, partner approvals, and platform permissions.</p>
        </div>
        <a href="{{ route('admin.users.create-staff') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-semibold uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
            <i class="fas fa-plus mr-2 text-sm"></i> Add Staff User
        </a>
    </div>

    <!-- Alert System -->
    @if (session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
            <p class="text-[10px] font-semibold text-emerald-800 uppercase tracking-widest">Success</p>
            <p class="text-xs font-semibold text-emerald-600 mt-1 uppercase italic tracking-tight">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <p class="text-[10px] font-semibold text-red-800 uppercase tracking-widest">Error</p>
            <p class="text-xs font-semibold text-red-600 mt-1 uppercase italic tracking-tight">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20">
            <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-widest">Search & Segmentation</h3>
        </div>
        <div class="p-8">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-6">
                <div class="flex-1">
                    <x-input-label for="search" value="Keyword Search" class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <x-text-input type="search" name="search" id="search" value="{{ $search }}" placeholder="Search by name or email address..." />
                </div>
                <div class="w-full lg:w-64">
                    <x-input-label for="role" value="Account Role" class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400" />
                    <select name="role" id="role" class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-900 focus:ring-slate-900 focus:border-slate-900 py-3">
                        <option value="">All Account Types</option>
                        @foreach (['admin' => 'Administrators', 'staff' => 'Internal Staff', 'customer' => 'Retail Customers', 'hotel_owner' => 'Hotel Owners'] as $value => $label)
                            <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full lg:w-auto px-8 py-3.5 bg-slate-100 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                    Apply List Filters
                </button>
            </form>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-400 border-b border-slate-50 bg-slate-50/20">
                        <th class="px-8 py-5">Verified User</th>
                        <th class="px-8 py-5">Platform Role</th>
                        <th class="px-8 py-5">Partner Status</th>
                        <th class="px-8 py-5">Joined</th>
                        <th class="px-8 py-5 text-right">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic-hover">
                    @forelse ($users as $user)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tight">{{ $user->name }}</div>
                                        <div class="text-[10px] font-medium text-slate-400 lowercase tracking-wider">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-semibold uppercase tracking-widest border border-slate-100 bg-slate-50 text-slate-600">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if ($user->role === 'hotel_owner')
                                    @php
                                        $approved = $user->approval_status === 'approved';
                                    @endphp
                                    <div class="flex items-center gap-2">
                                         <span class="w-1.5 h-1.5 rounded-full {{ $approved ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse' }}"></span>
                                         <span class="text-[9px] font-semibold uppercase tracking-widest {{ $approved ? 'text-emerald-700' : 'text-amber-700' }}">
                                             {{ $approved ? 'Approved' : 'Pending Verification' }}
                                         </span>
                                    </div>
                                @else
                                    <span class="text-[9px] font-semibold text-slate-300 uppercase italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-end gap-4">
                                    @if ($user->role === 'hotel_owner' && $user->approval_status !== 'approved')
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="text-[10px] font-semibold uppercase border-slate-100 rounded-xl py-2 pl-3 pr-8 focus:ring-slate-900 focus:border-slate-900 bg-slate-50/50">
                                            @foreach(['admin', 'staff', 'customer', 'hotel_owner'] as $r)
                                                <option value="{{ $r }}" @selected($user->role === $r)>{{ str_replace('_', ' ', $r) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-all">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-slash text-2xl text-slate-200"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-400 italic">No users found matching your segments.</p>
                            </td>
                        </tr>
                    @endforelse
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
</x-dashboard-layout>
