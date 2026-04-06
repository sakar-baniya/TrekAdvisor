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

    public function syncUnifiedMedia(Request $request, Trek $trek): void
    {
        $this->galleryImageService->syncUnifiedMedia($request, $trek, 'treks/gallery');
    }

    public function deleteAll(Trek $trek): void
    {
        $this->galleryImageService->deleteAll($trek);
    }
}
