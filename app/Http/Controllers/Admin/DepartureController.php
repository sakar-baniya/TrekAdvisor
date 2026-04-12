<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Trek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartureController extends Controller
{
    public function index(Request $request): View
    {
        $trekId = $request->string('trek_id')->toString();
        $status = $request->string('status')->toString();
        $month = $request->string('month')->toString();

        $departures = Departure::query()
            ->with('trek')
            ->when($trekId !== '', fn ($query) => $query->where('trek_id', $trekId))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($month !== '', fn ($query) => $query->whereMonth('start_date', $month))
            ->orderBy('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.departures.index', [
            'departures' => $departures,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrek' => $trekId,
            'selectedStatus' => $status,
            'selectedMonth' => $month,
        ]);
    }

    public function create(Request $request): View
    {
        $departure = new Departure([
            'status' => 'available',
        ]);

        return view('admin.departures.create', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $request->integer('trek_id') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        Departure::query()->create($validated + [
            'booked_seats' => 0,
        ]);

        return redirect()
            ->route('admin.departures.index')
            ->with('success', 'Departure added.');
    }

    public function show(Departure $departure): RedirectResponse
    {
        return redirect()->route('admin.departures.edit', $departure);
    }

    public function edit(Departure $departure): View
    {
        $departure->load('trek');

        return view('admin.departures.edit', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $departure->trek_id,
        ]);
    }

    public function update(Request $request, Departure $departure): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        if (($validated['booked_seats'] ?? $departure->booked_seats) > $validated['capacity']) {
            return back()
                ->withErrors(['capacity' => 'Capacity must be greater than or equal to booked seats.'])
                ->withInput();
        }

        $departure->update($validated);

        return redirect()
            ->route('admin.departures.edit', $departure)
            ->with('success', 'Departure updated.');
    }

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'trek_id' => ['required', 'exists:treks,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'price' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'booked_seats' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'full', 'completed'])],
        ]);
    }
}

