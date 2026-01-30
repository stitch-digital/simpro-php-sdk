<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * DTO for tax code information.
 *
 * Based on swagger customer endpoints.
 */
final readonly class TaxCode
{
    public function __construct(
        public int $id,
        public ?string $code,
        public ?string $type,
        public ?float $rate,
        public ?bool $reverseTaxEnabled,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            code: $data['Code'] ?? null,
            type: $data['Type'] ?? null,
            rate: isset($data['Rate']) ? (float) $data['Rate'] : null,
            reverseTaxEnabled: $data['ReverseTaxEnabled'] ?? null,
        );
    }
}
