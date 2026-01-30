<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ActivityScheduleResource;

trait SupportsActivitySchedulesEndpoints
{
    public function activitySchedules(int|string $companyId = 0): ActivityScheduleResource
    {
        return new ActivityScheduleResource($this, $companyId);
    }
}
