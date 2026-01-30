<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Licences;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;

final class GetEmployeeLicenceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $licenceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences/{$this->licenceId}";
    }

    public function createDtoFromResponse(Response $response): Licence
    {
        return Licence::fromResponse($response);
    }
}
