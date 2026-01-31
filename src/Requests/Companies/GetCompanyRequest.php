<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;

final class GetCompanyRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}";
    }

    public function createDtoFromResponse(Response $response): Company
    {
        return Company::fromResponse($response);
    }
}
