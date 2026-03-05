<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\SiteResource;

trait SupportsSitesEndpoints
{
    public function sites(int $companyId = 0): SiteResource
    {
        return new SiteResource($this, $companyId);
    }
}
