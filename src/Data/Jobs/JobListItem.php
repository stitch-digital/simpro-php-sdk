<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobListItem
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?JobTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            description: $data['Description'] ?? null,
            total: isset($data['Total']) ? JobTotal::fromArray($data['Total']) : null,
        );
    }
}
