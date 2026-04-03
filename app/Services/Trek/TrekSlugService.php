<?php

namespace App\Services\Trek;

use App\Models\Trek;
use Illuminate\Support\Str;

class TrekSlugService
{
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
