<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Trek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin Departure Controller: Trek haru ko start date, end date, ra seats handle garne thau.
 *
 * Function:
 * Kun trek kaile jaane, kati price, ra kati capacity chha bhanne kura set garchha.
 */
class DepartureController extends Controller
{
    /**
     * Departure List (Index): Sabai departures haru ko list, filters sahit dekhaune.
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

        return view('admin.departures.departure-list', [
            'departures' => $departures,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrek' => $trekId,
            'selectedStatus' => $status,
            'selectedMonth' => $month,
        ]);
    }

    /**
     * Create Form (Create): Naya departure thapna ko lagi form dekhaune.
     */
    public function create(Request $request): View
    {
        $departure = new Departure([
            'status' => 'available',
        ]);

        return view('admin.departures.create-departure', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $request->integer('trek_id') ?: null,
        ]);
    }

    /**
     * Save Departure (Store): Form bata aayeko data database ma save garne.
     */
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

    /**
     * Show to Edit (Show): Details dekhauna ko sato sidhai edit page ma redirect garne.
     */
    public function show(Departure $departure): RedirectResponse
    {
        return redirect()->route('admin.departures.edit', $departure);
    }

    /**
     * Edit Form (Edit): Purano departure detail update garne form dekhaune.
     */
    public function edit(Departure $departure): View
    {
        $departure->load('trek');

        return view('admin.departures.edit-departure', [
            'departure' => $departure,
            'treks' => Trek::query()->orderBy('title')->get(),
            'selectedTrekId' => $departure->trek_id,
        ]);
    }

    /**
     * Update Departure (Update): Form bata aayeko data edit garera save garne.
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
            ->route('admin.departures.edit', $departure)
            ->with('success', 'Departure updated.');
    }

    /**
     * Validate (Helper): Form ko data thik chha ki nai check garne validation rules.
     */
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




