<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\JobCostCenterResource;

trait SupportsJobCostCentersEndpoints
{
    public function jobCostCenters(int $companyId = 0): JobCostCenterResource
    {
        return new JobCostCenterResource($this, $companyId);
    }
}
