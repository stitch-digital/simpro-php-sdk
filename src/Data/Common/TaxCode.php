<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for tax code information.
 *
 * Used in totals and pricing structures where tax code details are included.
 */
final readonly class TaxCode
{
    public function __construct(
        public int $id,
        public ?string $code = null,
        public ?string $type = null,
        public ?float $rate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            code: $data['Code'] ?? null,
            type: $data['Type'] ?? null,
            rate: isset($data['Rate']) ? (float) $data['Rate'] : null,
        );
    }

    /**
     * Check if this is a compound tax type.
     */
    public function isCompound(): bool
    {
        return $this->type === 'Compound';
    }

    /**
     * Check if this is a single tax type.
     */
    public function isSingle(): bool
    {
        return $this->type === 'Single';
    }
}
