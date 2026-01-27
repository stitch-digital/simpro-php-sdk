<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\CompanyResource;

trait SupportsCompaniesEndpoints
{
    public function companies(): CompanyResource
    {
        return new CompanyResource($this);
    }
}
