<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\TaskResource;

trait SupportsTasksEndpoints
{
    public function tasks(int $companyId = 0): TaskResource
    {
        return new TaskResource($this, $companyId);
    }
}
