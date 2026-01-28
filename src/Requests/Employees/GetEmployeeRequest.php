<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Employees\Employee;

final class GetEmployeeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}";
    }

    public function createDtoFromResponse(Response $response): Employee
    {
        return Employee::fromResponse($response);
    }
}
