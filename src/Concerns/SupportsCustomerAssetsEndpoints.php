<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\CustomerAssetResource;

trait SupportsCustomerAssetsEndpoints
{
    public function customerAssets(int $companyId = 0): CustomerAssetResource
    {
        return new CustomerAssetResource($this, $companyId);
    }
}
