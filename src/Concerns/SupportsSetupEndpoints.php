<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\SetupResource;

trait SupportsSetupEndpoints
{
    public function setup(int|string $companyId = 0): SetupResource
    {
        return new SetupResource($this, $companyId);
    }
}
