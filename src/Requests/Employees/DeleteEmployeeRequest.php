<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteEmployeeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $employeeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}";
    }
}
