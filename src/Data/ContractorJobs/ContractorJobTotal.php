<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorJobs;

final readonly class ContractorJobTotal
{
    public function __construct(
        public float $exTax,
        public float $incTax,
        public float $reverseChargeTax,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
            reverseChargeTax: (float) ($data['ReverseChargeTax'] ?? 0),
        );
    }
}
