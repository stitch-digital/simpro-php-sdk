<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\JobResource;

trait SupportsJobsEndpoints
{
    public function jobs(int|string $companyId = 0): JobResource
    {
        return new JobResource($this, $companyId);
    }
}
