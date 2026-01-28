<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Sections;

final readonly class SectionListItem
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?int $displayOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
        );
    }
}
