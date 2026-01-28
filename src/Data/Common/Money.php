<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for monetary values with tax breakdown.
 *
 * Used throughout the API for totals, prices, and costs.
 * Some contexts include a TaxCode with the breakdown.
 */
final readonly class Money
{
    public function __construct(
        public float $exTax,
        public float $tax,
        public float $incTax,
        public ?TaxCode $taxCode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            tax: (float) ($data['Tax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
            taxCode: isset($data['TaxCode']) && is_array($data['TaxCode']) ? TaxCode::fromArray($data['TaxCode']) : null,
        );
    }

    /**
     * Create from a simple float value (assumes no tax breakdown available).
     */
    public static function fromFloat(float $value): self
    {
        return new self(
            exTax: $value,
            tax: 0,
            incTax: $value,
        );
    }

    /**
     * Check if this represents a zero value.
     */
    public function isZero(): bool
    {
        return $this->incTax === 0.0;
    }

    /**
     * Calculate the tax rate percentage.
     */
    public function taxRate(): float
    {
        if ($this->exTax === 0.0) {
            return 0.0;
        }

        return ($this->tax / $this->exTax) * 100;
    }

    /**
     * Check if tax code information is available.
     */
    public function hasTaxCode(): bool
    {
        return $this->taxCode !== null;
    }
}
