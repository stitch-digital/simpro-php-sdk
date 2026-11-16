<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for catalog and prebuild references that carry a part number alongside the ID and name.
 */
final readonly class PartReference
{
    public function __construct(
        public int $id,
        public ?string $partNo = null,
        public ?string $name = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            partNo: $data['PartNo'] ?? null,
            name: $data['Name'] ?? null,
        );
    }
}
