<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ScheduleResource;

trait SupportsSchedulesEndpoints
{
    public function schedules(int $companyId = 0): ScheduleResource
    {
        return new ScheduleResource($this, $companyId);
    }
}
