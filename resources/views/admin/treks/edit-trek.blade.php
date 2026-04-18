<x-dashboard-layout>
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.treks.update', $trek) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center text-[10px] font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Inventory
                    </a>
                    <h1 class="text-4xl font-semibold text-slate-900 tracking-tight">Edit Trek</h1>
                    <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-xs italic">Updating: {{ $trek->title }}</p>
                </div>
                <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-widest leading-none">Status</span>
                        <strong class="text-xs font-semibold text-slate-900 uppercase">{{ $trek->status }}</strong>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="pb-32">
                @include('admin.treks.trek-form-fields', ['trek' => $trek, 'edit' => true])
            </div>

            <!-- Sticky Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-100 p-6 z-50">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="hidden md:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                            <i class="fas fa-edit text-xs"></i>
                        </div>
                        <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                            Modified (Draft)
                        </div>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="{{ route('admin.treks.index') }}" class="flex-1 md:flex-none text-center px-8 py-3 bg-slate-50 text-slate-600 text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all border border-transparent">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 md:flex-none px-12 py-3 bg-slate-900 text-white text-[10px] font-semibold uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-dashboard-layout>
