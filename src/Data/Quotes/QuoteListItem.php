<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteListItem
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?QuoteTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            description: $data['Description'] ?? null,
            total: ! empty($data['Total']) ? QuoteTotal::fromArray($data['Total']) : null,
        );
    }
}
