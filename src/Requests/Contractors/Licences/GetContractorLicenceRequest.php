<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\Licences;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;

final class GetContractorLicenceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $licenceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/licences/{$this->licenceId}";
    }

    public function createDtoFromResponse(Response $response): Licence
    {
        return Licence::fromResponse($response);
    }
}
