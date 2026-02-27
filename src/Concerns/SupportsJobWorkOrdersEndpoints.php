<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\JobWorkOrderResource;

trait SupportsJobWorkOrdersEndpoints
{
    public function jobWorkOrders(int $companyId = 0): JobWorkOrderResource
    {
        return new JobWorkOrderResource($this, $companyId);
    }
}
