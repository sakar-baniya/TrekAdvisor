<?php

namespace App\Services\Trek;

use App\Models\Trek;
use App\Services\Shared\GalleryImageService;
use Illuminate\Http\Request;

class TrekGalleryService
{
    public function __construct(
        private readonly GalleryImageService $galleryImageService,
    ) {
    }

    public function syncHeroImage(Request $request, array &$payload, ?Trek $trek = null): void
    {
        $this->galleryImageService->syncHeroImage($request, $payload, 'treks', $trek);
    }

    public function syncGallery(Request $request, Trek $trek): void
    {
        $this->galleryImageService->syncGallery($request, $trek, 'treks/gallery');
    }

    public function deleteAll(Trek $trek): void
    {
        $this->galleryImageService->deleteAll($trek);
    }
}
