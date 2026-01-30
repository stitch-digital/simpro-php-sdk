<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ReportResource;

trait SupportsReportsEndpoints
{
    public function reports(int|string $companyId = 0): ReportResource
    {
        return new ReportResource($this, $companyId);
    }
}
