<?php

namespace App\DTOs\Trek;

use Illuminate\Http\Request;

class TrekFilterData
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $location = null,
        public readonly ?string $difficulty = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly string $sortBy = 'popularity',
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->query('search'),
            location: $request->query('location'),
            difficulty: $request->query('difficulty'),
            minPrice: $request->query('min_price') ? (float) $request->query('min_price') : null,
            maxPrice: $request->query('max_price') ? (float) $request->query('max_price') : null,
            sortBy: $request->query('sort', 'popularity'),
        );
    }
}
