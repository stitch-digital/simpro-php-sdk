<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCostBreakdown
{
    public function __construct(
        public ?float $actual,
        public ?float $committed,
        public ?float $estimate,
        public ?float $revised,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            actual: isset($data['Actual']) ? (float) $data['Actual'] : null,
            committed: isset($data['Committed']) ? (float) $data['Committed'] : null,
            estimate: isset($data['Estimate']) ? (float) $data['Estimate'] : null,
            revised: isset($data['Revised']) ? (float) $data['Revised'] : null,
        );
    }
}
