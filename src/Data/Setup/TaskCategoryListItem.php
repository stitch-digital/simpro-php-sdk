<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * Task category list item DTO.
 */
final readonly class TaskCategoryListItem
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
        );
    }
}
