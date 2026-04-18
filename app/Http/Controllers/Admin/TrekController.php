<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrekRequest;
use App\Http\Requests\UpdateTrekRequest;
use App\Models\Trek;
use App\Services\Trek\AdminTrekQueryService;
use App\Services\Trek\DeleteTrekService;
use App\Services\Trek\UpsertTrekService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin Trek Controller: Trek haru banaune, edit garne, ra delete garne thau.
 *
 * Function:
 * Services (Query, Upsert, Delete) call garera business logic chhuttyayeko chha. Database sidhai yaha bata chalaidaina.
 */
class TrekController extends Controller
{
    public function __construct(
        private readonly AdminTrekQueryService $adminTrekQueryService,
        private readonly UpsertTrekService $upsertTrekService,
        private readonly DeleteTrekService $deleteTrekService,
    ) {
    }

    /**
     * Trek List (Index): Sabai treks ko list dekhaune paginated views.
     */
    public function index(Request $request): View
    {
        return view('admin.treks.trek-list', $this->adminTrekQueryService->paginate($request));
    }

    /**
     * Create Form (Create): Naya trek banaune khali form dekhaune.
     */
    public function create(): View
    {
        return view('admin.treks.create-trek', [
            'trek' => $this->adminTrekQueryService->makeDraft(),
        ]);
    }

    /**
     * Save New Trek (Store): Form bata aayeko naya data sabai validate garera save garne.
     */
    public function store(StoreTrekRequest $request): RedirectResponse
    {
        $trek = $this->upsertTrekService->create($request);

        return redirect()
            ->route('admin.treks.index')
            ->with('success', 'Trek created successfully.');
    }

    /**
     * View Trek Details (Show): Euta trek ko pura details (itinerary/departures) frontend ma herne.
     */
    public function show(Trek $trek): View
    {
        return view('admin.treks.trek-details', [
            'trek' => $this->adminTrekQueryService->loadForShow($trek),
        ]);
    }

    /**
     * Edit Form (Edit): Purano trek update garna form ma details bhariyera dekhaune.
     */
    public function edit(Trek $trek): View
    {
        return view('admin.treks.edit-trek', [
            'trek' => $this->adminTrekQueryService->loadForEdit($trek),
        ]);
    }

    /**
     * Update Trek (Update): Form bata edit gareko naya kura haru save garne.
     */
    public function update(UpdateTrekRequest $request, Trek $trek): RedirectResponse
    {
        $trek = $this->upsertTrekService->update($request, $trek);

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek updated successfully.');
    }

    /**
     * Delete Trek (Destroy): Trek lai database bata delete garna service pathaune.
     */
    public function destroy(Trek $trek): RedirectResponse
    {
        $this->deleteTrekService->handle($trek);

        return redirect()
            ->route('admin.treks.index')
            ->with('success', 'Trek deleted successfully.');
    }
}



