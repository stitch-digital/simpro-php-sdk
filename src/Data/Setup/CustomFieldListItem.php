<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * Custom field list item DTO.
 */
final readonly class CustomFieldListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $type = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? null,
        );
    }
}
