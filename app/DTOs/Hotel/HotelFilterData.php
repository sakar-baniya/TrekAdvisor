<?php

namespace App\DTOs\Hotel;

use Illuminate\Http\Request;

class HotelFilterData
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $location = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly ?string $sortBy = 'popularity',
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->input('search'),
            location: $request->input('location'),
            minPrice: $request->filled('min_price') ? (float) $request->input('min_price') : null,
            maxPrice: $request->filled('max_price') ? (float) $request->input('max_price') : null,
            sortBy: $request->input('sort', 'popularity'),
        );
    }
}
