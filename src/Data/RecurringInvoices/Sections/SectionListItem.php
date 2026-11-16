<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections;

final readonly class SectionListItem
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?int $displayOrder,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
        );
    }
}
