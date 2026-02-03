<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * Custom field list item DTO (setup).
 */
final readonly class CustomFieldListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Text',
        public int $order = 0,
        public bool $locked = false,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? 'Text',
            order: (int) ($data['Order'] ?? 0),
            locked: (bool) ($data['Locked'] ?? false),
        );
    }
}
