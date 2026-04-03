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

class AdminTrekController extends Controller
{
    public function __construct(
        private readonly AdminTrekQueryService $adminTrekQueryService,
        private readonly UpsertTrekService $upsertTrekService,
        private readonly DeleteTrekService $deleteTrekService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('admin.treks.index', $this->adminTrekQueryService->paginate($request));
    }

    public function create(): View
    {
        return view('admin.treks.create', [
            'trek' => $this->adminTrekQueryService->makeDraft(),
        ]);
    }

    public function store(StoreTrekRequest $request): RedirectResponse
    {
        $trek = $this->upsertTrekService->create($request);

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek created successfully.');
    }

    public function show(Trek $trek): View
    {
        return view('admin.treks.show', [
            'trek' => $this->adminTrekQueryService->loadForShow($trek),
        ]);
    }

    public function edit(Trek $trek): View
    {
        return view('admin.treks.edit', [
            'trek' => $this->adminTrekQueryService->loadForEdit($trek),
        ]);
    }

    public function update(UpdateTrekRequest $request, Trek $trek): RedirectResponse
    {
        $trek = $this->upsertTrekService->update($request, $trek);

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek updated successfully.');
    }

    public function destroy(Trek $trek): RedirectResponse
    {
        $this->deleteTrekService->handle($trek);

        return redirect()
            ->route('admin.treks.index')
            ->with('success', 'Trek deleted successfully.');
    }
}
