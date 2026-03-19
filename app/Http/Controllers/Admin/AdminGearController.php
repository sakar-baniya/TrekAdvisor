<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GearItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminGearController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $type = $request->string('type')->toString();
        $availability = $request->string('availability')->toString();

        $gearItems = GearItem::query()
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->when($availability === 'available', fn ($query) => $query->where('available_stock', '>', 0))
            ->when($availability === 'out', fn ($query) => $query->where('available_stock', '<=', 0))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.gear.index', [
            'gearItems' => $gearItems,
            'search' => $search,
            'type' => $type,
            'availability' => $availability,
            'types' => GearItem::query()->select('type')->distinct()->orderBy('type')->pluck('type'),
        ]);
    }

    public function create(): View
    {
        return view('admin.gear.create', [
            'gearItem' => new GearItem(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $payload = $this->payloadFromRequest($request, $validated);

        GearItem::query()->create($payload);

        return redirect()
            ->route('admin.gear.index')
            ->with('success', 'Gear item added.');
    }

    public function edit(GearItem $gear): View
    {
        return view('admin.gear.edit', [
            'gearItem' => $gear,
        ]);
    }

    public function update(Request $request, GearItem $gear): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $payload = $this->payloadFromRequest($request, $validated, $gear);

        $gear->update($payload);

        return redirect()
            ->route('admin.gear.edit', $gear)
            ->with('success', 'Gear item updated.');
    }

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'total_stock' => ['required', 'integer', 'min:0'],
            'available_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);
    }

    protected function payloadFromRequest(Request $request, array $validated, ?GearItem $gearItem = null): array
    {
        $payload = $validated;

        if ($request->hasFile('image')) {
            if ($gearItem?->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $gearItem->image));
            }

            $payload['image'] = Storage::url($request->file('image')->store('gear', 'public'));
        }

        if ($payload['available_stock'] > $payload['total_stock']) {
            $payload['available_stock'] = $payload['total_stock'];
        }

        return $payload;
    }
}
