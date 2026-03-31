<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorJobs;

final readonly class ContractorJobRetention
{
    public function __construct(
        public float $amount,
        public float $perClaim,
        public int $periodMonths,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: (float) ($data['Amount'] ?? 0),
            perClaim: (float) ($data['PerClaim'] ?? 0),
            periodMonths: (int) ($data['PeriodMonths'] ?? 0),
        );
    }
}
