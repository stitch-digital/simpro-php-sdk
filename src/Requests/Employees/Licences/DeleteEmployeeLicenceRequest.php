<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Licences;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteEmployeeLicenceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $licenceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences/{$this->licenceId}";
    }
}
