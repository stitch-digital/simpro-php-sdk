<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * AssetTypeFailurePointListItem DTO (list response).
 */
final readonly class AssetTypeFailurePointListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public int $displayOrder = 0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'],
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
        );
    }
}
