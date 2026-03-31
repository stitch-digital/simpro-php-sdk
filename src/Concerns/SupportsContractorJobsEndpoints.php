<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ContractorJobResource;

trait SupportsContractorJobsEndpoints
{
    public function contractorJobs(int $companyId = 0): ContractorJobResource
    {
        return new ContractorJobResource($this, $companyId);
    }
}
