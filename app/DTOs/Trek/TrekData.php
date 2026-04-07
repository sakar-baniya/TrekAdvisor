<?php

namespace App\DTOs\Trek;

class TrekData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly float $basePrice,
        public readonly int $durationDays,
        public readonly string $difficulty,
        public readonly ?string $status = 'draft',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            basePrice: (float) $data['base_price'],
            durationDays: (int) $data['duration_days'],
            difficulty: $data['difficulty'],
            status: $data['status'] ?? 'draft',
        );
    }
}
