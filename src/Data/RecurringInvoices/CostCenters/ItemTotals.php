<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

/**
 * The price and time totals Simpro reports on catalog, prebuild and service fee lines.
 */
final readonly class ItemTotals
{
    public function __construct(
        public float $priceEx,
        public float $priceInc,
        public float $estimatedTime,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            priceEx: (float) ($data['PriceEx'] ?? 0),
            priceInc: (float) ($data['PriceInc'] ?? 0),
            estimatedTime: (float) ($data['EstimatedTime'] ?? 0),
        );
    }
}
