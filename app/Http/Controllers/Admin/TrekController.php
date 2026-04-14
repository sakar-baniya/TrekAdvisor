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
 * Yo TrekController controller le trek controller ko request/response flow handle garcha.
 *
 * Why:
 * Route bata aaune kaam yaha rakheko le flow clear huncha, check haru euta thau ma huncha, ra debug garna sajilo huncha.
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
     * Yo function le index ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function index(Request $request): View
    {
        return view('admin.treks.trek-list', $this->adminTrekQueryService->paginate($request));
    }

    /**
     * Yo function le create ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function create(): View
    {
        return view('admin.treks.create-trek', [
            'trek' => $this->adminTrekQueryService->makeDraft(),
        ]);
    }

    /**
     * Yo function le store ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function store(StoreTrekRequest $request): RedirectResponse
    {
        $trek = $this->upsertTrekService->create($request);

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek created successfully.');
    }

    /**
     * Yo function le show ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function show(Trek $trek): View
    {
        return view('admin.treks.trek-details', [
            'trek' => $this->adminTrekQueryService->loadForShow($trek),
        ]);
    }

    /**
     * Yo function le edit ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function edit(Trek $trek): View
    {
        return view('admin.treks.edit-trek', [
            'trek' => $this->adminTrekQueryService->loadForEdit($trek),
        ]);
    }

    /**
     * Yo function le update ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function update(UpdateTrekRequest $request, Trek $trek): RedirectResponse
    {
        $trek = $this->upsertTrekService->update($request, $trek);

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek updated successfully.');
    }

    /**
     * Yo function le destroy ko kaam handle garcha.
     *
     * Why:
     * Request bata aako data process garera sahi view/response return garna yo function chahinchha.
     */
    public function destroy(Trek $trek): RedirectResponse
    {
        $this->deleteTrekService->handle($trek);

        return redirect()
            ->route('admin.treks.index')
            ->with('success', 'Trek deleted successfully.');
    }
}



