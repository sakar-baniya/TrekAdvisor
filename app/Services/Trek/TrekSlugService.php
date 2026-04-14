<?php

namespace App\Services\Trek;

use App\Models\Trek;
use Illuminate\Support\Str;


/**
 * Yo TrekSlugService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class TrekSlugService
{
    /**
     * Yo method le generate ko service-level kaam handle garcha.
     *
     * Why:
     * Output banne rule yahi method ma clear rakhda format change huda impact track garna sajilo hunchha.
     */
    public function generate(string $title, ?Trek $trek = null): string
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






