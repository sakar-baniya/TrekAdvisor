@section('page-title', 'Create New Trek')
@section('page-subtitle', 'Define a new adventure for the global marketplace.')
@section('page-back', route('admin.treks.index'))

<x-layouts.dashboard>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <form action="{{ route('admin.treks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Content Area -->
            <div class="pb-32 space-y-12">
                @include('admin.treks.trek-form-fields', ['trek' => $trek])
            </div>

            <!-- Sticky Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-200/60 p-6 z-50">
                <div class="max-w-6xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="{{ route('admin.treks.index') }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl transition-all">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 md:flex-none px-10 py-2.5 bg-slate-900 text-white hover:bg-slate-800 text-sm font-semibold rounded-xl transition-all shadow-lg shadow-slate-900/10">
                            Create & Publish
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>

