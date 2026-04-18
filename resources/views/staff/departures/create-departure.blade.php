<x-layouts.dashboard>
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-10">
            <a href="{{ route('staff.departures.index') }}" class="inline-flex items-center text-[10px] font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Operations
            </a>
            <h1 class="text-4xl font-semibold text-slate-900 tracking-tight">Add Departure</h1>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-xs italic">Schedule a new date for an existing trek</p>
        </div>

        <form action="{{ route('staff.departures.store') }}" method="POST">
            @csrf
            @include('staff.departures.departure-form-fields')
        </form>
    </div>
</x-layouts.dashboard>

