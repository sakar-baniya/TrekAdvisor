<?php

namespace App\DTOs\Hotel;

class HotelData
{
    public function __construct(
        public readonly string $name,
        public readonly string $location,
        public readonly string $description,
        public readonly ?string $status = 'draft',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            location: $data['location'],
            description: $data['description'],
            status: $data['status'] ?? 'draft',
        );
    }
}
