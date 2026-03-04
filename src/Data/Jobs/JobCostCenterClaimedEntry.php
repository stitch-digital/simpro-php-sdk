<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCostCenterClaimedEntry
{
    public function __construct(
        public float $percent,
        public float $exTax,
        public float $incTax,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            percent: (float) ($data['Percent'] ?? 0),
            exTax: (float) ($data['Amount']['ExTax'] ?? 0),
            incTax: (float) ($data['Amount']['IncTax'] ?? 0),
        );
    }
}
