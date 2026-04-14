<?php

namespace App\Services\Trek;

use App\Models\Trek;
use App\Services\Shared\GalleryImageService;
use Illuminate\Http\Request;


/**
 * Yo TrekGalleryService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class TrekGalleryService
{
    /**
     * Yo method le syncUnifiedMedia related state change safely apply garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function syncUnifiedMedia(Request $request, Trek $trek): void
    {
        $this->galleryImageService->syncUnifiedMedia($request, $trek, 'treks/gallery');
    }

    /**
     * Yo method le deleteAll related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function deleteAll(Trek $trek): void
    {
        $this->galleryImageService->deleteAll($trek);
    }
}





