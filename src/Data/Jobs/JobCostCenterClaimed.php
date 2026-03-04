<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCostCenterClaimed
{
    public function __construct(
        public JobCostCenterClaimedEntry $toDate,
        public JobCostCenterClaimedEntry $remaining,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            toDate: JobCostCenterClaimedEntry::fromArray($data['ToDate']),
            remaining: JobCostCenterClaimedEntry::fromArray($data['Remaining']),
        );
    }
}
