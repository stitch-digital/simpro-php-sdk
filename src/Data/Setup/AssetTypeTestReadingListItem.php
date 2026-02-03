<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * AssetTypeTestReadingListItem DTO (list response).
 */
final readonly class AssetTypeTestReadingListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Text',
        public int $order = 0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'],
            type: $data['Type'] ?? 'Text',
            order: (int) ($data['Order'] ?? 0),
        );
    }
}
