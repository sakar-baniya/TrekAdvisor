<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trek;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTrekController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $difficulty = $request->string('difficulty')->toString();
        $status = $request->string('status')->toString();

        $treks = Trek::query()
            ->withCount('departures')
            ->withSum('departures as total_booked_seats', 'booked_seats')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($difficulty !== '', fn ($query) => $query->where('difficulty', $difficulty))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.treks.index', compact('treks', 'search', 'difficulty', 'status'));
    }

    public function create(): View
    {
        $trek = new Trek();
        $trek->setRelation('itineraries', collect());

        return view('admin.treks.create', compact('trek'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        $trek = DB::transaction(function () use ($request, $validated) {
            $payload = $this->payloadFromValidated($request, $validated);
            $trek = Trek::create($payload);
            $this->syncItineraries($trek, $validated['itinerary'] ?? []);

            return $trek;
        });

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek created successfully.');
    }

    public function show(Trek $trek): View
    {
        $trek->load(['itineraries', 'departures', 'gallery']);

        return view('admin.treks.show', compact('trek'));
    }

    public function edit(Trek $trek): View
    {
        $trek->load('itineraries');

        return view('admin.treks.edit', compact('trek'));
    }

    public function update(Request $request, Trek $trek): RedirectResponse
    {
        $validated = $this->validateRequest($request, $trek);

        DB::transaction(function () use ($request, $validated, $trek) {
            $trek->update($this->payloadFromValidated($request, $validated, $trek));
            $this->syncItineraries($trek, $validated['itinerary'] ?? []);
        });

        return redirect()
            ->route('admin.treks.edit', $trek)
            ->with('success', 'Trek updated successfully.');
    }

    public function destroy(Trek $trek): RedirectResponse
    {
        if ($trek->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $trek->image));
        }

        foreach ($trek->gallery as $image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $image->path));
        }

        $trek->delete();

        return redirect()
            ->route('admin.treks.index')
            ->with('success', 'Trek deleted successfully.');
    }

    protected function validateRequest(Request $request, ?Trek $trek = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'difficulty' => ['required', Rule::in(['Easy', 'Moderate', 'Difficult', 'Extreme'])],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_altitude' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
        ]);
    }

    protected function payloadFromValidated(Request $request, array $validated, ?Trek $trek = null): array
    {
        $payload = [
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title'], $trek),
            'base_price' => $validated['base_price'],
            'difficulty' => $validated['difficulty'],
            'duration_days' => $validated['duration_days'],
            'max_altitude' => $validated['max_altitude'] ?? null,
            'description' => $validated['description'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('image')) {
            if ($trek?->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $trek->image));
            }

            $payload['image'] = Storage::url($request->file('image')->store('treks', 'public'));
        }

        return $payload;
    }

    protected function syncItineraries(Trek $trek, array $itineraries): void
    {
        $trek->itineraries()->delete();

        $rows = collect($itineraries)
            ->filter(fn (array $day) => filled($day['title'] ?? null) && filled($day['description'] ?? null))
            ->values();

        foreach ($rows as $index => $day) {
            $trek->itineraries()->create([
                'day_number' => $index + 1,
                'title' => $day['title'],
                'description' => $day['description'],
            ]);
        }
    }

    protected function generateUniqueSlug(string $title, ?Trek $trek = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 2;

        while (
            Trek::query()
                ->when($trek, fn ($query) => $query->whereKeyNot($trek->id))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
