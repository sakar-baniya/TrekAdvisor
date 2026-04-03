<?php

namespace App\Services\Trek;

use App\Models\Trek;

class DeleteTrekService
{
    public function __construct(
        private readonly TrekGalleryService $trekGalleryService,
    ) {
    }

    public function handle(Trek $trek): void
    {
        $trek->loadMissing('gallery');
        $this->trekGalleryService->deleteAll($trek);
        $trek->delete();
    }
}
