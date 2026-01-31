<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\CustomerResource;

trait SupportsCustomersEndpoints
{
    public function customers(int $companyId = 0): CustomerResource
    {
        return new CustomerResource($this, $companyId);
    }
}
