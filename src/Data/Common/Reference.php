<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for ID/Name reference pairs.
 *
 * Used throughout the API to reference related entities without full details.
 */
final readonly class Reference
{
    public function __construct(
        public int $id,
        public ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
        );
    }

    /**
     * Create from just an ID.
     */
    public static function fromId(int $id): self
    {
        return new self(id: $id);
    }
}
