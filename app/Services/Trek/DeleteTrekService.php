<?php

namespace App\Services\Trek;

use App\Models\Trek;


/**
 * Yo DeleteTrekService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class DeleteTrekService
{
    public function __construct(
        private readonly TrekGalleryService $trekGalleryService
    ) {}

    /**
     * Yo method le handle related business flow execute garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function handle(Trek $trek): void
    {
        $trek->loadMissing('images');
        $this->trekGalleryService->deleteAll($trek);
        $trek->delete();
    }
}






