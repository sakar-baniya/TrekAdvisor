<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Services\Shared\GalleryImageService;
use Illuminate\Http\Request;


/**
 * Yo HotelGalleryService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class HotelGalleryService
{
    private GalleryImageService $galleryImageService;

    public function __construct(GalleryImageService $galleryImageService)
    {
        $this->galleryImageService = $galleryImageService;
    }

    /**
     * Yo method le syncHeroImage related state change safely apply garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function syncHeroImage(Request $request, array &$payload, ?Hotel $hotel = null): void
    {
        $this->galleryImageService->syncHeroImage($request, $payload, 'hotels', $hotel);
    }

    /**
     * Yo method le syncGallery related state change safely apply garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function syncGallery(Request $request, Hotel $hotel): void
    {
        $this->galleryImageService->syncGallery($request, $hotel, 'hotels/gallery');
    }
}





