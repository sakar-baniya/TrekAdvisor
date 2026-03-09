<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tight">
                {{ __('Trek List') }}
            </h2>
            <a href="{{ route('admin.treks.create') }}" class="inline-flex items-center px-8 py-4 bg-gray-900 border border-transparent rounded-2xl font-black text-xs text-white uppercase tracking-widest hover:bg-black active:scale-95 focus:outline-none transition-all shadow-2xl shadow-gray-200">
                <i class="fas fa-plus mr-3"></i> Add New Trek
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-10 p-6 bg-white border-l-4 border-gray-900 rounded-r-2xl shadow-xl shadow-gray-100 flex items-center animate-fade-in-down">
            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white mr-4">
                <i class="fas fa-check"></i>
            </div>
            <span class="text-sm font-bold text-gray-900 tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Trek Table Container -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-gray-100/50">
        <div class="p-0">
            <div class="overflow-x-auto text-dark">
                <table class="min-w-full divide-y divide-gray-50">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Trek Name</th>
                            <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Price</th>
                            <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Difficulty</th>
                            <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                            <th scope="col" class="px-10 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($treks as $trek)
                            <tr class="hover:bg-gray-50/20 transition-all duration-300 group">
                                <td class="px-10 py-8 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="relative h-16 w-24 flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                            <img src="{{ $trek->image ?? 'https://via.placeholder.com/300x200?text=NO+IMAGE' }}" alt="{{ $trek->title }}" class="h-16 w-24 object-cover rounded-xl shadow-lg border border-gray-100">
                                            <div class="absolute inset-0 ring-1 ring-inset ring-black/10 rounded-xl"></div>
                                        </div>
                                        <div class="ml-8">
                                            <div class="text-base font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $trek->title }}</div>
                                            <div class="flex items-center mt-1.5">
                                                <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded border border-gray-100">{{ $trek->slug }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-black text-gray-900">${{ number_format($trek->base_price) }}</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Price</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    @php
                                        $difficultyColors = [
                                            'Easy'    => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                            'Moderate' => 'text-blue-600 bg-blue-50 border-blue-100',
                                            'Difficult' => 'text-orange-600 bg-orange-50 border-orange-100',
                                            'Extreme'  => 'text-rose-600 bg-rose-50 border-rose-100',
                                        ];
                                        $dColor = $difficultyColors[$trek->difficulty] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $dColor }} border shadow-sm">
                                        <i class="fas fa-bolt mr-2 opacity-50"></i> {{ $trek->difficulty }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    @if($trek->status == 'Active')
                                        <div class="flex items-center">
                                            <span class="relative flex h-2 w-2 mr-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            </span>
                                            <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Active</span>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <span class="h-2 w-2 rounded-full bg-gray-200 mr-3"></span>
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Inactive</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <a href="{{ route('admin.treks.show', $trek->id) }}" class="p-2.5 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all shadow-sm" title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.treks.edit', $trek->id) }}" class="p-2.5 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all shadow-sm" title="Edit Trek">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.treks.destroy', $trek->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this trek?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-white border border-red-50 rounded-xl text-red-200 hover:text-red-600 hover:border-red-600 transition-all shadow-sm" title="Delete Trek">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-24 text-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center text-gray-200 mx-auto mb-6">
                                        <i class="fas fa-mountain text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-gray-400 uppercase tracking-widest">No Treks Found</h3>
                                    <p class="text-sm text-gray-300 mt-2 font-medium italic">Start by adding your first trek.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($treks->hasPages())
                <div class="px-10 py-8 bg-gray-50/30 border-t border-gray-50">
                    {{ $treks->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Total Info -->
    <div class="mt-10 flex justify-between items-center text-[9px] font-black text-gray-300 uppercase tracking-[0.3em]">
        <div>TOTAL TREKS: {{ $treks->total() }}</div>
        <div class="flex items-center space-x-6">
            <span class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> ACTIVE</span>
            <span class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-gray-200 mr-2"></span> INACTIVE</span>
        </div>
    </div>
</x-dashboard-layout>
