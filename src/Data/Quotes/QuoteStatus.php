<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteStatus
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $color,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
            color: $data['Color'] ?? null,
        );
    }
}
