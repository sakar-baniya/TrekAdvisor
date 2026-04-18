@section('page-title', 'Edit Trek')
@section('page-subtitle', 'Updating: ' . $trek->title)
@section('page-back', route('admin.treks.index'))

@section('page-actions')
@endsection

<x-layouts.dashboard>
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.treks.update', $trek) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <!-- Content Area -->
            <div class="pb-32 space-y-12">
                @include('admin.treks.trek-form-fields', ['trek' => $trek, 'edit' => true])
            </div>

            <!-- Sticky Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-100 p-6 z-50">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="{{ route('admin.treks.index') }}" class="flex-1 md:flex-none text-center px-8 py-3 bg-slate-50 text-slate-600 text-[10px] font-display font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all border border-slate-100">
                            Discard
                        </a>
                        <button type="submit" class="flex-1 md:flex-none px-12 py-3 bg-slate-900 text-white text-[10px] font-display font-black uppercase tracking-[0.2em] rounded-xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 active:scale-[0.98]">
                            Apply Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>

