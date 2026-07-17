<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\Jobs\JobLogResource;

trait SupportsJobLogsEndpoints
{
    public function jobLogs(int $companyId = 0): JobLogResource
    {
        return new JobLogResource($this, $companyId);
    }
}
