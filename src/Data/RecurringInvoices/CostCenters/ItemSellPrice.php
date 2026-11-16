<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

/**
 * A line item's unit sell price, with and without its discount applied.
 */
final readonly class ItemSellPrice
{
    public function __construct(
        public float $exTax,
        public float $incTax,
        public float $exDiscountExTax,
        public float $exDiscountIncTax,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
            exDiscountExTax: (float) ($data['ExDiscountExTax'] ?? 0),
            exDiscountIncTax: (float) ($data['ExDiscountIncTax'] ?? 0),
        );
    }
}
