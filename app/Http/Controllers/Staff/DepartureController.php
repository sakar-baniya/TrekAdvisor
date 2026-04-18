<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Admin\DepartureController as AdminDepartureController;
use App\Models\Departure;
use App\Models\Trek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff Departure Controller: Staff le trek ka dates manage garne thau.
 *
 * Fixed: Overrides base methods to use staff-specific views and routes,
 * preventing illegal redirects to the admin panel.
 */
class DepartureController extends AdminDepartureController
{
    /**
     * Departure List (Index)
     */
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

        return view('staff.departures.departure-list', [
            'departures' => $departures,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrek' => $trekId,
            'selectedStatus' => $status,
            'selectedMonth' => $month,
        ]);
    }

    /**
     * Create Form
     */
    public function create(Request $request): View
    {
        $departure = new Departure([
            'status' => 'available',
        ]);

        return view('staff.departures.create-departure', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $request->integer('trek_id') ?: null,
        ]);
    }

    /**
     * Store Departure
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        Departure::query()->create($validated + [
            'booked_seats' => 0,
        ]);

        return redirect()
            ->route('staff.departures.index')
            ->with('success', 'Departure added successfully.');
    }

    /**
     * Show to Edit
     */
    public function show(Departure $departure): RedirectResponse
    {
        return redirect()->route('staff.departures.edit', $departure);
    }

    /**
     * Edit Form
     */
    public function edit(Departure $departure): View
    {
        $departure->load('trek');

        return view('staff.departures.edit-departure', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $departure->trek_id,
        ]);
    }

    /**
     * Update Departure
     */
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
            ->route('staff.departures.edit', $departure)
            ->with('success', 'Departure updated successfully.');
    }
}
