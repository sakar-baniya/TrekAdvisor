<?php

namespace App\Services\Trek;

use App\Models\Trek;
use App\Services\Trek\TrekGalleryService;
use App\Services\Trek\TrekSlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Yo UpsertTrekService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class UpsertTrekService
{
    public function __construct(
        private readonly TrekSlugService $trekSlugService,
        private readonly TrekGalleryService $trekGalleryService,
    ) {}

    /**
     * Yo method le create related business flow execute garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function create(Request $request): Trek
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated) {
            $payload = $this->buildPayload($request, $validated);
            $trek = Trek::query()->create($payload);

            $this->syncItineraries($trek, $validated['itinerary'] ?? []);
            $this->trekGalleryService->syncUnifiedMedia($request, $trek);

            return $trek;
        });
    }

    /**
     * Yo method le update related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function update(Request $request, Trek $trek): Trek
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated, $trek) {
            $trek->update($this->buildPayload($request, $validated, $trek));
            
            // Sync the updated base price across all scheduled departures
            $trek->departures()->update(['price' => $trek->base_price]);

            $this->syncItineraries($trek, $validated['itinerary'] ?? []);
            $this->trekGalleryService->syncUnifiedMedia($request, $trek);

            return $trek;
        });
    }

    private function buildPayload(Request $request, array $validated, ?Trek $trek = null): array
    {
        $payload = [
            'title' => $validated['title'],
            'slug' => $this->trekSlugService->generate($validated['title'], $trek),
            'base_price' => $validated['base_price'],
            'difficulty' => \Illuminate\Support\Str::lower($validated['difficulty']),
            'duration_days' => $validated['duration_days'],
            'max_altitude' => $validated['max_altitude'] ?? null,
            'description' => $validated['description'],
            'status' => \Illuminate\Support\Str::lower($validated['status']),
        ];

        // Hero image is now handled separately in syncHeroImage method
        // Don't set it in payload since treks.image field no longer exists

        return $payload;
    }

    private function syncItineraries(Trek $trek, array $itineraries): void
    {
        $trek->itineraries()->delete();

        $rows = collect($itineraries)
            ->filter(fn (array $day) => filled($day['title'] ?? null))
            ->values();

        foreach ($rows as $index => $day) {
            $trek->itineraries()->create([
                'day_number'  => $index + 1,
                'title'       => $day['title'],
                'description' => $day['description'] ?? '',
            ]);
        }
    }
}





