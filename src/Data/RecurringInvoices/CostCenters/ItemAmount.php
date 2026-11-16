<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

final readonly class ItemAmount
{
    public function __construct(
        public float $exTax,
        public float $incTax,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
        );
    }
}
