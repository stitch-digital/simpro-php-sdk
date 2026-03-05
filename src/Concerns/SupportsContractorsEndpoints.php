<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ContractorResource;

trait SupportsContractorsEndpoints
{
    public function contractors(int $companyId = 0): ContractorResource
    {
        return new ContractorResource($this, $companyId);
    }
}
