<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

final readonly class CostCenterVendorOrderTotals
{
    public function __construct(
        public ?float $exTax,
        public ?float $incTax,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            exTax: isset($data['ExTax']) ? (float) $data['ExTax'] : null,
            incTax: isset($data['IncTax']) ? (float) $data['IncTax'] : null,
        );
    }
}
