<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\EmployeeResource;

trait SupportsEmployeesEndpoints
{
    public function employees(int $companyId = 0): EmployeeResource
    {
        return new EmployeeResource($this, $companyId);
    }
}
