<?php

namespace App\Services\Hotel;

use App\Models\Hotel;
use App\Services\Shared\GalleryImageService;
use Illuminate\Http\Request;

class HotelGalleryService
{
    public function __construct(
        private readonly GalleryImageService $galleryImageService,
    ) {
    }

    public function syncHeroImage(Request $request, array &$payload, ?Hotel $hotel = null): void
    {
        $this->galleryImageService->syncHeroImage($request, $payload, 'hotels', $hotel);
    }

    public function syncGallery(Request $request, Hotel $hotel): void
    {
        $this->galleryImageService->syncGallery($request, $hotel, 'hotels/gallery');
    }
}
